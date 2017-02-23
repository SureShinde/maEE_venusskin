<?php
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'CustomerController.php';

class Venus_Theme_Rewrite_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController {
	protected function _initCustomer($idFieldName = 'id') {
		$this->_title($this->__('Patients'))->_title($this->__('Manage Patients'));

		$customerId = (int)$this->getRequest()->getParam($idFieldName);
		$customer   = Mage::getModel('customer/customer');

		if ($customerId) {
			$customer->load($customerId);
		}

		Mage::register('current_customer', $customer);

		return $this;
	}

	/**
	 * Patients list action
	 */
	public function indexAction() {
		$this->_title($this->__('Patients'))->_title($this->__('Manage Patients'));

		if ($this->getRequest()->getQuery('ajax')) {
			$this->_forward('grid');

			return;
		}
		$this->loadLayout();

		/**
		 * Set active menu item
		 */
		$this->_setActiveMenu('customer/manage');

		/**
		 * Append customers block to content
		 */
		$this->_addContent(
			$this->getLayout()->createBlock('adminhtml/customer', 'customer')
		);

		/**
		 * Add breadcrumb item
		 */
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Patients'), Mage::helper('adminhtml')->__('Patients'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Patients'), Mage::helper('adminhtml')->__('Manage Patients'));

		$this->renderLayout();
	}
}
