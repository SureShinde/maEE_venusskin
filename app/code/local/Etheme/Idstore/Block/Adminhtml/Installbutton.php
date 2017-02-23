<?php
 
class Etheme_Idstore_Block_Adminhtml_Installbutton extends Mage_Adminhtml_Block_System_Config_Form_Field {
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('idstore/index/installBlocks'); 

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Install Now')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();

        return $html;
    }
}