<?php

class Snowdog_FacebookRefLink_Model_Observer
{

    public function updateRefererUrl(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $refCode = Mage::getSingleton('multilevel/customer')->getAffiliateAccount($customer)->getIdentifyCode();
        $this->_sendUpdateToFacebook($refCode);
    }

    protected function _sendUpdateToFacebook($refCode)
    {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        if ($isSecure) {
            $url = Mage::getUrl("customer/account/create/", array('_secure' => true)) . "?referer=$refCode";
        } else {
            $url = Mage::getUrl("customer/account/create/") . "?referer=$refCode";
        }

        $furl = 'https://graph.facebook.com';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $furl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $params = array(
            'id' => $url,
            'scrape' => true);
        $data = http_build_query($params);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_exec($ch);
        curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
}
