<?php
class Venus_Theme_Block_Catalog_Product_Featured_List extends Mage_Catalog_Block_Product_Abstract {

	const SHOW_TOP_ONLY_DEFAULT  = false;
	const DEFAULT_PRODUCTS_COUNT = 10;

	protected $_productsCount;
	protected $_showTopOnly;

	protected function _construct() {
		parent::_construct();

		$this->addColumnCountLayoutDepend('empty', 4)
		     ->addColumnCountLayoutDepend('one_column', 4)
		     ->addColumnCountLayoutDepend('two_columns_left', 4)
		     ->addColumnCountLayoutDepend('two_columns_right', 4)
		     ->addColumnCountLayoutDepend('three_columns', 3);

		$this->addData(array('cache_lifetime' => null));
		$this->addCacheTag(Mage_Catalog_Model_Product::CACHE_TAG);
	}

	/**
	 * Get Key pieces for caching block content
	 *
	 * @return array
	 */
	public function getCacheKeyInfo() {
		return array(
			'CATALOG_PRODUCT_FEATURED_LIST',
			Mage::app()->getStore()->getId(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::getSingleton('customer/session')->getCustomerGroupId(),
			'template' => $this->getTemplate(),
			$this->getProductsCount(),
		);
	}

	/**
	 * Prepare and return product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
	 */
	protected function _getProductCollection() {
		/**
		 * @var $collection Mage_Catalog_Model_Resource_Product_Collection
		 */
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

		$collection = $this->_addProductAttributesAndPrices($collection)
		                   ->addStoreFilter()
		                   ->addAttributeToFilter('featured', true)
		                   ->setPageSize($this->getProductsCount())
		                   ->setCurPage(1);

		return $collection;
	}

	/**
	 * Prepare collection with new products
	 *
	 * @return Mage_Core_Block_Abstract
	 */
	protected function _beforeToHtml() {
		$this->setProductCollection($this->_getProductCollection());

		Mage::dispatchEvent('catalog_block_product_list_collection', array('collection' => $this->getProductCollection()));

		if (!$this->getChild('catalog.product.item')) {
			$this->setChild(
			     'catalog.product.item',
			     $this->getLayout()->createBlock(
			          'enterprise_targetrule/catalog_product_item',
			          $this->getNameInLayout() . '.item',
			          array('template' => 'catalog/product/item/grid.phtml')
			     )
			);
		}

		return parent::_beforeToHtml();
	}

	/**
	 * Set how much product should be displayed at once.
	 *
	 * @param $count
	 *
	 * @return Mage_Catalog_Block_Product_New
	 */
	public function setProductsCount($count) {
		$this->_productsCount = $count;

		return $this;
	}

	/**
	 * Get how much products should be displayed at once.
	 *
	 * @return int
	 */
	public function getProductsCount() {
		if (null === $this->_productsCount) {
			$this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
		}

		return $this->_productsCount;
	}

	public function setShowTopOnly($value) {
		$this->_showTopOnly = $value;

		return $this;
	}

	public function getShowTopOnly() {
		if ($this->_showTopOnly === null) {
			$this->_showTopOnly = self::SHOW_TOP_ONLY_DEFAULT;
		}

		return $this->_showTopOnly;
	}
}
