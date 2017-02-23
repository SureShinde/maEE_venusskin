<?php class Etheme_Idstore_Model_Adminhtml_Categorylayout
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'page/2columns-left.phtml', 'label'=>Mage::helper('idstore')->__('With left sidebar')),
            array('value'=>'page/1column.phtml', 'label'=>Mage::helper('idstore')->__('One column')),
        );
    }

}?>