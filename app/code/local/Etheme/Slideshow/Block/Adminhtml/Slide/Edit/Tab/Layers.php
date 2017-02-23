<?php

class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers extends Mage_Adminhtml_Block_Widget {
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('etheme/slideshow/edit/layers.phtml');
    }
    
    protected function _prepareLayout() 
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
            //->setName('admin.slide.addlayer')
            ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Layer'),
                    'class' => 'add',
                    'id'    => 'add_new_layer'))
        );
        
        
        $this->setChild('layers_box',
            $this->getLayout()->createBlock('etheme_slideshow/adminhtml_slide_edit_tab_layers_layer')
        );
        
        return parent::_prepareLayout();
    }
    
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getLayersBoxHtml()
    {
        return $this->getChildHtml('layers_box');
    }
    
}