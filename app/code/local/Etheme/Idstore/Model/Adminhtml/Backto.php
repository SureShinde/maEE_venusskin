<?php class Etheme_Idstore_Model_Adminhtml_Backto
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'disable', 'label'=> Mage::helper('idstore')->__('Disable')),
            array('value'=>'modern', 'label'=> Mage::helper('idstore')->__('Modern')) ,
            array('value'=>'default', 'label'=> Mage::helper('idstore')->__('Default'))            
        );
    }

}?>