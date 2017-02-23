<?php

class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Layer extends Mage_Adminhtml_Block_Widget 
{
    
    protected $slide;
    
    protected $_itemCount = 1;
    
    protected $_values;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('etheme/slideshow/edit/layers/layer.phtml');
    }
    
    public function getSlide()
    {
        if (!$this->slide) {
            if ($slide = Mage::registry('current_slide')) {
                $this->slide = $slide;
            } else {
                $this->slide = Mage::getModel('etheme_slideshow/slide');
            }
        }
        return $this->slide;
    }
    
    public function getItemCount() 
    {
        return $this->_itemCount;
    }
    
    public function setItemCount($newCount) 
    {
        $this->_itemCount = max($newCount, $this->_itemCount);
        return $this;
    }
    
    public function getFieldName()
    {
        return 'layers';
    }

    public function getFieldId()
    {
        return 'slide_layer';
    }
    
    protected function _prepareLayout() 
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Layer'),
                    'class' => 'delete delete-layer '
                ))
        );
        
        $path = 'global/etheme_slideshow/layers/types';
        
        /**/
        foreach (Mage::getConfig()->getNode($path)->children() as $type) {
            $this->setChild($type->getName() . '_layer_type',
                $this->getLayout()->createBlock((string) Mage::getConfig()->getNode($path . '/' . $type->getName() . '/render'))
            );
        }
        /**/        
        return parent::_prepareLayout();
    }
    
    public function getAddButtonId()
    {
        /** /
        $buttonId = $this->getLayout()->getBlock('slide_admin_edit_tabs')
                                      ->getTab('content_section')
                                      ->getChild('layers_block')
                                      ->getChild('add_button')->getId();
        /**/
        $buttonId = 'add_new_layer';
        return $buttonId;
    }
    
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }
    
    public function getTypeSelectHtml() 
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId(). '_{{id}}_type',
                'class' => 'select select-slide-layer-type required-option-select'
            ))
            ->setName($this->getFieldName() . '[{{id}}][type]')
            ->setOptions(Mage::getSingleton('etheme_slideshow/system_config_source_slide_layers_type')->toOptionArray());
        return $select->getHtml();
    }
    
    /**
     * Retrieve html templates for different types layers
     *
     * @return string
     */
    public function getTemplatesHtml()
    {
        $templates = $this->getChildHtml('text_layer_type') . "\n" .
                     $this->getChildHtml('image_layer_type') . "\n" .
                     $this->getChildHtml('video_layer_type');
                     
        return $templates;
    }
    
    /** Method that loads all layers data
     * that then will be displayed in phtml
     */ 
    public function getLayers() 
    {
        $layersArr = $this->getSlide()->getLayers();
        if (!$layersArr)
            return null;
            
        $layersArr = array_reverse($layersArr, true);

        if (!$this->_values) {
            
            $values = array();

            foreach ($layersArr as $layer) {
                
                //Mage::log($layer, true, 'load_layer.log');
                $this->setItemCount($layer['id']);
                
                $value = array();
                $value['id'] = $this->getLayerValue($layer, 'id');
                $value['item_count'] = $this->getItemCount();
                $value['option_id'] = $this->getLayerValue($layer, 'id');
                $value['name'] = $this->getLayerValue($layer, 'name');
                $value['type'] = $this->getLayerValue($layer, 'type');
                $value['appearance_time'] = $this->getLayerValue($layer, 'appearance_time');
                $value['order'] = $this->getLayerValue($layer, 'order');
                $value['animation'] = $this->getLayerValue($layer, 'animation');
                $value['easing'] = $this->getLayerValue($layer, 'easing');
                $value['speed'] = $this->getLayerValue($layer, 'speed');
                $value['xoffset'] = $this->getLayerValue($layer, 'xoffset');
                $value['yoffset'] = $this->getLayerValue($layer, 'yoffset');
                
                //switch ( type )
                
                switch ($value['type']) {
                    
                    case 'text': 
                        $value['text'] = $this->getLayerValue($layer, 'text');
                        $value['style'] = $this->getLayerValue($layer, 'style');
                        break;
                    case 'video':
                        $value['url'] = $this->getLayerValue($layer, 'url');
                        $value['width'] = $this->getLayerValue($layer, 'width');
                        $value['height'] = $this->getLayerValue($layer, 'height');
                        $value['args'] = $this->getLayerValue($layer, 'args');
                        $value['autoplay'] = $this->getLayerValue($layer, 'autoplay');
                        $value['nextslideafter'] = $this->getLayerValue($layer, 'nextslideafter');
                        break;
                    case 'image':
                        $value['image'] = $this->getLayerValue($layer, 'image');
                        $value['image_url'] = $this->getSlide()->getLayerImageUrl($layer['id']);
                }
                 
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }
        return $this->_values;
    }
    
    private function getLayerValue($layer, $key, $default=null) {
        if (is_array($layer) && array_key_exists($key, $layer)) 
            return $layer[$key];
        else
            return $default;
    }
    
}

















