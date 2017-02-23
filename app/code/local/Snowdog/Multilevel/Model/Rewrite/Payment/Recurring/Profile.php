<?php

/**
 * Class Snowdog_Multilevel_Model_Rewrite_Payment_Recurring_Profile
 */
class Snowdog_Multilevel_Model_Rewrite_Payment_Recurring_Profile extends Mage_Payment_Model_Recurring_Profile
{
    const BUY_REQUEST_PERIOD = 'recurring_profile_period';

    protected $_buyRequestImported = false;

    protected $_productImported = false;

    public function importBuyRequest(Varien_Object $buyRequest)
    {
        $this->_buyRequestImported = true;

        if ($periodFrequency = $buyRequest->getData(self::BUY_REQUEST_PERIOD)) {
            $this->setPeriodFrequency($periodFrequency);
        }

        return parent::importBuyRequest($buyRequest);
    }

    public function importProduct(Mage_Catalog_Model_Product $product)
    {
        if ($product->isRecurring() && is_array($product->getRecurringProfile())) {
            $this->_productImported = true;

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

            return $this->_filterValues();
        }

        return false;
    }

    protected function _filterValues()
    {
        $result = parent::_filterValues();

        if ($this->_buyRequestImported && $this->_productImported) {
            $periodFrequency = $this->getPeriodFrequency();
            $validPeriodFrequencies = Mage::helper('multilevel/profile')->getValidPeriodFrequencies($this, false, true);

            if (!isset($validPeriodFrequencies[$periodFrequency])) {
                Mage::throwException(Mage::helper('multilevel')->__('Please Select a Pricing Option.'));
            }
        }

        return $result;
    }
}
