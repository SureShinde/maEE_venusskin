<?php

class Snowdog_ShippingPriority_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{

    public function editAction()
    {
        $this->_title($this->__("ShippingPriority"));
        $this->_title($this->__("Order"));
        $this->_title($this->__("Edit Order"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("sales/order")->load($id);
        if ($model->getId()) {
            Mage::register("order_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("shippingpriority/order");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Order Manager"), Mage::helper("adminhtml")->__("Order Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Order Description"), Mage::helper("adminhtml")->__("Order Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock("shippingpriority/adminhtml_order_edit"))
                ->_addLeft($this->getLayout()->createBlock("shippingpriority/adminhtml_order_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("shippingpriority")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }


    public function saveAction()
    {
        $post_data = $this->getRequest()->getPost();
        if ($post_data) {
            try {
                $model = Mage::getModel("sales/order")->load($this->getRequest()->getParam("id"));

                $model->setData('shipping_priority', $post_data['shipping_priority'])
                    ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setOrderData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')
                    ->getUrl("adminhtml/sales_order/view", array('order_id' => $model->getId())));
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setOrderData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $model->getId()));
                return;
            }
        }

        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')
            ->getUrl("adminhtml/sales_order/view", array('order_id' => $this->getRequest()->getParam("id"))));
    }
}
