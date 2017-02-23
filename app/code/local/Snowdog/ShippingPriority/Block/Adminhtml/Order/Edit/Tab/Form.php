<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Order_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("shippingpriority_form", array("legend" => Mage::helper("shippingpriority")->__("Item information")));

        $fieldset->addField('shipping_priority', 'select', array(
            'label' => Mage::helper('shippingpriority')->__('Shipping Priority'),
            'values' => Snowdog_ShippingPriority_Block_Adminhtml_Order_Grid::getValueArray0(),
            'name' => 'shipping_priority',
        ));

        if (Mage::getSingleton("adminhtml/session")->getOrderData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getOrderData());
            Mage::getSingleton("adminhtml/session")->setOrderData(null);
        } elseif (Mage::registry("order_data")) {
            $form->setValues(Mage::registry("order_data")->getData());
        }
        return parent::_prepareForm();
    }
}
