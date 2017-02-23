<?php

class Etheme_Slideshow_Block_Slider extends Mage_Core_Block_Template {
    
    private $sliderId, $slider, $identifier;

    const DEFAULT_YOUTUBE_ARGUMENTS = "hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0;";
    
    public function getSlider($identifier) {
        $this->identifier = $identifier;
        $currentStoreId = Mage::app()->getStore()->getStoreId();
        $sliders = Mage::getModel('etheme_slideshow/slider')
                    ->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('identifier', array('eq' => $identifier));
        foreach ($sliders->getItems() as $slider) {
            $assignedStores = $slider->getStoreId();
            if (in_array($currentStoreId, $assignedStores)) {
                //best match
                $this->slider = Mage::getModel('etheme_slideshow/slider')->load($slider->getId());
                break;
            }
            if (in_array('0', $assignedStores)) {
                $this->slider = Mage::getModel('etheme_slideshow/slider')->load($slider->getId());
            }
        }
        return $this->slider; 
    }

    public function getYoutubeArgs() {
        return self::DEFAULT_YOUTUBE_ARGUMENTS;
    }
    
    public function getSlides() {
        
        $slidesCollection = $this->slider->getSlidesIds();
        $slides = array();
        foreach ($slidesCollection->getItems() as $slideId) {
            $slides[] = Mage::getModel('etheme_slideshow/slide')->load($slideId->getId());
        }
        return $slides;
        
    }
    
}