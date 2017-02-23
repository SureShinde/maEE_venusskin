<?php

/**
 * Class Snowdog_ManageRecurringStock_Model_Observer
 */
class Snowdog_ManageRecurringStock_Model_Observer
{
    const PARENT_ATTRIBUTE_CODE = 'onetime_purchase_variant_id';

    protected $_websideId;

    protected $_productModel;

    protected $_stockModel;

    public function __construct()
    {
        $this->_websideId = Mage::app()->getWebsite()->getId();

        $this->_productModel = Mage::getModel('catalog/product');

        $this->_stockModel = Mage::getModel('cataloginventory/stock_item');
    }

    /**
     * Remove qty from recurring profile after success order
     *
     * @param Varien_Event_Observer $observer
     */
    public function manageRecurringStock(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            if (!$item->getProduct()->getIsRecurring()) {
                $recurringProduct = $this->_productModel->loadByAttribute(
                    self::PARENT_ATTRIBUTE_CODE,
                    $item->getProductId()
                );
                if ($recurringProduct && $recurringProduct->getId()) {
                    $stock = $this->_stockModel->loadByProduct(
                        $recurringProduct,
                        $this->_websideId
                    );
                    $qty = $stock->getQty();
                    $stock->setQty($qty - $item->getQtyOrdered());
                    $stock->save();
                }
            }
        }
    }

    /**
     * Revert order qty after cancel order item
     *
     * @param Varien_Event_Observer $observer
     * @throws Exception
     */
    public function cancelOrderItem(Varien_Event_Observer $observer)
    {
        $item = $observer->getEvent()->getItem();
        if (!$item->getProduct()->getIsRecurring()) {
            $recurringProduct = $this->_productModel->loadByAttribute(
                self::PARENT_ATTRIBUTE_CODE,
                $item->getProductId()
            );
            if ($recurringProduct && $recurringProduct->getId()) {
                $stock = $this->_stockModel->loadByProduct(
                    $recurringProduct,
                    $this->_websideId
                );
                $qty = $stock->getQty();
                $stock->setQty($qty + $item->getQtyOrdered());
                $stock->save();
            }
        }
    }

/* Comment observer add qty after refund */

//    /**
//     * Refund order qty after refund
//     *
//     * @param Varien_Event_Observer $observer
//     * @throws Exception
//     */
//    public function refundOrderInventory(Varien_Event_Observer $observer)
//    {
//        $creditmemo = $observer->getEvent()->getCreditmemo();
//        foreach ($creditmemo->getAllItems() as $item) {
//            $recurringProduct = $this->_productModel->loadByAttribute(
//                self::PARENT_ATTRIBUTE_CODE,
//                $item->getProductId()
//            );
//            if ($recurringProduct->getId()) {
//                $stock = $this->_stockModel->loadByProduct(
//                    $recurringProduct,
//                    $this->_websideId
//                );
//                $qty = $stock->getQty();
//                $stock->setQty($qty + $item->getQty());
//                $stock->save();
//            }
//        }
//    }
}
