<?php class Etheme_Idstore_Model_Adminhtml_Headertype
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('idstore')->__('1')),
            array('value'=>'2', 'label'=>Mage::helper('idstore')->__('2')),
            array('value'=>'3', 'label'=>Mage::helper('idstore')->__('3')), 
            array('value'=>'4', 'label'=>Mage::helper('idstore')->__('4')),
            array('value'=>'5', 'label'=>Mage::helper('idstore')->__('5')),
        );
    }

}?>