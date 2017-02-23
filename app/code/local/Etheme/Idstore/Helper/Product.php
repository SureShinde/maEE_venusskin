<?php
class Etheme_Idstore_Helper_Product extends Mage_Core_Helper_Abstract {
    
    /**
     * Product settings helper for media.phtml
     * $mainWidth - width of main image
     * $mainHeight - height of main image
     * $carWidth - width of thumbnails slider area
     * $zoomWidth - width of zoom window
     * $smallWidth - thumbnail width
     * $smallHeight - thumbnail height
     * $zoomOffset - left offset of zoom window from main image (not used directly)
     * $zoomLeft - total left offset for zoom window 
     * $minThumbsCount - minimum count of thumbnails to run slider
     * */
    
    private $pageSettings;
    
    public function initLayout($layout) {

        $settins = array();
        
        switch ($layout) {
            case 'default':
                $settings['main_width'] = 440;
                $settings['main_height'] = 600;
                $settings['car_width'] = 320;
                $settings['zoom_width'] = 450;
                $settings['min_thums'] = 4;
                $settings['desc_span'] = 5;
                break;
            case 'horizontal':
                $settings['main_width'] = 440;
                $settings['main_height'] = 600;
                $settings['car_width'] = 320;
                $settings['zoom_width'] = 450;
                $settings['min_thums'] = 4;
                $settings['desc_span'] = 7;
                //lalala
                break;
            case 'vertical':
                break;
            
        }
        $settings['zoom_offset'] = 17;
        $settings['small_width'] = 75;
        $settings['small_height'] = 100;
        $settings['zoom_left'] = $settings['main_width'] + $settings['zoom_offset'];

        $this->pageSettings = $settings;
        
    }

    public function getValue($value) {
        return $this->pageSettings[$value];
    }

}