<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Order_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "entity_id";
        $this->_blockGroup = "shippingpriority";
        $this->_controller = "adminhtml_order";
        $this->_updateButton("save", "label", Mage::helper("shippingpriority")->__("Save Item"));
        $this->_removeButton("delete");
        $this->_removeButton("reset");
        $this->_removeButton("back");
        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("shippingpriority")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
        ), -100);


        $this->_addButton("back-to-order", array(
            "label" => Mage::helper("shippingpriority")->__("Back"),
            "onclick" => "setLocation('" . Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=> $this->getRequest()->getParam("id"))) . "')",
            "class" => "back",
        ), 0);


        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText()
    {
        if (Mage::registry("order_data") && Mage::registry("order_data")->getId()) {
            return Mage::helper("shippingpriority")->__("Edit Shipping priority for order '%s'", $this->htmlEscape(Mage::registry("order_data")->getId()));
        } else {
            return Mage::helper("shippingpriority")->__("Add Item");
        }
    }
}