<?php

/**
 * Class Snowdog_Multilevel_Model_Sales_Recurring_Profile
 */
class Snowdog_Multilevel_Model_Sales_Recurring_Profile extends Mage_Sales_Model_Recurring_Profile
{
    public function getPaymentProfile()
    {
        if (!$this->hasPaymentProfile()) {
            $product = Mage::getModel('catalog/product')->load($this->getOrderItemInfo('product_id'));

            $paymentProfile = Mage::getModel('payment/recurring_profile')->importProduct($product);

            if ($this->getStartDatetime()) {
                $paymentProfile->setStartDatetime($this->getStartDatetime());
            }

            $this->setPaymentProfile($paymentProfile);
        }

        return parent::getPaymentProfile();
    }

    public function createOrder()
    {
        /**
         * @var Mage_Sales_Model_Order $order
         */

        $order = call_user_func_array('parent::createOrder', func_get_args());

        $order->setMultilevelOwnerId($this->getOrderInfo('multilevel_owner_id'));

        if ($this->getOrderInfo('use_customer_balance')) {
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());

            if ($customer->getId()) {
                $store = $order->getStore();

                $baseBalance = Mage::getModel('enterprise_customerbalance/balance')
                    ->setCustomer($customer)
                    ->setCustomerId($customer->getId())
                    ->setWebsiteId($store->getWebsiteId())
                    ->loadByCustomer()
                    ->getAmount();

                if ($baseBalance > $order->getBaseGrandTotal()) {
                    $baseBalance = $order->getBaseGrandTotal();
                }

                $balance = $store->convertPrice($baseBalance);

                $order->setBaseCustomerBalanceAmount($baseBalance);
                $order->setCustomerBalanceAmount($balance);

                $order->setBaseGrandTotal($order->getBaseGrandTotal() - $baseBalance);
                $order->setGrandTotal($order->getGrandTotal() - $balance);
            }
        }

        return $order;
    }

    public function importProduct(Mage_Catalog_Model_Product $product)
    {
        if ($product->isRecurring() && is_array($product->getRecurringProfile())) {
            $periodFrequency = $this->getPeriodFrequency();

            $this->addData($product->getRecurringProfile());

            $this->setValidPeriodFrequencies($this->getPeriodFrequency())->unsPeriodFrequency();

            if (!$this->hasScheduleDescription()) {
                $this->setScheduleDescription($product->getName());
            }

            $validPeriodFrequencies = Mage::helper('multilevel/profile')->getValidPeriodFrequencies($this, false, true);

            if (count($validPeriodFrequencies) == 1) {
                $this->setPeriodFrequency(key($validPeriodFrequencies));
            } else if (isset($validPeriodFrequencies[$periodFrequency])) {
                $this->setPeriodFrequency($periodFrequency);
            }

            $options = $product->getCustomOption(self::PRODUCT_OPTIONS_KEY);

            if ($options) {
                $options = unserialize($options->getValue());

                if (is_array($options)) {
                    if (isset($options['period_frequency'])) {
                        $this->setPeriodFrequency($options['period_frequency']);
                    }

                    if (isset($options['start_datetime'])) {
                        $startDatetime = new Zend_Date($options['start_datetime'], Varien_Date::DATETIME_INTERNAL_FORMAT);
                        $this->setNearestStartDatetime($startDatetime);
                    }
                }
            }

            return $this->_filterValues();
        }

        return false;
    }

    public function isValid()
    {
        parent::isValid();

        if ($this->hasValidPeriodFrequencies()) {
            $validPeriodFrequencies = Mage::helper('multilevel/profile')->getValidPeriodFrequencies($this, false, true);

            if (!isset($validPeriodFrequencies[$this->getPeriodFrequency()])) {
                $this->_errors['period_frequency'][] = Mage::helper('payment')->__('Period frequency is wrong.');
            }
        }

        return empty($this->_errors);
    }

    public function setNearestStartDatetime(Zend_Date $minAllowed = null)
    {
        if ($this->getId() && $this->getStartDatetime()) {
            return $this;
        }

        return parent::setNearestStartDatetime($minAllowed);
    }

    public function updateNextCycle()
    {
        $additionalInfo = $this->getAdditionalInfo();

        if ($this->getPeriodUnit() == self::PERIOD_UNIT_SEMI_MONTH) {
            $additionalInfo['next_cycle'] = strtotime('+' . ($this->getPeriodFrequency() * 2) . ' weeks', $additionalInfo['last_bill']);
        } else {
            $additionalInfo['next_cycle'] = strtotime('+' . $this->getPeriodFrequency() . ' ' . $this->getPeriodUnit(), $additionalInfo['last_bill']);
        }

        $this->setAdditionalInfo($additionalInfo);

        return $this;
    }

    public function renderData($key)
    {
        $value = $this->_getData($key);
        switch ($key) {
            case 'period_unit':
                return $this->getPeriodUnitLabel($value);
            case 'method_code':
                if (!$this->_paymentMethods) {
                    $this->_paymentMethods = Mage::helper('payment')->getPaymentMethodList(false);
                }
                if (isset($this->_paymentMethods[$value])) {
                    return $this->_paymentMethods[$value];
                }
                break;
            case 'start_datetime':
                return $this->exportStartDatetime(true);
            case 'updated_at':
                return $this->_locale->storeDate($this->_store, strtotime($this->getUpdatedAt()), true);
        }

        return $value;
    }

    public function getFieldLabel($field)
    {
        switch ($field) {
            case 'order_item_id':
                return Mage::helper('sales')->__('Purchased Item');
            case 'state':
                return Mage::helper('sales')->__('Profile State');
            case 'created_at':
                return Mage::helper('adminhtml')->__('Created At');
            case 'updated_at':
                return Mage::helper('adminhtml')->__('Date Last Modified');
            default:
                return parent::getFieldLabel($field);
        }
    }
}
