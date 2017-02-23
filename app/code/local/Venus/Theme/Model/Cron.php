<?php
class Venus_Theme_Model_Cron {
	const XML_PATH_ORDER_INACTIVITY_STEP                     = 'sales_email/order_inactivity/step';
	const XML_PATH_ORDER_INACTIVITY_SALESRULE_ID             = 'sales_email/order_inactivity/salesrule_id';
	const XML_PATH_ORDER_INACTIVITY_EMAIL_TEMPLATE           = 'sales_email/order_inactivity/template';
	const XML_PATH_ORDER_INACTIVITY_VIP_EMAIL_TEMPLATE       = 'sales_email/order_inactivity/vip_template';
	const XML_PATH_ORDER_INACTIVITY_FOLLOWUP_EMAIL_TEMPLATE  = 'sales_email/order_inactivity/followup_template';
	const XML_PATH_ORDER_INACTIVITY_COUPON_ONE_WEEK_TEMPLATE = 'sales_email/order_inactivity/one_week_warning_template';
	const XML_PATH_ORDER_INACTIVITY_COUPON_ONE_DAY_TEMPLATE  = 'sales_email/order_inactivity/one_day_warning_template';
	const XML_PATH_ORDER_SHIPMENT_ALERT_EMAIL_TEMPLATE       = 'sales_email/shipment_alert/template';
	const XML_PATH_ORDER_SHIPMENT_ALERT_EMAIL_RECIPIENTS     = 'sales_email/shipment_alert/send_to';

	protected $_storeId;

	protected function _getStoreId() {
		if (!$this->_storeId) {
			$this->_storeId = Mage::app()->getDefaultStoreView()->getId();
		}

		return $this->_storeId;
	}

	public function sendUnshippedOrdersAlertEmail() {
		$dateFlag = Mage::app()->getLocale()->date()->subDay(1);

		/** @var Mage_Sales_Model_Resource_Order_Collection $collection */
		$collection = Mage::getResourceModel('sales/order_collection');
		$collection->addFieldToFilter('main_table.created_at', array('lteq' => $dateFlag))
		           ->addFieldToFilter('sfs.entity_id', array('null' => true))
		           ->addFieldToFilter('state', array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING))
		           ->getSelect()
		           ->reset(Zend_Db_Select::COLUMNS)
		           ->joinLeft(array('sfs' => $collection->getTable('sales/shipment')), 'sfs.order_id = main_table.entity_id', 'entity_id')
		           ->columns('main_table.increment_id')
		           ->columns('main_table.created_at');

		$recipients = explode(',', Mage::getStoreConfig(self::XML_PATH_ORDER_SHIPMENT_ALERT_EMAIL_RECIPIENTS));

		try {
			/** @var Mage_Core_Model_Email_Template $email */
			$email = Mage::getModel('core/email_template');
			$email->setDesignConfig(array('area' => 'frontend', 'store' => $this->_getStoreId()));
			$email->sendTransactional(
				Mage::getStoreConfig(self::XML_PATH_ORDER_SHIPMENT_ALERT_EMAIL_TEMPLATE, $this->_getStoreId()),
				Mage::getStoreConfig(Magestore_Affiliateplus_Model_Transaction::XML_PATH_EMAIL_IDENTITY, $this->_getStoreId()),
				$recipients,
				null,
				array(
					'today'      => now(),
					'collection' => $collection,
				),
				$this->_getStoreId()
			);
		} catch (Exception $e) {
			Mage::logException($e);
		}

