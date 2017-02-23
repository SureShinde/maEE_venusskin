<?php
class Venus_Theme_Model_Enterprise_Invitation_Invitation extends Enterprise_Invitation_Model_Invitation {
	public function sendInvitationEmail() {
		$this->makeSureCanBeSent();

		$store   = Mage::app()->getStore($this->getStoreId());
		$inviter = $this->getInviter() ? : Mage::getSingleton('admin/session')->getUser();

		/** @var Mage_Core_Model_Email_Template $mail */
		$mail = Mage::getModel('core/email_template');
		$mail->setDesignConfig(array('area' => 'frontend', 'store' => $this->getStoreId()))
		     ->sendTransactional(
			     $store->getConfig(self::XML_PATH_EMAIL_TEMPLATE),
			     $store->getConfig(self::XML_PATH_EMAIL_IDENTITY),
			     $this->getEmail(),
			     null,
			     array(
				     'url'           => Mage::helper('enterprise_invitation')->getInvitationUrl($this),
				     'allow_message' => Mage::app()->getStore()->isAdmin() || Mage::getSingleton('enterprise_invitation/config')->isInvitationMessageAllowed(),
				     'message'       => $this->getMessage(),
				     'store'         => $store,
				     'store_name'    => $store->getGroup()->getName(),
				     'inviter_name'  => $inviter->getName(),
			     ),
			     $this->getStoreId()
		     );

		if ($mail->getSentSuccess()) {
			$this->setStatus(self::STATUS_SENT)->setUpdateDate(true)->save();

			return true;
		}

		return false;
	}
}
