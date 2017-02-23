<?php
class Venus_Theme_Block_Adminhtml_Customer_Edit extends Mage_Adminhtml_Block_Customer_Edit {
	public function __construct() {
		parent::__construct();

		if (!Mage::getSingleton('admin/session')->isAllowed('customer/manage_delete')) {
			$this->_removeButton('delete');
		}
	}
}
