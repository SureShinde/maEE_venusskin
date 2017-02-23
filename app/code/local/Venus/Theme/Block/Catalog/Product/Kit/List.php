<?php
class Venus_Theme_Block_Catalog_Product_Kit_List extends Venus_Theme_Block_Catalog_Product_List 
{
	const KIT_ATTRIBUTE             = 'is_kit';
	const RELEASE_YEAR_ATTRIBUTE    = 'release_year';
	const PRODUCT_IDS_ATTRIBUTE     = 'entity_id';
	const ATTRIBUTE_VALUE_DEFAULT   = true;

	protected $_isKit;

	public function getCacheKeyInfo() {
		return array(
			'CATALOG_PRODUCT_KIT_LIST',
			Mage::app()->getStore()->getId(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::getSingleton('customer/session')->getCustomerGroupId(),
			'template' => $this->getTemplate(),
		    $this->getIsKit()
		);
	}

	protected function _getProductCollection() {
		/**
		 * @var $collection Mage_Catalog_Model_Resource_Product_Collection
		 */

		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

		$collection = $this->_addProductAttributesAndPrices($collection)
		                   ->addAttributeToFilter(self::KIT_ATTRIBUTE, $this->getIsKit())
		                   ->addStoreFilter();

                if($this->getProductIds()){
                   $ids = explode(',', $this->getProductIds());
                   $collection->addAttributeToFilter(self::PRODUCT_IDS_ATTRIBUTE, array('in' => $ids));
                   //sort in id order set in block
                   $ids = array_map('intval', $ids);
                   $collection->getSelect()->order("find_in_set(e.entity_id,'".implode(',',$ids)."')");
                } 
                
                if($this->getReleaseYear()){
                   $collection->addAttributeToFilter(self::RELEASE_YEAR_ATTRIBUTE, $this->getReleaseYear());
                }
                

		return $collection;
	}

	public function setIsKit($value) {
		$this->_isKit = $value;
	}

	public function getIsKit() {
		if ($this->_isKit === null) {
			$this->_isKit = self::ATTRIBUTE_VALUE_DEFAULT;
		}

		return $this->_isKit;
	}
}
