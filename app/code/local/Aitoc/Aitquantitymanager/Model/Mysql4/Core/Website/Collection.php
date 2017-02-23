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

class Aitoc_Aitquantitymanager_Model_Mysql4_Core_Website_Collection extends Mage_Core_Model_Mysql4_Website_Collection
{
    public function load($printQuery = false, $logQuery = false)
    {
// start aitoc code 
       
        $this->getSelect()->where('main_table.code != "aitoccode" ');

// finish aitoc code

        parent::load($printQuery, $logQuery);
        return $this;
    }

}