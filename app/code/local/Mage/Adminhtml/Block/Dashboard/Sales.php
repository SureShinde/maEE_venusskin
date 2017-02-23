<?php
class Mage_Adminhtml_Block_Dashboard_Sales extends Mage_Adminhtml_Block_Dashboard_Bar {
	protected function _construct() {
		parent::_construct();
		$this->setTemplate('dashboard/salebar.phtml');
	}

	protected function _prepareLayout() {
		return $this;
	}
}
