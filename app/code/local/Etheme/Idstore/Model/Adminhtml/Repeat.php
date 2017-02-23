<?php class Etheme_Idstore_Model_Adminhtml_Repeat
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('idstore')->__('Select')),
            array('value'=>'no-repeat', 'label'=>Mage::helper('idstore')->__('no-repeat')),
            array('value'=>'repeat-x', 'label'=>Mage::helper('idstore')->__('repeat-x')),   
            array('value'=>'repeat-y', 'label'=>Mage::helper('idstore')->__('repeat-y'))        
        );
    }

}?>