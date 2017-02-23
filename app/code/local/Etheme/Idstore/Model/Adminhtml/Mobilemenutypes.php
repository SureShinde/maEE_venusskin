<?php class Etheme_Idstore_Model_Adminhtml_Mobilemenutypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'default', 'label'=> Mage::helper('idstore')->__('Default Menu')),
            array('value'=>'side', 'label'=> Mage::helper('idstore')->__('Side Menu'))            
        );
    }

}?>