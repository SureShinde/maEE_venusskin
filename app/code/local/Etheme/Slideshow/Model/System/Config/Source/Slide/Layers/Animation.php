<?php

class Etheme_Slideshow_Model_System_Config_Source_Slide_Layers_Animation
{

    public function toOptionArray()
    {
        $helper = Mage::helper('etheme_slideshow');
        
        $animations = array(
            array('value' => '', 'label' => $helper->__('-- Please select --')),
            array('value' => 'fade', 'label' => $helper->__('Fade')),
            array('value' => 'sft', 'label' => $helper->__('Short from Top')),
            array('value' => 'sfb', 'label' => $helper->__('Short from Bottom')),
            array('value' => 'sfl', 'label' => $helper->__('Short from Left')),
            array('value' => 'sfr', 'label' => $helper->__('Short from Right')),
            array('value' => 'lft', 'label' => $helper->__('Long from Top')),
            array('value' => 'lfb', 'label' => $helper->__('Long from Bottom')),
            array('value' => 'lfl', 'label' => $helper->__('Long from Left')),
            array('value' => 'lfr', 'label' => $helper->__('Long from Right')),
            array('value' => 'randomrotate', 'label' => $helper->__('Random Rotate')),
        );
        
        return $animations;
    }
}
