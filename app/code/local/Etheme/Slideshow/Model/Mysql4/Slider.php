<?php

class Etheme_Slideshow_Model_Mysql4_Slider extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        
        //specify which table and primary key to use. Table will be taken from config.xml
        $this->_init('etheme_slideshow/slider', 'id');
    }   
    
}