<?php

class Etheme_Slideshow_Model_System_Config_Source_Slide_Transition
{

    public function toOptionArray()
    {
        $helper = Mage::helper('etheme_slideshow');
        
        $transitions = array(
            array('value' => '', 'label' => $helper->__('-- Please select --')),
            array('value' => 'random', 'label' => $helper->__('Random')),
            array('value' => 'fade', 'label' => $helper->__('Fade')),
            array('value' => 'slidehorizontal', 'label' => $helper->__('Slide Horizontal')),
            array('value' => 'slidevertical', 'label' => $helper->__('Slide Vertical')),
            array('value' => 'boxslide', 'label' => $helper->__('Box Slide')),
            array('value' => 'boxfade', 'label' => $helper->__('Box Fade')),
            array('value' => 'slotzoom-horizontal', 'label' => $helper->__('SlotZoom Horizontal')),
            array('value' => 'slotslide-horizontal', 'label' => $helper->__('SlotSlide Horizontal')),
            array('value' => 'slotfade-horizontal', 'label' => $helper->__('SlotFade Horizontal')),
            array('value' => 'slotzoom-vertical', 'label' => $helper->__('SlotZoom Vertical')),
            array('value' => 'slotslide-vertical', 'label' => $helper->__('SlotSlide Vertical')),
            array('value' => 'slotfade-vertical', 'label' => $helper->__('SlotFade Vertical')),
            array('value' => 'curtain-1', 'label' => $helper->__('Curtain 1')),
            array('value' => 'curtain-2', 'label' => $helper->__('Curtain 2')),
            array('value' => 'curtain-3', 'label' => $helper->__('Curtain 3')),
            array('value' => 'slideleft', 'label' => $helper->__('Slide Left')),
            array('value' => 'slideright', 'label' => $helper->__('Slide Right')),
            array('value' => 'slideup', 'label' => $helper->__('Slide Up')),
            array('value' => 'slidedown', 'label' => $helper->__('Slide Down')),
            array('value' => 'papercut', 'label' => $helper->__('Premium - Paper Cut')),
            array('value' => '3dcurtain-horizontal', 'label' => $helper->__('Premium - 3D Curtain Horizontal')),
            array('value' => '3dcurtain-vertical', 'label' => $helper->__('Premium - 3D Curtain Vertical')),
            array('value' => 'flyin', 'label' => $helper->__('Premium - Fly In')),
            array('value' => 'turnoff', 'label' => $helper->__('Premium - Turn Off')),
            array('value' => 'cubic', 'label' => $helper->__('Premium - Cubic')),
        );
        
        return $transitions;
    }
}
