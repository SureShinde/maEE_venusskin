<?php class Etheme_Idstore_Model_Adminhtml_Zoomtypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'hover', 'label'=>Mage::helper('idstore')->__('Hover')),
            array('value'=>'lightbox', 'label'=>Mage::helper('idstore')->__('Lightbox')) 
        );
    }

}?>