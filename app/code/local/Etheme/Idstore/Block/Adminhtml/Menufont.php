<?php
 
class Etheme_Idstore_Block_Adminhtml_Menufont extends Mage_Adminhtml_Block_System_Config_Form_Field {
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        
        $html = parent::_getElementHtml($element);
        
        $fonts = Mage::getModel('idstore/adminhtml_googlefont')->toOptionArray();
        
        $html .= '<br/><div class="gfont-preview"><span>Apparel Furniture Accessories</span></div>';
        
        return $html;
    }
}