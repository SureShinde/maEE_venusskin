<?php
class Inspiratica_Banner_Model_Mysql4_Block_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	public function _construct() {
		parent::_construct();
		$this->_init('banner/block');
	}
}
