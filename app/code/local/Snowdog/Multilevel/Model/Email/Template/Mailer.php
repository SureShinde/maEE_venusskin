<?php

/**
 * Class Snowdog_Multilevel_Model_Email_Template_Mailer
 */
class Snowdog_Multilevel_Model_Email_Template_Mailer extends Mage_Core_Model_Email_Template_Mailer
{

    public function send()
    {
        while (!empty($this->_emailInfos)) {
            $emailInfo = array_pop($this->_emailInfos);

            /**
             * @var Mage_Core_Model_Email_Template $emailTemplate
             */

            $emailTemplate = Mage::getModel('core/email_template');

            $attachments = $emailInfo->getAttachments();

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $emailTemplate->getMail()
                        ->createAttachment(
                            $attachment['content'],
                            Zend_Mime::TYPE_OCTETSTREAM,
                            Zend_Mime::DISPOSITION_ATTACHMENT,
                            Zend_Mime::ENCODING_BASE64,
                            $attachment['filename']
                        );
                }
            }

            $emailTemplate->addBcc($emailInfo->getBccEmails());

            $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $this->getStoreId()))
                ->sendTransactional(
                    $this->getTemplateId(),
                    $this->getSender(),
                    $emailInfo->getToEmails(),
                    $emailInfo->getToNames(),
                    $this->getTemplateParams(),
                    $this->getStoreId()
                );
        }

        return $this;
    }
}
