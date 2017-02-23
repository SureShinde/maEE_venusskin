<?php

class Etheme_Slideshow_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container {
    
    /**
     * init grid container settings
     * 
     * The child grid block class will be:
     * 
     * $this->_blockGroup . '/' . $this->_controller . '_grid'
     */
     
     public function __construct() {
        $this->_blockGroup = 'etheme_slideshow';
        $this->_controller = 'adminhtml_slider';
        $this->_headerText = $this->__('List of sliders');
        parent::__construct();     
        $this->updateButton('add', 'label', "Add New Slider");
     }
}