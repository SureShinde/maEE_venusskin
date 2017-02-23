<?php
class Mage_Adminhtml_Block_Dashboard_Monthly_Date extends Mage_Adminhtml_Block_Dashboard_Abstract {
	public function __construct() {
		parent::__construct();
		$this->setTemplate('dashboard/date.phtml');
	}

	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
}
