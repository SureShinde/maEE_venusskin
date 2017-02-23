<?php

/**
 * Class Snowdog_Multilevel_Model_Sales_Order
 */
class Snowdog_Multilevel_Model_Sales_Order extends Mage_Sales_Model_Order
{
    public function sendNewOrderEmail()
    {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
            return $this;
        }

        $emailSentAttributeValue = $this->load($this->getId())->getData('email_sent');
        $this->setEmailSent((bool)$emailSentAttributeValue);
        if ($this->getEmailSent()) {
            return $this;
        }

        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);

        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        try {
            $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())->setIsSecureMode(true);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
        } catch (Exception $exception) {
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
        }

        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }

        /**
         * @var Venus_Multilevel_Model_Email_Template_Mailer $mailer
         */

        $mailer = Mage::getModel('multilevel/email_template_mailer');
        $emailInfo = Mage::getModel('multilevel/email_info');
        $emailInfo->addTo($this->getCustomerEmail(), $customerName);
        if ($copyTo && $copyMethod == 'bcc') {
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('multilevel/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(
            array(
                'order' => $this,
                'billing' => $this->getBillingAddress(),
                'payment_html' => $paymentBlockHtml
            )
        );

        $mailer->send();

        $this->setEmailSent(true);
        $this->_getResource()->saveAttribute($this, 'email_sent');

        // reset the order inactivity step
        $customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
        $customer->setOrderInactivityStep(1);
        $customer->getResource()->saveAttribute($customer, 'order_inactivity_step');

        return $this;
    }
}
