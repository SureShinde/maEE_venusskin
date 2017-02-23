<?php

class Snowdog_AddToCartType_Model_Observer
{

    public function checkProductType(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $addedProductType = $product->getProductSellType();
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $totalItemsInCart = Mage::helper('checkout/cart')->getItemsCount();

        if ($totalItemsInCart > 0) {
            foreach ($cart->getAllItems() as $item) {
                $productType = $item->getProduct()->getProductSellType();

                if ($addedProductType != $productType && !is_null($productType) && !is_null($addedProductType)) {
                    Mage::throwException(Mage::helper('adminhtml')->__('Product not added to cart. Retail and Backbar products should be placed on separate orders.'));
                }
            }
        }
    }
}