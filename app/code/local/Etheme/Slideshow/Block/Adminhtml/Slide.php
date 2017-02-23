<?php

class Etheme_Slideshow_Block_Adminhtml_Slide extends Mage_Adminhtml_Block_Widget_Grid_Container {
    
    /**
     * init grid container settings
     * 
     * The child grid block class will be:
     * 
     * $this->_blockGroup . '/' . $this->_controller . '_grid'
     */
     
     public function __construct() {
        $sliderModel = Mage::registry('current_slider');
        $sliderId = $this->getRequest()->getParam('slider_id');
        $slider = $sliderModel->load($sliderId);
        
        $this->_blockGroup = 'etheme_slideshow';
        $this->_controller = 'adminhtml_slide';
        $this->_headerText = $this->__('List of slides in ') . $slider->getName();
        $this->addButton('back', array(
            'label'     => $this->__('Back To Sliders'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/sliders/list') .'\')',
            'class'     => 'back',
        ));
        parent::__construct();
        $this->updateButton('add', 'label', "Add New Slide");
        $this->updateButton('add', 'onclick', 'setLocation(\'' . $this->getUrl('*/*/edit', array('slider_id' => $sliderId)) . '\')');
     }
}