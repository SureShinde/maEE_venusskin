<?php

class Etheme_Slideshow_Model_System_Config_Source_Slider_Type
{

    public function toOptionArray()
    {
        $helper = Mage::helper('etheme_slideshow');
        
        $types = array(
            array('value' => 'fixed', 'label' => $helper->__('Fixed')),
            array('value' => 'responsive', 'label' => $helper->__('Responsive')),
            array('value' => 'fullwidth', 'label' => $helper->__('Full Width')),
        );
        
        return $types;
    }
    
}