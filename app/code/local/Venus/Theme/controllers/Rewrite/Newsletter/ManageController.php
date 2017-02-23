<?php
require_once Mage::getModuleDir('controllers', 'Mage_Newsletter') . DS . 'ManageController.php';

class Venus_Theme_Rewrite_Newsletter_ManageController extends Mage_Newsletter_ManageController {
	public function indexAction() {
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');

		if ($block = $this->getLayout()->getBlock('customer_newsletter')) {
			$block->setRefererUrl($this->_getRefererUrl());
		}

		$this->getLayout()->getBlock('head')->setTitle($this->__('Communication Preferences'));
		$this->renderLayout();
	}
}
