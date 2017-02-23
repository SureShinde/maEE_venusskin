<?php

require_once('../app/Mage.php');
umask(0);
Mage::app();
$collection = Mage::getModel('sales/order_item')->getCollection();

foreach ($collection as $item) {
    $productId = $item->getProductId();
    $value = Mage::getResourceModel('catalog/product')->getAttributeRawValue($productId, 'product_sell_type', 0);
    $item->setProductSellType($value);
    $item->save();
}
echo 'done';