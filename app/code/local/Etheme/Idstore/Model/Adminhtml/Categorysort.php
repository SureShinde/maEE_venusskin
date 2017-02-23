<?php class Etheme_Idstore_Model_Adminhtml_Categorysort
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'position', 'label'=>Mage::helper('idstore')->__('Position')),
            array('value'=>'name', 'label'=>Mage::helper('idstore')->__('Name')),
            array('value'=>'id', 'label'=>Mage::helper('idstore')->__('Id')),
        );
    }

}?>