<?php class Etheme_Idstore_Model_Adminhtml_Attachment
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('idstore')->__('Select')),
            array('value'=>'fixed', 'label'=>Mage::helper('idstore')->__('fixed')),
            array('value'=>'scroll', 'label'=>Mage::helper('idstore')->__('scroll'))     
        );
    }

}?>