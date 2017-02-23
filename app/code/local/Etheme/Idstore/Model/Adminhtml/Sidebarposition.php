<?php class Etheme_Idstore_Model_Adminhtml_Sidebarposition
{
    public function toOptionArray()
    {
        return array(
            array('value'=> 0, 'label'=>Mage::helper('idstore')->__('Left')),
            array('value'=> 1, 'label'=>Mage::helper('idstore')->__('Right'))
        );
    }

}?>