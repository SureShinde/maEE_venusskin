<?php

class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
    
    public function _construct() {
        
        parent::_construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('etheme_slideshow')->__('Slide tabs descr'));
    }
    
    protected function _beforeToHtml() {
        
        $this->addTab('general_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('General'),
            'title' => Mage::helper('etheme_slideshow')->__('General'),
            'content' => $this->getLayout()
                ->createBlock('etheme_slideshow/adminhtml_slide_edit_tab_general')->toHtml()
        ));
        
        $this->addTab('content_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('Content'),
            'title' => Mage::helper('etheme_slideshow')->__('Content'),
            'content' => $this->getLayout()
                ->createBlock('etheme_slideshow/adminhtml_slide_edit_tab_content')->toHtml()
        ));
        
        return parent::_beforeToHtml(); 
    }
}