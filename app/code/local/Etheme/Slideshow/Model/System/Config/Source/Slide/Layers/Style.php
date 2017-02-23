<?php

class Etheme_Slideshow_Model_System_Config_Source_Slide_Layers_Style
{

    public function toOptionArray()
    {
        
        $styles = array(
            array('value' => '', 'label' => 'No special style'),
            array('value' => 'main-caption', 'label' => 'main-caption'),
            array('value' => 'heading-white', 'label' => 'heading-white'),
            array('value' => 'main-text-white', 'label' => 'main-text-white'),
            array('value' => 'main-text', 'label' => 'main-text'),
            array('value' => 'big_red', 'label' => 'big_red'),
            array('value' => 'big_gray', 'label' => 'big_gray'),
            array('value' => 'simple_text', 'label' => 'simple_text'),
            array('value' => 'simple_text2', 'label' => 'simple_text2'),
            array('value' => 'big_white', 'label' => 'big_white'),
            array('value' => 'big_orange', 'label' => 'big_orange'),
            array('value' => 'big_black', 'label' => 'big_black'),
            array('value' => 'medium_grey', 'label' => 'medium_grey'),
            array('value' => 'small_text', 'label' => 'small_text'),
            array('value' => 'medium_text', 'label' => 'medium_text'),
            array('value' => 'large_text', 'label' => 'large_text'),
            array('value' => 'very_large_text', 'label' => 'very_large_text'),
            array('value' => 'very_big_white', 'label' => 'very_big_white'),
            array('value' => 'very_big_black', 'label' => 'very_big_black'),
            array('value' => 'boxshadow', 'label' => 'boxshadow'),
            array('value' => 'black', 'label' => 'black'),
            array('value' => 'noshadow', 'label' => 'noshadow'),
        );
        
        return $styles;
    }
}
