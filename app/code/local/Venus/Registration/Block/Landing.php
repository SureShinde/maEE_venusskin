<?php
class Venus_Registration_Block_Landing extends Mage_Core_Block_Template {
	protected function _construct() {
		parent::_construct();
	}

	public function getPhysicianLicenseProduct() {
		/** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->addFieldToFilter('physicians_only', array('eq' => true));

		return count($collection) > 0 ? $collection->getFirstItem() : null;
	}

	public function getAddToCartUrl($product, $additional = array()) {
		if (!$product->getTypeInstance(true)->hasRequiredOptions($product)) {
			return $this->helper('checkout/cart')->getAddUrl($product, $additional);
		}
		$additional = array_merge(
			$additional,
			array(Mage_Core_Model_Url::FORM_KEY => Mage::getSingleton('core/session')->getFormKey())
		);
		if (!isset($additional['_escape'])) {
			$additional['_escape'] = true;
		}
		if (!isset($additional['_query'])) {
			$additional['_query'] = array();
		}
		$additional['_query']['options'] = 'cart';

		return $this->getProductUrl($product, $additional);
	}

	public function getProductUrl($product, $additional = array()) {
		if ($this->hasProductUrl($product)) {
			if (!isset($additional['_escape'])) {
				$additional['_escape'] = true;
			}

			return $product->getUrlModel()->getUrl($product, $additional);
		}

		return '#';
	}
}
