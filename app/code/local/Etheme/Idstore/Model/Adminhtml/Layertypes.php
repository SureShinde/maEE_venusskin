<?php class Etheme_Idstore_Model_Adminhtml_Layertypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('idstore')->__('Collapsed')),
            array('value'=>2, 'label'=>Mage::helper('idstore')->__('Opened'))            
        );
    }

}?>