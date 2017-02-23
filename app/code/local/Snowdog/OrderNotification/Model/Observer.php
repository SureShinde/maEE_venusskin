<?php

class Snowdog_OrderNotification_Model_Observer
{

    public function sendEmail(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!is_null($order->getData('original_increment_id'))) {
            $helper = Mage::helper('ordernotification');
            try {
                $emails = $helper->getEmails($order->getStoreId());
                $html = 'Order no:' . $order->getIncrementId() . ' has been edited';
                $mail = Mage::getModel('core/email');
                $mail->setToName('*');
                $mail->setBody($html);
                $mail->setSubject('Edit Order no' . $order->getIncrementId());
                $mail->setFromEmail($helper->getStoreEmail());
                $mail->setFromName($helper->getSenderName());
                $mail->setType('html');// YOu can use Html or text as Mail format
                foreach ($emails as $email) {
                    $mail->setToEmail($email);
                    $mail->send();
                }
            } catch (Exception $e) {
                Mage::log($e->getMessage(), null, 'OrderNotificationError.log', true);
            }

        }
    }

}
