<?php

class Snowdog_HideProduct_Model_Observer
{
    public function saveHideAttribute(Varien_Event_Observer $observer)
    {
        if ($actionInstance = Mage::app()->getFrontController()->getAction()) {
            $action = $actionInstance->getFullActionName();
            if ($action == 'adminhtml_catalog_product_save') {
                $hideModel = Mage::getModel('hideproduct/hideproduct');
                $product = $observer->getEvent()->getProduct();
                $productId = $product->getId();
                $removeCollection = $hideModel->getCollection()->addFieldToFilter('product_id', $productId);

                foreach ($removeCollection as $item) {
                    $item->delete();
                }

                $customerGroup = $product->getSnowHideGroup();

                foreach ($customerGroup as $group) {
                    $hideModel->setId(null);
                    $hideModel->setProductId($productId);
                    $hideModel->setCustomerGroup($group);
                    $hideModel->save();
                }
            }
        }
    }
}
