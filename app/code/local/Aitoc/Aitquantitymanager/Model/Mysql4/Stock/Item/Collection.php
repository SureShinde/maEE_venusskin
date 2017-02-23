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
class Aitoc_Aitquantitymanager_Model_Mysql4_Stock_Item_Collection extends Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection
{
    protected function _construct()
    {
///ait/        $this->_init('cataloginventory/stock_item');
        $this->_init('aitquantitymanager/stock_item');   
    }

}