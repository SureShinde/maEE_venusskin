<?php
class Venus_Theme_Model_Sales_Quote_Address_Total_Nominal extends Mage_Sales_Model_Quote_Address_Total_Nominal {
	public function collect(Mage_Sales_Model_Quote_Address $address) {
		$collector = Mage::getSingleton(
			'sales/quote_address_total_nominal_collector',
			array('store' => $address->getQuote()->getStore())
		);

		foreach ($collector->getCollectors() as $model) {
			$model->collect($address);
		}

		foreach ($address->getAllNominalItems() as $item) {
			$rowTotal     = 0;
			$baseRowTotal = 0;
			$totalDetails = array();
			foreach ($collector->getCollectors() as $model) {
				$itemRowTotal = $model->getItemRowTotal($item);
				if ($model->getIsItemRowTotalCompoundable($item)) {
					$rowTotal += $itemRowTotal;
					$baseRowTotal += $model->getItemBaseRowTotal($item);
					$isCompounded = true;
				} else {
					$isCompounded = false;
				}
				if (((float)$itemRowTotal > 0.001 || (float)$itemRowTotal < -0.001) && $label = $model->getLabel()) {
					$totalDetails[] = new Varien_Object(
						array(
							'label'         => $label,
							'amount'        => abs($itemRowTotal),
							'is_compounded' => $isCompounded,
						)
					);
				}
			}
			$item->setNominalRowTotal($rowTotal);
			$item->setBaseNominalRowTotal($baseRowTotal);
			$item->setNominalTotalDetails($totalDetails);
		}

		return $this;
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address) {
		$items = $address->getAllNominalItems();

		if ($items) {
			$address->addTotal(
				array(
					'code'  => $this->getCode(),
					'title' => Mage::helper('sales')->__('Nominal Items'),
					'items' => $items,
					'area'  => 'nominal'
				)
			);
		}

		return $this;
	}
}
