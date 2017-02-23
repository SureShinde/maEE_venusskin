<?php

class Snowdog_CancelOrderEmail_Model_Observer
{

    const XML_PATH_SNOW_CANCEL_ORDER_EMAIL = 'snowcancelorderemail/general/email';

    public function sendCancelEmail(Varien_Event_Observer $observer)
    {
        $emails = Mage::getStoreConfig(self::XML_PATH_SNOW_CANCEL_ORDER_EMAIL);
        $email = explode(',', $emails);
        $order = $observer->getOrder();
        $templateId = "cancel_order_email";
        $emailTemplate = Mage::getModel('core/email_template')->loadByCode($templateId);
        $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
        $vars = array("order_id" => $order->getIncrementId());
        $processedTemplate = $emailTemplate->getProcessedTemplate($vars);

        $mail = Mage::getModel('core/email')
            ->setToName($senderName)
            ->setToEmail($email)
            ->setBody($processedTemplate)
            ->setSubject('Cancel order: ' . $order->getIncrementId())
            ->setFromEmail($senderEmail)
            ->setFromName($senderName)
            ->setType('html');

        try {
            //Confimation E-Mail Send
            $mail->send();
        } catch (Exception $error) {
            Mage::getSingleton('core/session')->addError($error->getMessage());
            return false;
        }
    }

}
