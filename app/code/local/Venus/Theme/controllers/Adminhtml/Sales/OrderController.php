<?php
class Venus_Theme_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
		     ->_setActiveMenu('sales/order')
		     ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
		     ->_addBreadcrumb($this->__('Orders'), $this->__('Orders'));

		return $this;
	}

	public function processingAction() {
		$this->_title($this->__('Sales'))->_title($this->__('Orders'))->_title($this->__('Pending Orders'));

		$this->_initAction()
		     ->renderLayout();
	}

	public function processingGridAction() {
		$this->loadLayout(false);
		$this->renderLayout();
	}

	protected function _isAllowed() {
		return Mage::getSingleton('admin/session')->isAllowed('sales/order');
	}
}
