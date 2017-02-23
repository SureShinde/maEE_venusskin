<?php
/**
 * Multi-Location Inventory
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitquantitymanager
 * @version      10.1.17
 * @license:     zYhgg6AVjUSz3lP2TXyFUlL5wRBeGAYVQuE9Sq0OpU
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */

class Aitoc_Aitquantitymanager_Block_Rewrite_CheckoutCartCrosssell extends Mage_Checkout_Block_Cart_Crosssell
{
    
    protected function _getCollection()
    {
        $collection = Mage::getModel('catalog/product_link')->useCrossSellLinks()
            ->getProductCollection()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addStoreFilter()
            ->setPageSize($this->_maxItemCount);
        $this->_addProductAttributesAndPrices($collection);

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        //Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

        return $collection;
    }
}