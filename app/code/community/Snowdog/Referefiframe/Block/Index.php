<?php

/**
 * Class Snowdog_Referefiframe_Block_Index
 */
class Snowdog_Referefiframe_Block_Index extends Mage_Core_Block_Template
{
    const XML_PATH_CATEGORY_ID = 'affiliate_iframe/general/category_id';

    /**
     * Get product from category
     */
    public function getProducts()
    {
        $categoryId = $this->_getCategoryId();
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            return $category->getProductCollection()
                ->addAttributeToSelect(array('name', 'price'))
                ->load();
        } else {
            return null;
        }
    }

    /**
     * Get category id from config data
     *
     * @return mixed
     */
    protected function _getCategoryId()
    {
        $storeId = Mage::app()->getStore()->getStoreId();

        return Mage::getStoreConfig(self::XML_PATH_CATEGORY_ID, $storeId);
    }

    /**
     * Get product url with affiliate code
     *
     * @param $productUrl
     */
    public function getProductReffUrl($productId)
    {
        return Mage::getUrl(
            'snowreferefiframe/index/redirect',
            array(
                'id' => $productId,
                'acc' => $this->getRequest()->getParam('acc')
            )
        );
    }
}