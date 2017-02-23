<?php

class Etheme_Slideshow_Model_System_Config_Source_Slide_Layers_Type
{
    const SLIDE_LAYERS_GROUPS_PATH = 'global/etheme_slideshow/layers/types';

    public function toOptionArray()
    {
        $helper = Mage::helper('etheme_slideshow');
        
        $types = array(
            array('value' => '', 'label' => $helper->__('-- Please select --'))
        );


        foreach (Mage::getConfig()->getNode(self::SLIDE_LAYERS_GROUPS_PATH)->children() as $type) {
            
            $name = $type->getName();
            $render = Mage::getConfig()->getNode(self::SLIDE_LAYERS_GROUPS_PATH . '/' . $name . '/render');
            $label = Mage::getConfig()->getNode(self::SLIDE_LAYERS_GROUPS_PATH . '/' . $name . '/label');
            $types[] = array('value' => $name, 'label' => $label);
        }
        
        return $types;
    }
}
