<?php

class Snowdog_OrderGrid_Model_Observer
{
    public function salesQuoteItemSetCustomAttribute(Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setProductSellType($product->getProductSellType());
    }
}
