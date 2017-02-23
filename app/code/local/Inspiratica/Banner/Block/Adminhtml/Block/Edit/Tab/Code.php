<?php
class Inspiratica_Banner_Block_Adminhtml_Block_Edit_Tab_Code extends Mage_Adminhtml_Block_Widget_Form {
	public function __construct() {
		parent::__construct();
		$this->setTemplate('banner/code.phtml');
	}
}
