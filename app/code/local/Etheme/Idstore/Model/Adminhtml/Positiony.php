<?php class Etheme_Idstore_Model_Adminhtml_Positiony
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('idstore')->__('Select')),
            array('value'=>'top', 'label'=>Mage::helper('idstore')->__('top')),
            array('value'=>'center', 'label'=>Mage::helper('idstore')->__('center')), 
            array('value'=>'bottom', 'label'=>Mage::helper('idstore')->__('bottom'))     
        );
    }

}?>