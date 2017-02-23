<?php
class Venus_Theme_Helper_Admin extends Mage_Core_Helper_Abstract {
	const XML_PATH_NEW_EMAIL_TEMPLATE = 'admin/emails/account_email_template';
	const XML_PATH_NEW_EMAIL_IDENTITY = 'admin/emails/account_email_identity';

	public function sendNewAdminEmail(Mage_Admin_Model_User $user) {
		try {
			$mailer    = Mage::getModel('core/email_template_mailer');
			$emailInfo = Mage::getModel('core/email_info');
			$emailInfo->addTo($user->getEmail(), $user->getName());
			$mailer->addEmailInfo($emailInfo);

			$mailer->setSender(Mage::getStoreConfig(self::XML_PATH_NEW_EMAIL_IDENTITY));
			$mailer->setStoreId(0);
			$mailer->setTemplateId(Mage::getStoreConfig(self::XML_PATH_NEW_EMAIL_TEMPLATE));
			$mailer->setTemplateParams(
				array(
					'user' => $user
				)
			);
			$mailer->send();
		} catch (Exception $e) {
			Mage::logException($e);
		}

		return $this;
	}
}
