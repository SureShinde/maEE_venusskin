<?php
 
class Etheme_Idstore_Block_Adminhtml_Headingfont extends Mage_Adminhtml_Block_System_Config_Form_Field {
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        
        $html = parent::_getElementHtml($element);
        
        $fonts = Mage::getModel('idstore/adminhtml_googlefont')->toOptionArray();
        
        $html .= '<style>';
        
        foreach ($fonts as $font)
            $html .= '@import url(http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $font['value']) . ');';
        $html .= '</style>';
        $html .= '<br/><div class="gfont-preview"><h1>Lorem Ipsum Dolor</h1></div>';
        
        return $html;
    }
}