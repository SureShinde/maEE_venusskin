<?php class Etheme_Idstore_Model_Adminhtml_Menutypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=> Mage::helper('idstore')->__('Wide Menu')),
            array('value'=>2, 'label'=> Mage::helper('idstore')->__('Superfish Menu'))            
        );
    }

}?>