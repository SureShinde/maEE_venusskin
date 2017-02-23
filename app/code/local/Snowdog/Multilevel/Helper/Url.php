<?php

/**
 * Class Snowdog_Multilevel_Helper_Url
 */
class Snowdog_Multilevel_Helper_Url extends Magestore_Affiliateplus_Helper_Url
{
    /**
     * Rewrite to show allways store code in url
     *
     * @param $banner
     * @param null $store
     * @return string
     */
    public function getBannerUrl($banner, $store = null)
    {
        if (is_null($store))
            $store = Mage::app()->getStore();
        $account = Mage::getSingleton('affiliateplus/session')->getAccount();

        $url = Mage::getUrl('customer/account/create');

        //hainh 29-07-2014 
        $referParam = $this->getPersonalUrlParameter();
        $referParamValue = $account->getIdentifyCode();
        if (Mage::getStoreConfig('affiliateplus/general/url_param_value') == 2)
            $referParamValue = $account->getAccountId();

        if (strpos($url, '?'))
            $url .= '&' . $referParam . '=' . $referParamValue;
        else
            $url .= '?' . $referParam . '=' . $referParamValue;
        //end editing

        // Changed By Adam: 10/11/2014: Fix loi khi chay multiple website nhung ko co default store view
        if (Mage::app()->getDefaultStoreView())
            $url .= '&___store=' . $store->getCode();

        $websitecode = Mage::app()->getWebsite()->getCode();
        if($websitecode)
            $url .= '&___website=' . $websitecode;

        /** Thanhpv - add bannerid (2012-10-09) */
        if ($banner->getId())
            $url .= '&bannerid=' . $banner->getId();

        $urlParams = new Varien_Object(array(
            'helper' => $this,
            'params' => array(),
        ));
        Mage::dispatchEvent('affiliateplus_helper_get_banner_url', array(
            'banner' => $banner,
            'url_params' => $urlParams,
        ));

        $params = $urlParams->getParams();
        if (count($params))
            $url .= '&' . http_build_query($urlParams->getParams(), '', '&');

        return $url;
    }
}
