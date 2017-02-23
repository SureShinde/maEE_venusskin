<?php

/**
 * Class Snowdog_Multilevel_Model_Sales_Order_Shipment
 */
class Snowdog_Multilevel_Model_Sales_Order_Shipment extends Mage_Sales_Model_Order_Shipment
{
    /**
     * Add field qty_ordered, qty_shipped and qty_backordered to collection
     *
     * @return mixed
     */
    public function getItemsCollection()
    {
        if (empty($this->_items)) {
            $this->_items = Mage::getResourceModel('sales/order_shipment_item_collection')
                ->setShipmentFilter($this->getId());

            if ($this->getId()) {
                foreach ($this->_items as $item) {
                    $item->setShipment($this);
                }
            }
        }

        return $this->_items;
    }
}