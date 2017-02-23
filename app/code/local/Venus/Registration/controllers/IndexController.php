<?php
class Venus_Registration_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout();
		$this->renderLayout();
	}

	protected function _getSession() {
		return Mage::getSingleton('customer/session');
	}
}
