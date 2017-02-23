<?php

class Snowdog_HideProduct_Block_Catalog_Product_Kit_List extends Venus_Theme_Block_Catalog_Product_Kit_List
{
    protected function _getProductCollection()
    {
        /**
         * @var $collection Mage_Catalog_Model_Resource_Product_Collection
         */
        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();

        $hideProductCollection = Mage::getModel('hideproduct/hideproduct')
            ->getCollection()
            ->addFieldToFilter('customer_group', $groupId);

        $removeProductData = $hideProductCollection->getData();

        $removeProductArr = array_column($removeProductData, 'product_id');

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection);
        if ($this->getIsKit()) {
            $collection->addAttributeToFilter(self::KIT_ATTRIBUTE, $this->getIsKit())
                ->addStoreFilter();
        }

        if ($removeProductArr) {
            $collection->addAttributeToFilter('entity_id', array('nin' => $removeProductArr));
        }

        if ($this->getProductIds()) {
            $ids = explode(',', $this->getProductIds());
            $collection->addAttributeToFilter(self::PRODUCT_IDS_ATTRIBUTE, array('in' => $ids));
            //sort in id order set in block
            $ids = array_map('intval', $ids);
            $collection->getSelect()->order("find_in_set(e.entity_id,'" . implode(',', $ids) . "')");
        }

        if ($this->getReleaseYear()) {
            $collection->addAttributeToFilter(self::RELEASE_YEAR_ATTRIBUTE, $this->getReleaseYear());
        }

        if ($this->getCustomerGroupId()) {
            $groupArray = explode(',', $this->getCustomerGroupId());
            if (!in_array($groupId, $groupArray)) {
                //clear collection
                $collection->addFieldToFilter('entity_id', 0);
                return $collection;
            }
        }

        return $collection;
    }

    public function getIsKit()
    {
        if ($this->_isKit === null) {
            return null;
        }

        return $this->_isKit;
    }
}