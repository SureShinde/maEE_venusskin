<?php class Etheme_Idstore_Model_Adminhtml_Positionx
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('idstore')->__('Select')),
            array('value'=>'left', 'label'=>Mage::helper('idstore')->__('left')),
            array('value'=>'center', 'label'=>Mage::helper('idstore')->__('center')), 
            array('value'=>'right', 'label'=>Mage::helper('idstore')->__('right'))     
        );
    }

}?>