<?php

class Etheme_Slideshow_Model_Slider extends Etheme_Slideshow_Model_Abstract {

    protected function _construct() {
        
        //init resource model
        $this->_init('etheme_slideshow/slider');
    }
    
    protected function _beforeSave() {
        $data = $this->getData();
        if (isset($data['stores'])) {
            if (!$this->getIsUniqueSliderToStore($data['stores']))
                Mage::throwException(Mage::helper('etheme_slideshow')->__('Slider identifier should be unique for each store view'));
        }
        parent::_beforeSave();
    }
    
    public function save() {
        
        $data = $this->getData();
        if (isset($data['stores']) && !Mage::app()->isSingleStoreMode()) {
            if (in_array('0', $data['stores']))
                $this->setStoreId(0);
            else {
                $storesArr = $data['stores'];
                
            }
            unset($data['stores']);
        }
        else 
            $storesArr = array(Mage::app()->getStore()->getId());
        Mage::log($storesArr, true, "stores.log");
        $this->setStoreId(implode(",", $storesArr));
        $time = Mage::getModel('core/date')->timestamp(time());
        if (!$this->getId())
            $this->setCreatedAt($time); 
        $this->setUpdatedAt($time);
        $fieldsToSerialize = array(
            'slider_type',
            'grid_width',
            'grid_height',
            'screen_width1',
            'slider_width1',
            'screen_width2',
            'screen_width2',
            'slider_width2',
            'screen_width3',
            'slider_width3',
            'screen_width4',
            'slider_width4',
            'screen_width5',
            'slider_width5',
            'screen_width6',
            'slider_width6',
            'load_gfont',
            'gfont_family',
            'delay',
            'touch_enabled',
            'stop_on_hover',
            'shuffle',
            'load_gfont',
            'gfont_family',
            'stop_slider',
            'stop_after_loops',
            'stop_at',
            'slider_position',
            'margin_left',
            'margin_right',
            'margin_top',
            'margin_bottom',
            'shadow_type',
            'show_timerline',
            'timerline_position',
            'background_color',
            'padding',
            'show_bg_image',
            'bg_image_url',
            'hide_slider_under',
            'hide_defined_layers_under',
            'hide_all_layers_under',
            'navigation_type',
            'navigation_arrows',
            'always_show_navigation',
            'navigation_style',
            'navigation_always_on',
            'hide_thumbs',
            'navigation_align_hor',
            'navigation_align_vert',
            'navigation_offset_hor',
            'navigation_offset_vert',
            'leftarrow_align_hor',
            'leftarrow_align_vert',
            'leftarrow_offset_hor',
            'leftarrow_offset_vert',
            'rightarrow_align_hor',
            'rightarrow_align_vert',
            'rightarrow_offset_hor',
            'rightarrow_offset_vert',
            'thumb_width',
            'thumb_height',
            'thumb_amount',
        );
        $this->setData('params', $this->serializeData($this->getData(), $fieldsToSerialize, 'params')); 
        parent::save();
    }
    
    public function load($id, $field=null) {
        
        $data = parent::load($id, $field);
        if ($raw = $data->getData('params')) {
            $params = $this->deserializeData($raw);
            foreach ($params['params'] as $key => $value) {
                $data->setData($key, $value);
            }
        }
        $this->setData('store_id', $this->getAssignedStores());
        return $data;
    }
    
    
    public function delete() {
        
        $sliderId = $this->getId();
        $slides = Mage::getModel('etheme_slideshow/slide')
                ->getCollection()
                ->addFieldToFilter('slider_id', array('eq' => $sliderId));
        foreach ($slides->getItems() as $slide) {
            $slide->delete();
        }
        $sliderDir = Mage::getBaseDir('media') . DS . 'etheme' . DS . 'slideshow' . DS . 'slider_' . $sliderId;
        $this->rrmdir($sliderDir);
        parent::delete();
    }

    /**
     * Create new slider using data from the old one
     * Create copy of all slides of all slider and assign these to new slider
     */
    public function duplicate() {
        
        $srcSliderId = $this->getId();
        $this->setId(null);
        $this->setIsNew(true);
        $this->save();
        $newSliderId = $this->getId(); 
        $slidesCollection = Mage::getModel('etheme_slideshow/slide')
                            ->getCollection()
                            ->addFieldToSelect('id')
                            ->addFieldToFilter('slider_id', array('eq' => $srcSliderId));
        foreach ($slidesCollection->getItems() as $slideId) {
            $slide = Mage::getModel('etheme_slideshow/slide')->load($slideId->getId());
            $slide->setSliderId($newSliderId)->setData('src_slider_id', $srcSliderId)->setData('source_id', $slideId->getId());
            $slide->duplicate(); 
        }
    }
    
    /**
     * Get collection of slide ids assigned to the current slider
     */

    
    public function getSlidesIds() {
        $collection = Mage::getModel('etheme_slideshow/slide')
                        ->getCollection()
                        ->addFieldToSelect('id')
                        ->setOrder('position', 'asc')
                        ->addFieldToFilter('slider_id', array('eq' => $this->getId()));       
        return $collection;
    }

    
    /** 
     * 
     * Gets array of store ids, assigned to current slider
     * @return array of store ids
     */
     /**/
    public function getAssignedStores() {
        
        if (Mage::app()->isSingleStoreMode()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = $this->getData('store_id');
            $stores = (array)explode(',', $stores);
        }
        
        return $stores;
    }
    /**/
    
    /**
     * Checks whether slider that attempts to be saved 
     * meets the requirement of unique set of stores
     * There shouldn't be two sliders assigned to the same store
     * @param $currentStores array of stores assigned to slider
     * @return boolean is slider unique to store
     */
    public function getIsUniqueSliderToStore($currentStores = array())
    {
        if (!Mage::app()->isSingleStoreMode()) {
            if (count($currentStores) == 0)
                $currentStores = $this->getStoreId();
            $collection = Mage::getModel('etheme_slideshow/slider')->getCollection();
            foreach ($collection->getItems() as $slider) { 
                //haha it's me don't look there
                if ($slider->getId() == $this->getId())
                    continue;
                //sliders with the same identifier should be validated!
                if ($slider->getIdentifier() == $this->getIdentifier()) {
                    $cmpStores = $slider->getStoreId();
                    //same store found in other slider 
                    if (count(array_intersect($currentStores, $cmpStores)) != 0)
                        return false;
                }
            }
        }
        return true;
    }
    
    private function rrmdir($dir) { 
        if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
            if ($object != "." && $object != "..") {
                if (filetype($dir . DS . $object) == "dir") 
                    $this->rrmdir($dir . DS . $object); 
                else 
                    unlink($dir . DS . $object); 
            }
        } 
        reset($objects); 
        rmdir($dir); 
        } 
    } 
}