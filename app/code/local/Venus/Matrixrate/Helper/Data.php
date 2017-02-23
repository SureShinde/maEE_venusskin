<?php
class Venus_Matrixrate_Helper_Data extends Mage_Core_Helper_Abstract {
	const XML_PATH_RECURRING_SHIPPING_METHOD = 'carriers/matrixrate/recurring_shipping_method';

	public function getRecurringShippingMethod($store = null) {
		$rateId = Mage::getStoreConfig(self::XML_PATH_RECURRING_SHIPPING_METHOD, $store);

		return Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate_collection')->addFieldToFilter('pk', $rateId)->getFirstItem();
	}
}
