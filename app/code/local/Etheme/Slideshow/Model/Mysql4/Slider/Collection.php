<?php

class Etheme_Slideshow_Model_Mysql4_Slider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

	private $storeFilter;
    
    protected function _construct() {
        
        //init resource model
        $this->_init('etheme_slideshow/slider');
    }
	
	public function addStoreFilter($store) {
		
		if ($store instanceof Mage_Core_Model_Store) {
	        $store = array($store->getId());
	    }
	
	    if (!is_array($store)) {
	        $store = array($store);
	    }
	    $this->storeFilter = $store;
	    return $this;
	}
	
	protected function _afterLoad() {
		
		foreach ($this->_items as $key => $_item)
		{
			$stores = explode(",", $_item->getStoreId());
			if (!is_array($stores))
				$stores = array($stores);
			$_item->setStoreId($stores);
			
			if ($this->storeFilter && count(array_intersect($stores, $this->storeFilter)) < 1) {
				$this->removeItemByKey($key);
			}
		}
		
		//aaarrgghh
		if (count($this->_items) <= 0) {
			$this->_totalRecords = 0;
		}
		return parent::_afterLoad();
	}

}