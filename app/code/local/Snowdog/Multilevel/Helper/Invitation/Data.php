<?php

/**
 * Class Snowdog_Multilevel_Helper_Invitation_Data
 */
class Snowdog_Multilevel_Helper_Invitation_Data extends Enterprise_Invitation_Helper_Data
{
    /**
     * Return invitation url
     *
     * @param Enterprise_Invitation_Model_Invitation $invitation
     * @return string
     */
    public function getInvitationUrl($invitation)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            $customer = $invitation->getCustomer();
            $refererId = $customer->getRefererCustomerId();
            $email = $invitation->getEmail();
            if ($refererId) {
                $affiliateCode = $this->_getAffiliateCode($refererId);
            } else {
                $affiliateCode = null;
            }
        } else {
            $affiliateCode = null;
        }

        $storeCode = Mage::app()->getStore()->getCode();
        $websitecode = Mage::app()->getWebsite()->getCode();
        return Mage::getModel('core/url')->setStore($invitation->getStoreId())
            ->getUrl('customer/account/create', array(
                'invitation' => Mage::helper('core')->urlEncode($invitation->getInvitationCode()),
                '_store_to_url' => true,
                '_nosid' => true,
                'acc' => $affiliateCode,
                'email' => $email
            )) . "?_store=$storeCode&___website=$websitecode";
    }

    /**
     * Get affiliate code
     *
     * @param $customerId
     */
    protected function _getAffiliateCode($customerId)
    {
        $doctor = Mage::getModel('affiliateplus/account')->load($customerId, 'customer_id');
        return $doctor->getIdentifyCode();
    }
}