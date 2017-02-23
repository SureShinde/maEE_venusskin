<?php
class Inspiratica_Banner_Block_Adminhtml_Block extends Mage_Adminhtml_Block_Widget_Grid_Container {
	public function __construct() {
		$this->_controller     = 'adminhtml_block';
		$this->_blockGroup     = 'banner';
		$this->_headerText     = Mage::helper('banner')->__('Block Manager');
		$this->_addButtonLabel = Mage::helper('banner')->__('Add Block');
		parent::__construct();
	}
}
