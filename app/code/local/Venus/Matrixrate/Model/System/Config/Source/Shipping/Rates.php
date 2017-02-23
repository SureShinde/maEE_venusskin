<?php
class Venus_Matrixrate_Model_System_Config_Source_Shipping_Rates {
	const VALUE_NONE = 'none';

	public function toOptionArray() {
		$methods = array(
			array(
				'value' => self::VALUE_NONE,
				'label' => Mage::helper('theme')->__('Use Existing Method')
			)
		);

		$scopeWebsite = $this->getScopeWebsite();

		/**
		 * @var Webshopapps_Matrixrate_Model_Mysql4_Carrier_Matrixrate_Collection $collection
		 */

		$collection = Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate_collection')->setWebsiteFilter($scopeWebsite->getId());

		foreach ($collection as $rate) {
			$methods[] = array(
				'value' => $rate->getId(),
				'label' => $rate->getDeliveryType() . ' [' . $rate->getDestCountry() . ']'
			);
		}

		return $methods;
	}

	public function getScopeWebsite() {
		return Mage::app()->getWebsite(Mage::app()->getRequest()->getParam('website', null));
	}
}