		return $this;
	}

	public function prepareOrderInactivityEmails() {
		/** @var Mage_Customer_Model_Resource_Customer_Collection $customerCollection */
		$customerCollection = Mage::getResourceModel('customer/customer_collection');
		$customerCollection->addAttributeToSelect('*');

		/** @var Mage_Customer_Model_Customer $customer */
		foreach ($customerCollection as $customer) {
			if (!$customer->getHasRedeemedCoupon()) {
				$step        = $customer->getOrderInactivityStep();
				$dateCompare = Mage::app()->getLocale()->date()->subDay(Mage::getStoreConfig(self::XML_PATH_ORDER_INACTIVITY_STEP) * $step);

				/** @var Mage_Newsletter_Model_Subscriber $subscriber */
				$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());

				if ($subscriber->isSubscribed()) {
					try {
						/** @var Mage_Sales_Model_Resource_Order_Collection $collection */
						$collection = Mage::getResourceModel('sales/order_collection');
						$collection->addFieldToSelect('*')
						           ->addFieldToFilter('customer_id', $customer->getId())
						           ->addAttributeToSort('created_at', Varien_Db_Select::SQL_DESC)
						           ->setPageSize(1);

						if ($collection->getSize() > 0) {
							/** @var Mage_Sales_Model_Order $order */
							$order = $collection->getFirstItem();

							/** @var Mage_Sales_Model_Resource_Recurring_Profile_Collection $profileCollection */
							$profileCollection = Mage::getResourceModel('sales/recurring_profile_collection');
							$profileCollection->addFieldToFilter('customer_id', $customer->getEntityId());

							if ($profileCollection->getSize() == 0 && $order->getCreatedAt() < $dateCompare) {
								$this->sendInactivityEmail($customer, $subscriber, Mage::getStoreConfig(self::XML_PATH_ORDER_INACTIVITY_VIP_EMAIL_TEMPLATE, $customer->getStoreId()), $step);
							} else {
								$profileCollection->addFieldToFilter('state', 'canceled');

								if ($profileCollection->getSize() > 0) {
									/** @var Mage_Sales_Model_Recurring_Profile $profile */
									$profile = $profileCollection->getFirstItem();

									/** @var Mage_Sales_Model_Resource_Recurring_Profile_Collection $activeProfileCollection */
									$activeProfileCollection = Mage::getResourceModel('sales/recurring_profile_collection');
									$activeProfileCollection->addFieldToFilter('customer_id', $customer->getEntityId())
									                        ->addFieldToFilter('state', array('active', 'suspended'));

									if ($profile->getUpdatedAt() < $dateCompare && $activeProfileCollection->getSize() === 0) {
										$this->sendInactivityEmail($customer, $subscriber, Mage::getStoreConfig(self::XML_PATH_ORDER_INACTIVITY_FOLLOWUP_EMAIL_TEMPLATE, $customer->getStoreId()), $step);
									}
								}
							}
						} else if ($customer->getCreatedAt() < $dateCompare) {
							$this->sendInactivityEmail($customer, $subscriber, Mage::getStoreConfig(self::XML_PATH_ORDER_INACTIVITY_EMAIL_TEMPLATE, $customer->getStoreId()), $step);
						}
					} catch (Exception $e) {
						Mage::logException($e);
						continue;
					}

					$this->sendCouponExpirationEmails($customer, $subscriber);
				}
			}
		}
	}

	/**
	 * Send the customer an email based on the $template passed
	 *
	 * @param Mage_Customer_Model_Customer     $customer
	 * @param Mage_Newsletter_Model_Subscriber $subscriber
	 * @param string                           $template
	 * @param int                              $step
	 */
	public function sendInactivityEmail($customer, $subscriber, $template, $step) {
		/** @var Mage_Core_Model_Email_Template $email */
		$email = Mage::getModel('core/email_template');
		$email->setDesignConfig(array('area' => 'frontend', 'store' => $customer->getStoreId()));

		/** @var Mage_SalesRule_Model_Coupon $coupon */
		$coupon = $this->generateCoupon();

		$email->sendTransactional(
			$template,
			Mage::getStoreConfig(Magestore_Affiliateplus_Model_Transaction::XML_PATH_EMAIL_IDENTITY, $customer->getStoreId()),
			$customer->getEmail(),
			null,
			array(
				'customer'         => $customer,
				'coupon_code'      => $coupon->getCode(),
				'unsubscribe_link' => $subscriber->getUnsubscriptionLink(),
			),
			$customer->getStoreId()
		);

		$customer->setOrderInactivityStep(++$step)
		         ->setAssignedCouponCode($coupon->getCode());
		$customer->save();
	}

	public function generateCoupon() {
		/** @var Mage_SalesRule_Model_Coupon_Massgenerator $couponGenerator */
		$couponGenerator = Mage::getSingleton('salesrule/coupon_massgenerator');

		$params = array(
			'qty'               => 1,
			'length'            => 6,
			'uses_per_customer' => 1,
			'uses_per_coupon'   => 1,
			'to_date'           => date('Y-m-d', strtotime('+14 days')),
			'format'            => Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC,
			'rule_id'           => Mage::getStoreConfig(self::XML_PATH_ORDER_INACTIVITY_SALESRULE_ID),
		);

		if ($couponGenerator->validateData($params)) {
			$couponGenerator->setData($params);
			$couponGenerator->generatePool();
		}

		/** @var Mage_SalesRule_Model_Resource_Coupon_Collection $coupons */
		$coupons = Mage::getResourceModel('salesrule/coupon_collection');
		$coupons->addRuleToFilter($params['rule_id'])
		        ->addGeneratedCouponsFilter();

		return $coupons->getLastItem();
	}

	/**
	 * @param Mage_Customer_Model_Customer     $customer
	 * @param Mage_Newsletter_Model_Subscriber $subscriber
	 */
	public function sendCouponExpirationEmails($customer, $subscriber) {
		if ($couponCode = $customer->getAssignedCouponCode()) {
			/** @var Mage_SalesRule_Model_Coupon $coupon */
			$coupon = Mage::getModel('salesrule/coupon')->loadByCode($couponCode);
			$locale = Mage::app()->getLocale();

			if ($coupon->getId() && $expirationDate = $locale->date($coupon->getExpirationDate())->addDay(1)->setTime(0)) {
				$template = null;

				if ($expirationDate == $locale->date()->addDay(1)->setTime(0)) {
					$template = self::XML_PATH_ORDER_INACTIVITY_COUPON_ONE_DAY_TEMPLATE;
				} elseif ($expirationDate == $locale->date()->addDay(7)->setTime(0)) {
					$template = self::XML_PATH_ORDER_INACTIVITY_COUPON_ONE_WEEK_TEMPLATE;
				}

				if ($template) {
					/** @var Mage_Core_Model_Email_Template $email */
					$email = Mage::getModel('core/email_template');
					$email->setDesignConfig(array('area' => 'frontend', 'store' => $customer->getStoreId()));
					$email->sendTransactional(
						Mage::getStoreConfig($template),
						Mage::getStoreConfig(Magestore_Affiliateplus_Model_Transaction::XML_PATH_EMAIL_IDENTITY, $customer->getStoreId()),
						$customer->getEmail(),
						null,
						array(
							'customer'         => $customer,
							'coupon_code'      => $couponCode,
							'unsubscribe_link' => $subscriber->getUnsubscriptionLink(),
						),
						$customer->getStoreId()
					);
				}
			}
		}
	}
}
