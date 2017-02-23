<?php
class Venus_Theme_Block_Catalog_Product_Collection extends Mage_Core_Block_Template {
	public function getCollection() {
		/**
		 * @var Mage_Catalog_Model_Resource_Product_Collection $collection
		 */

		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->addAttributeToSelect('name', 'inner')
		           ->addAttributeToFilter('onetime_purchase_variant_id', array('notnull' => true))
		           ->addAttributeToSort('name', Varien_Data_Collection::SORT_ORDER_ASC)
		           ->addUrlRewrite()
		           ->load();

		return $collection;
	}
}
