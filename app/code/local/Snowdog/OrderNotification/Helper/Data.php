<?php

class Snowdog_OrderNotification_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getStoreEmail()
    {
        return Mage::getStoreConfig('trans_email/ident_general/email');
    }

    public function getSenderName()
    {
        return Mage::getStoreConfig('trans_email/ident_general/name');
    }

    public function getEmails($storeId)
    {
        if (!$storeId) {
            $storeId = Mage::app()->getStore()->getStoreId();
        }
        $emails = Mage::getStoreConfig('order_notification/general/email', $storeId);
        $emailArr = explode(',', $emails);
        
        return $emailArr;
    }
}