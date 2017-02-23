<?php

class Etheme_Slideshow_Model_Slide extends Etheme_Slideshow_Model_Abstract {
    
    private $imgHelper;
    
    protected function _construct() {
        
        //init resource model
        $this->_init('etheme_slideshow/slide');
        $this->imgHelper = Mage::helper('etheme_slideshow/image');
    }

    public function save() {
               
        $this->removeDeletedImages();
        $time = Mage::getModel('core/date')->timestamp(time());
        if (!$this->getId())
            $isNew = true;
        else
            $isNew = false;
        if ($isNew)
            $this->setCreatedAt($time); 
        $this->setUpdatedAt($time);
        $this->serializeFields();
        parent::save();
        if ($isNew) {
            $slider = Mage::getModel('etheme_slideshow/slider')->load($this->getSliderId());
            $slider->setSlidesCount($slider->getSlidesCount() + 1)->save();
        }
    }
    
    private function serializeFields() {
        //Serialize some fields came from POST request and store them as string
        $data = $this->getData();
        $layersField = array('layers');
        $params = array(
            'position',
            'transition',
            'delay',
            'slot_amount',
            'rotation',
            'full_width_cent',
            'transition_duration',
            'bg',
            'enable_link',
            'slide_link',
            'link_open_in'
        );
        $this->setData('params', $this->serializeData($data, $params));
        $this->setData('layers', $this->removeDeletedLayers($this->getData('layers')));
        $this->setData('layers', $this->serializeData($this->getData(), $layersField));
    }
    
    public function load($id, $field=null) {
        
        $loaded = parent::load($id, $field);
        $params = $this->deserializeData($loaded->getData('params'));
        $layers = $this->deserializeData($loaded->getData('layers'));
        foreach ($params as $key => $value) {
            $loaded->setData($key, $value);
        }
        $loaded->setData('layers', $layers['layers']);
        return $loaded;
    }
    
    public function delete() {
        
        $slider = Mage::getModel('etheme_slideshow/slider')->load($this->getSliderId());
        $oldCount = $slider->getSlidesCount();
        $slider->setSlidesCount($oldCount - 1)->save();
        parent::delete();
    }
    
    public function duplicate() {
        
        $srcId = $this->getId();
        $this->setId(null)->setIsNew(true)->save();
        $new = self::load($this->getId());
        $new->setData('duplicate_source_id', $srcId);
        $new->saveImages();
        return $new;
    }

    public function getOrderedLayers() {

        $layers = $this->getLayers();
        usort($layers, array($this, 'cmpLayers'));
        return $layers;
    }

    private function cmpLayers($layer1, $layer2) {
        return $layer1['order'] - $layer2['order'];
    }
    
    public function getLayerImageUrl($layerId) {
        
        $layers = $this->getLayers();
        if (array_key_exists($layerId, $layers) && $layers[$layerId]['type'] == 'image') {
            return Mage::getBaseUrl('media') . $layers[$layerId]['image'];
        }
        else 
            return '';
    }
    
    private function removeDeletedLayers($layers) {
        foreach ($layers as $layer) {
            if ($layer['is_delete'] == '1')
                unset($layers[$layer['id']]);
        }
        return $layers;
    }
    
    
    private function saveImage($imgName) {
        
        if (isset($_FILES[$imgName]['name']) && file_exists($_FILES[$imgName]['tmp_name'])) {
            $filepath = $this->imgHelper->uploadImage($imgName, $this->getSliderId(), $this->getId());
            $this->setData($imgName, $filepath);
            unset($_FILES[$imgName]);
            return true;
        }
        return false;
    }
    
    private function saveLayerImages() {
        
        $flag = false;
        $layers = $this->getLayers();
        foreach($layers as $layer) {
            $id = $layer['id'];
            if ($layer['type'] == 'image') {
                $imgName = 'layer_' . $id;
                if (isset($_FILES[$imgName]['name']) && file_exists($_FILES[$imgName]['tmp_name'])) {
                    $filepath = $this->imgHelper->uploadImage($imgName, $this->getSliderId(), $this->getId());
                    $layers[$id]['image'] = $filepath;
                    $flag = true;
                }
            }
        }
        
        if ($flag)
            $this->setLayers($layers);
        return $flag;
    }
    
    private function duplicateImage($imgName, $srcId) {
        
        $dstId = $this->getId();
        $dstSliderId = $this->getSliderId();
        $src = Mage::getModel('etheme_slideshow/slide')->load($srcId);
        $srcPath = $src->getData($imgName);
        $newPath = $this->imgHelper->copyImage($srcPath, $imgName, $srcId, $dstId, $dstSliderId);
        $this->setData($imgName, $newPath);
    }
    
    private function duplicateLayerImages($srcId) {
        
        $dstId = $this->getId();
        $dstSliderId = $this->getSliderId();
        $src = Mage::getModel('etheme_slideshow/slide')->load($srcId);
        $layers = $this->getLayers();
        $srcLayers = $src->getLayers();
        foreach($layers as $layer) {
            $id = $layer['id'];
            if ($layer['type'] == 'image') {
                $srcPath = $srcLayers[$id]['image'];
                $imgName = 'layer_' . $id;
                $filepath = $this->imgHelper->copyImage($srcPath, $imgName, $srcId, $dstId, $dstSliderId);
                $layers[$id]['image'] = $filepath;
            }
        }
        $this->setLayers($layers);
    }
    
    private function removeDeletedImages() {
        
        $this->autoremoveImage('bg');
        $this->checkImage('bg');
    }
    
    private function autoremoveImage($imgName) {
        
        $data = $this->getData();
        if (isset($data[$imgName]['delete']) && $data[$imgName]['delete'] == 1) {
            $data[$imgName] = '';
            $this->unsetData();
            $this->setData($data);
        }
    }
    
    private function checkImage($imgName) {
        
        $data = $this->getData();
        if (is_array($this->getData($imgName)) && $tmp = $this->getData($imgName)) {
            $this->setData($imgName, $tmp['value']);    
        }
    }
    
    public function saveImages() {
        
        $srcId = $this->getData('duplicate_source_id');
        if (!$srcId) { //Save images
            $this->saveImage('bg');
            $this->saveLayerImages();
        }
        
        else { //duplicate images
            $this->duplicateImage('bg', $srcId);
            $this->duplicateLayerImages($srcId);
        }
        $this->save();
    }
}

















