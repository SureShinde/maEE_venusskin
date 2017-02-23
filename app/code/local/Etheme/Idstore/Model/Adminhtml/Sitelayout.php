<?php class Etheme_Idstore_Model_Adminhtml_Sitelayout
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'wide', 'label'=>Mage::helper('idstore')->__('Wide')),
            array('value'=>'boxed', 'label'=>Mage::helper('idstore')->__('Boxed')) 
        );
    }

}?>