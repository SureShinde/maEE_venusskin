<?php

/**
 * Class Snowdog_Multilevel_Block_Payment_Catalog_Product_View_Profile
 */
class Snowdog_Multilevel_Block_Payment_Catalog_Product_View_Profile extends Mage_Payment_Block_Catalog_Product_View_Profile
{
    protected $_helper;

    protected $_idPeriod = 'recurring_period';

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    public function getPeriodHtml()
    {
        $frequencies = $this->_getHelper()->getValidPeriodFrequencies($this->_profile);

        $product = $this->getProduct();
        $onetimeId = $product->getOnetimePurchaseVariantId();
        if ($onetimeId) {
            $oneTimeArray = $this->_addOneTimePurchase($onetimeId);
            array_splice($frequencies, 1, 0, $oneTimeArray);
        }

        if (count($frequencies) > 2) {
            $this->setPeriodHtmlId($this->_idPeriod);

            $select = $this->getLayout()
                ->createBlock('core/html_select')
                ->setId($this->_idPeriod)
                ->setName(Snowdog_Multilevel_Model_Rewrite_Payment_Recurring_Profile::BUY_REQUEST_PERIOD)
                ->setOptions($frequencies)
                ->setClass('required-entry');

            if ($this->getProduct() && $this->getProduct()->getPreconfiguredValues() && ($preconfiguredOptions = $this->getProduct()->getPreconfiguredValues()->getOptions()) && !empty($preconfiguredOptions[Venus_Multilevel_Model_Rewrite_Payment_Recurring_Profile::BUY_REQUEST_PERIOD])) {
                $select->setValue($preconfiguredOptions[Snowdog_Multilevel_Model_Rewrite_Payment_Recurring_Profile::BUY_REQUEST_PERIOD]);
            }

            return $select->getHtml();
        }

        return '';
    }

    /**
     * @return Snowdog_Multilevel_Helper_Profile
     */
    protected function _getHelper()
    {
        if (null === $this->_helper) {
            $this->_helper = $this->helper('multilevel/profile');
        }

        return $this->_helper;
    }

    /**
     * Add one time purchase item to frequencies array
     *
     * @param $onetimeIdmeId
     * @param $frequencies
     */
    protected function _addOneTimePurchase($onetimeIdmeId)
    {
        $otProduct = Mage::getModel('catalog/product');
        $otProduct->load($onetimeIdmeId);
        $oneTimeArray = array(
            array(
                'label' => $this->__('A la carte - one time purchase'),
                'value' => Mage::helper('checkout/cart')->getAddUrl($otProduct)
            )
        );

        return $oneTimeArray;
    }
}
