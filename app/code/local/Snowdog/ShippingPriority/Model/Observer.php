<?php

class Snowdog_ShippingPriority_Model_Observer
{

    public function addShippingPriority(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $shippingMethod = $order->getShippingMethod();
        if ($shippingMethod == 'freeshipping_freeshipping') {
            $value = 'Ground';
        } else if (strstr($shippingMethod, 'matrixrate_matrixrate_')) {
            $pk = filter_var($shippingMethod, FILTER_SANITIZE_NUMBER_INT);
            $value = Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate')->getLabel($pk);
        }

        $order->setShippingPriority($value);
    }
}
