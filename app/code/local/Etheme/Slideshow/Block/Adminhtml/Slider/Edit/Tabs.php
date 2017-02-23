<?php

class Etheme_Slideshow_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
    
    public function _construct() {
        
        parent::_construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('etheme_slideshow')->__('Slider tabs descr'));
    }
    
    protected function _beforeToHtml() {
        
        $this->addTab('general_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('General'),
            'title' => Mage::helper('etheme_slideshow')->__('General'),
            'content' => $this->getLayout()
                              ->createBlock('etheme_slideshow/adminhtml_slider_edit_tab_general')->toHtml()
        ));
        
        $this->addTab('settings_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('Settings'),
            'title' => Mage::helper('etheme_slideshow')->__('Settings'),
            'content' => $this->getLayout()
                              ->createBlock('etheme_slideshow/adminhtml_slider_edit_tab_settings')->toHtml()
        ));
        
        $this->addTab('appearance_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('Appearance'),
            'title' => Mage::helper('etheme_slideshow')->__('Appearance'),
            'content' => $this->getLayout()
                              ->createBlock('etheme_slideshow/adminhtml_slider_edit_tab_appearance')->toHtml()
        ));
        
        $this->addTab('navigation_section', array(
            'label' => Mage::helper('etheme_slideshow')->__('Navigation'),
            'title' => Mage::helper('etheme_slideshow')->__('Navigation'),
            'content' => $this->getLayout()
                              ->createBlock('etheme_slideshow/adminhtml_slider_edit_tab_navigation')->toHtml()
        ));
        
        return parent::_beforeToHtml(); 
    }
}