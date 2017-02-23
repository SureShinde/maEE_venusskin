<?php

class Etheme_Slideshow_Model_Mysql4_Slide_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    
    protected function _construct() {
        
        //init resource model
        $this->_init('etheme_slideshow/slide');
    }
}