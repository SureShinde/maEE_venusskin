<?php

class Etheme_Idstore_Block_Product_Ribbons extends Mage_Catalog_Block_Product_Abstract {
    
    
    public function hasSaleRibbon() {
        
        $_product = $this->getProduct();
        $specialPrice = number_format($_product->getFinalPrice(), 2);
        $regularPrice = number_format($_product->getPrice(), 2);
        if ($specialPrice != $regularPrice)
            return true;
        else 
            return false;
    }
    
    public function hasNewRibbon() {
        $_product = $this->getProduct();
        $now = date("Y-m-d H:m:s");   
        $newFromDate = $_product->getNewsFromDate();
        $newToDate = $_product->getNewsToDate();                                               
        if ($newFromDate < $now && $newToDate > $now)
            return true;
        else
            return false;
    }
}