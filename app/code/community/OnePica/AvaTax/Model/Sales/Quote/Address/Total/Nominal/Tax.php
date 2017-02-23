<?php
class OnePica_AvaTax_Model_Sales_Quote_Address_Total_Nominal_Tax extends Mage_Tax_Model_Sales_Total_Quote_Nominal_Tax {
	public function collect(Mage_Sales_Model_Quote_Address $address) {
		Mage_Sales_Model_Quote_Address_Total_Abstract::collect($address);

		$items = $this->_getAddressItems($address);

		if (count($items) > 0) {
			if ($address->getPostcode() && $address->getPostcode() != '-') {
				$store      = $address->getQuote()->getStore();
				$calculator = Mage::getModel('avatax/avatax_estimate');

				$address->setTotalAmount($this->getCode(), 0);
				$address->setBaseTotalAmount($this->getCode(), 0);

				$address->setTaxAmount(0);
				$address->setBaseTaxAmount(0);
				$address->setShippingTaxAmount(0);
				$address->setBaseShippingTaxAmount(0);

				if (Mage::helper('avatax')->isAddressActionable($address->getQuote()->getShippingAddress(), $address->getQuote()->getStoreId())) { // Added check for calculating tax for regions filtered in the admin
					foreach ($items as $item) {
						/**
						 * @var Mage_Sales_Model_Quote_Item $item
						 */

						$item->setAddress($address);

						$baseAmount = $calculator->getItemTax($item);
						$amount     = Mage::app()->getStore()->convertPrice($baseAmount);
						$percent    = $calculator->getItemRate($item);

						if (!$calculator->isProductCalculated($item)) {
							//$this->_addAmount($amount);
							$address->addTotalAmount('tax', $amount);
							//$this->_addBaseAmount($baseAmount);
							$address->addBaseTotalAmount('tax', $baseAmount);
						}

						if ($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_SHIPPING || $address->getUseForShipping()) {
							$itemAddress = clone $address;

							$itemAddress->setId($address->getId());
							$itemAddress->setShippingAmount($item->getShippingAmount());
							$itemAddress->setBaseShippingAmount($item->getBaseShippingAmount());

							$shippingItem = new Varien_Object();
							$shippingItem->setId(Mage::helper('avatax')->getShippingSku($store->getId()));
							$shippingItem->setProductId(Mage::helper('avatax')->getShippingSku($store->getId()));
							$shippingItem->setAddress($itemAddress);

							$baseShippingTax = $calculator->getItemTax($shippingItem);
							$shippingTax     = Mage::app()->getStore()->convertPrice($baseShippingTax);

							$shippingAmt     = $address->getTotalAmount('shipping');
							$baseShippingAmt = $address->getBaseTotalAmount('shipping');

							$address->setShippingTaxAmount($shippingTax);
							$address->setBaseShippingTaxAmount($baseShippingTax);
							$address->setShippingInclTax($shippingAmt + $shippingTax);
							$address->setBaseShippingInclTax($baseShippingAmt + $baseShippingTax);
							$address->setShippingTaxable($shippingTax ? $shippingAmt : 0);
							$address->setBaseShippingTaxable($baseShippingTax ? $baseShippingAmt : 0);
							$address->setIsShippingInclTax(false);

							//$this->_addAmount($shippingTax);
							$address->addTotalAmount('tax', $shippingTax);
							//$this->_addBaseAmount($baseShippingTax);
							$address->addBaseTotalAmount('tax', $baseShippingTax);

							$amount += $shippingTax;
							$baseAmount += $baseShippingTax;
						}

						$item->setTaxAmount($amount);
						$item->setBaseTaxAmount($baseAmount);
						$item->setTaxPercent($percent);

						$item->setPriceInclTax($item->getPrice() + ($amount / $item->getQty()));
						$item->setBasePriceInclTax($item->getBasePrice() + ($baseAmount / $item->getQty()));
						$item->setRowTotalInclTax($item->getRowTotal() + $amount);
						$item->setBaseRowTotalInclTax($item->getBaseRowTotal() + $baseAmount);
					}
				}
			}
		}

		return $this;
	}
}
