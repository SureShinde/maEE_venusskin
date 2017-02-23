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

class Aitoc_Aitquantitymanager_Model_Rewrite_FrontCatalogProductIndexerPrice extends Mage_Catalog_Model_Product_Indexer_Price
{
    // overide parent
    protected function _construct()
    {
//        $this->_init('catalog/product_indexer_price');
        $this->_init('aitquantitymanager/frontCatalogResourceEavMysql4ProductIndexerPrice');
    }
}