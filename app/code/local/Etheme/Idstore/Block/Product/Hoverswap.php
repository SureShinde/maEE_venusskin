<?php
class Etheme_Idstore_Block_Product_Hoverswap extends Mage_Catalog_Block_Product_View_Abstract {
    
    public function getHoverImg() {
        
        $product = $this->getProduct();
        $productId = $product->getId();
        $images = Mage::getModel('catalog/product')->load($productId)->getMediaGalleryImages();

        if ($images->count() > 1) {
            foreach ($images as $image) {
                
                if ($this->htmlEscape($image->getLabel()) == 'back') {
                    return $image;
                }
            }
        }
        else
            return null;
    }
    
}