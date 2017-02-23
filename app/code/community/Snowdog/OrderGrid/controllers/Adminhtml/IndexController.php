<?php

class Snowdog_OrderGrid_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function skuAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $order->getAllVisibleItems();
        foreach ($items as $i) {
            echo $i->getSku() . '<br>';
        }
    }

    public function qtyAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $order->getAllVisibleItems();
        foreach ($items as $i) {
            echo $i->getQtyOrdered() . '<br>';
        }
    }
}