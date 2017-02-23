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


class Aitoc_Aitquantitymanager_Block_Rewrite_AdminCatalogProductEditActionAttributeTabInventory extends Mage_Adminhtml_Block_Catalog_Product_Edit_Action_Attribute_Tab_Inventory
{
    
// start aitoc
    public function isDefaultWebsite()
    {
        $iWebsiteId = 0;
        
        if ($store = $this->getRequest()->getParam('store')) 
        {
            $iWebsiteId = Mage::app()->getStore($store)->getWebsiteId();
        }
        
        if (!$iWebsiteId) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
// finish aitoc

}