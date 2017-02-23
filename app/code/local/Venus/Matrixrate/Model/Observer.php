<?php
class Venus_Matrixrate_Model_Observer {
	public function recurringProfileBeforeBilled(Varien_Event_Observer $observer) {
		/**
		 * @var Mage_Sales_Model_Recurring_Profile              $profile
		 * @var Webshopapps_Matrixrate_Model_Carrier_Matrixrate $carrier
		 */

		$profile = $observer->getEvent()->getProfile();

		$store   = Mage::app()->getStore($profile->getStoreId());
		$carrier = Mage::getModel('matrixrate/carrier_matrixrate')->setStore($store);

		if ($carrier->isActive()) {
			$childOrderIds = $profile->getChildOrderIds();
			$childOrderIds = array_filter(
				$childOrderIds,
				function ($id) {
					return !empty($id) && $id != -1;
				}
			);

			if (count($childOrderIds) >= 1) {
				$rate = Mage::helper('venus_matrixrate')->getRecurringShippingMethod($store);

				if ($rate->getId()) {
					$profile->setOrderInfo($this->_updateShippingMethod($rate, $carrier, $profile->getOrderInfo()));
					$profile->setShippingAddressInfo($this->_updateShippingMethod($rate, $carrier, $profile->getShippingAddressInfo()));

					/**
					 * @var Mage_Sales_Model_Quote      $quote
					 * @var Mage_Sales_Model_Quote_Item $item
					 */

					$quote = Mage::getModel('sales/quote')->addData($profile->getOrderInfo());

					$item = Mage::getModel('sales/quote_item')->addData($profile->getOrderItemInfo())->setId(null);

					$quote->removeAllItems()->addItem($item);

					$quote->removeAllAddresses()->setShippingAddress(Mage::getModel('sales/quote_address')->setData($profile->getShippingAddressInfo()));

					$quote->getShippingAddress()
					      ->unsetData('cached_items_all')
					      ->unsetData('cached_items_nominal')
					      ->unsetData('cached_items_nonnominal');

					$quote->setTotalsCollectedFlag(false)->collectTotals();

					$profile->setShippingAmount($item->getShippingAmount());
					$profile->setTaxAmount($item->getTaxAmount());
				}
			}
		}

		return $this;
	}

	protected function _updateShippingMethod($rate, $carrier, $info) {
		$info['shipping_method']      = 'matrixrate_matrixrate_' . $rate->getId();
		$info['shipping_description'] = $carrier->getConfigData('title') . ' - ' . $rate->getDeliveryType();

		return $info;
	}
}
