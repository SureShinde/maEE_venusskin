<?php
require_once Mage::getModuleDir('controllers', 'Enterprise_Invitation') . DS . 'Adminhtml' . DS . 'InvitationController.php';

class Venus_Theme_Rewrite_Invitation_Adminhtml_InvitationController extends Enterprise_Invitation_Adminhtml_InvitationController {
	/**
	 * Invitation list
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->_title($this->__('Patients'))->_title($this->__('Invite New Patients'));

		$this->loadLayout()->_setActiveMenu('customer/invitation');
		$this->renderLayout();
	}

	/**
	 * Init invitation model by request
	 *
	 * @return Enterprise_Invitation_Model_Invitation
	 */
	protected function _initInvitation() {
		$this->_title($this->__('Patients'))->_title($this->__('Invite New Patients'));

		$invitation = Mage::getModel('enterprise_invitation/invitation')->load($this->getRequest()->getParam('id'));
		if (!$invitation->getId()) {
			Mage::throwException(Mage::helper('enterprise_invitation')->__('Invitation not found.'));
		}
		Mage::register('current_invitation', $invitation);

		return $invitation;
	}
}
