<?php

/**
 * Class Snowdog_Referefiframe_Helper_Data
 */
class Snowdog_Referefiframe_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get iframe html code
     *
     * @return null|string
     */
    public function getIframeUrl()
    {
        $customerId = Mage::getSingleton('customer/session')->getId();
        if ($customerId) {
            $account = Mage::getModel('affiliateplus/account')->load($customerId, 'customer_id');
            $iframe = '<div class="venusskin-container" style="position: relative; overflow: hidden; height: 0; padding: 0 0 1500px 0;">';
            $iframe .= '<iframe height="300" width="200" src="' . $this->_getUrl('snowreferefiframe/index/index/', array('acc' => $account->getIdentifyCode())) . '" style="position: absolute; top:0; left: 0; width: 100%; height: 100%;"></iframe>';
            $iframe .= '</div>';

            return htmlentities($iframe);
        }
        return null;
    }

    /**
     * Get acc data from session
     *
     * @return mixed
     */
    public function getAcc()
    {
        return Mage::getSingleton('customer/session')->getAcc();
    }
}
