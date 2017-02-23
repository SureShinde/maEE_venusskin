<?php

class Snowdog_PartialShipping_Model_Observer
{

    public function salesOrderShipmentSaveAfter(Varien_Event_Observer $observer)
    {
        $order = $observer->getShipment()->getOrder();
        if (true == $order->canShip()) {
            $order->setStatus('pending')
                ->save();
        }

        if (false == $order->canShip()) {
            $order->setStatus('processing')
                ->save();
        }
    }
}