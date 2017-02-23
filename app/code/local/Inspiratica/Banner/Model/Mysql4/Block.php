<?php
class Inspiratica_Banner_Model_Mysql4_Block extends Mage_Core_Model_Mysql4_Abstract {
	public function _construct() {
		$this->_init('banner/block', 'id');
	}
}
