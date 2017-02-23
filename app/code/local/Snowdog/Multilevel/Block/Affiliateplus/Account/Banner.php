<?php

/**
 * Class Snowdog_Multilevel_Block_Affiliateplus_Account_Banner
 */
class Snowdog_Multilevel_Block_Affiliateplus_Account_Banner extends Magestore_Affiliateplus_Block_Account_Banner
{
    public function getAffiliateUrl()
    {
        $storeCode = Mage::app()->getStore()->getCode();
        $websitecode = Mage::app()->getWebsite()->getCode();
        return Mage::helper('affiliateplus/url')->addAccToUrl($this->geturl('customer/account/create'))."&_store=$storeCode&___website=$websitecode";
    }
}