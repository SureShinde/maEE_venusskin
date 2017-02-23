<?php class Etheme_Idstore_Model_Adminhtml_Productpagetypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'default', 'label'=>Mage::helper('idstore')->__('Default')),
            array('value'=>'horizontal', 'label'=>Mage::helper('idstore')->__('Horizontal')),
            //array('value'=>'universal', 'label'=>Mage::helper('idstore')->__('Universal'))   
        );
    }

}?>