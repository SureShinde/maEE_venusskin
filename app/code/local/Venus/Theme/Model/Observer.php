<?php
class Venus_Theme_Model_Observer {
	const XML_PATH_NOTIFY_EMAIL_TEMPLATE        = 'venus_theme/notify_stock_qty_email/template';
	const XML_PATH_NOTIFY_EMAIL_SENDER_IDENTITY = 'venus_theme/notify_stock_qty_email/identity';
	const XML_PATH_NOTIFY_EMAIL_RECIPIENT       = 'cataloginventory/item_options/notification_recipient';

	/**
	 * Redirects the contacts page to CMS thankyou page upon succeful submission of contact form
	 *
	 * @param $observer
	 *
	 * @return $this
	 */
	public function redirect($observer) {
		$session = Mage::getSingleton('customer/session');
		if ($errors = $session->getMessages(false)->getErrors()) {
			return;
		}

		$session->getMessages(true);

		Mage::app()->getResponse()->setRedirect(Mage::helper('cms/page')->getPageUrl('contact-thank-you'));

		return $this;
	}

	public function addMessages($observer) {
		// Stop if this is a CMS page, since this has already been executed by the CMS helper.
		if (Mage::app()->getRequest()->getModuleName() === 'cms') {
			return $this;
		}

		$messageBlock = $observer->getEvent()->getLayout()->getMessagesBlock();
		foreach (array('catalog/session', 'checkout/session', 'customer/session') as $storageType) {
			$storage = Mage::getSingleton($storageType);
			if ($storage) {
				$messageBlock->addStorageType($storageType);
				$messageBlock->addMessages($storage->getMessages(true));
			}
		}

		return $this;
	}

	public function customQuoteProcess(Varien_Event_Observer $observer) {
		/**
		 * @var Mage_Checkout_Model_Session $session
		 */

		$session = $observer->getEvent()->getCheckoutSession();

		$quoteId = $session->getQuoteId();

		if (empty($quoteId)) {
			foreach (Mage::app()->getWebsites() as $id => $website) {
				$quoteIdKey = 'quote_id_' . $id;

				if ($session->getData($quoteIdKey)) {
					$quoteId = $session->getData($quoteIdKey);

					break;
				}
			}
		}

		if (!empty($quoteId)) {
			/**
			 * @var Mage_Sales_Model_Quote $quote
			 */

			$quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($quoteId);

			if ($quote->getId() && $quote->getIsActive() && $quote->getStoreId() != Mage::app()->getStore()->getId()) {
				$quote->setStoreId(Mage::app()->getStore()->getId());

				foreach ($quote->getItemsCollection() as $item) {
					$item->setStoreId(Mage::app()->getStore()->getId());
				}

				$quote->save();

				$session->setQuoteId($quoteId);
			}
		}

		return $this;
	}

	public function sendLowStockEmail(Varien_Event_Observer $observer) {
		$stockItem = $observer->getItem();

		if (!($stockItem->getProduct() instanceof Mage_Catalog_Model_Product)) {
			return $this;
		}

		$stockData = $stockItem->getProduct()->getStockData();

		if ($stockItem->verifyNotification() && $stockData['original_inventory_qty'] != $stockData['qty']) {
			$store        = Mage::app()->getStore();
			$mail         = Mage::getModel('core/email_template');
			$templateVars = array(
				'stock_item' => $stockItem,
				'stock_qty'  => (int)$stockItem->getQty(),
				'product'    => Mage::getModel('catalog/product')->load($stockItem->getProductId())
			);

			$mail->setDesignConfig(array('area' => 'frontend', 'store' => $store->getId()));
			$mail->sendTransactional(
				$store->getConfig(self::XML_PATH_NOTIFY_EMAIL_TEMPLATE),
				$store->getConfig(self::XML_PATH_NOTIFY_EMAIL_SENDER_IDENTITY),
				$store->getConfig(self::XML_PATH_NOTIFY_EMAIL_RECIPIENT),
				null,
				$templateVars
			);
		}

		return $this;
	}

	public function sendNewAdminEmail(Varien_Event_Observer $observer) {
		/**
		 * @var Mage_Admin_Model_User $adminUser
		 */

		$adminUser = $observer->getEvent()->getObject();

		if ($adminUser->isObjectNew()) {
			Mage::helper('theme/admin')->sendNewAdminEmail($adminUser);
		}

		return $this;
	}

	public function checkCouponUsage(Varien_Event_Observer $observer) {
		/** @var Mage_Sales_Model_Order $order */
		$order = $observer->getEvent()->getOrder();

		if ($couponCode = $order->getCouponCode()) {
			/** @var Mage_SalesRule_Model_Coupon $coupon */
			$coupon = Mage::getModel('salesrule/coupon')->loadByCode($couponCode);

			if ($coupon->getId() && $coupon->getRuleId() === Mage::getStoreConfig(Venus_Theme_Model_Cron::XML_PATH_ORDER_INACTIVITY_SALESRULE_ID)) {
				/** @var Mage_Customer_Model_Customer $customer */
				$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
				$customer->setHasRedeemedCoupon(1);
				$customer->getResource()->saveAttribute($customer, 'has_redeemed_coupon');
			}
		}
	}
}
