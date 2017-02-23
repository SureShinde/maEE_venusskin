<?php
class Venus_Theme_Block_Catalog_Product_Featured extends Mage_Catalog_Block_Product_Abstract {
	protected function _construct() {
		parent::_construct();

		$this->addData(array('cache_lifetime' => null));
		$this->addCacheTag(Mage_Catalog_Model_Product::CACHE_TAG);
	}

	public function getCacheKeyInfo() {
		return array(
			'CATALOG_PRODUCT_FEATURED',
			Mage::app()->getStore()->getId(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::getSingleton('customer/session')->getCustomerGroupId(),
			'template' => $this->getTemplate(),
			$this->getProductsCount()
		);
	}

	protected function _beforeToHtml() {
		if (!$this->getChild('featured_list')) {
			$this->setChild(
				'featured_list',
				$this->getLayout()->createBlock(
					'theme/catalog_product_featured_list',
					$this->getNameInLayout() . '.featured_list',
					array('template' => 'catalog/product/carousel.phtml')
				)
			);
		}

		return parent::_beforeToHtml();
	}
}
