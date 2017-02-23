<?php
class Inspiratica_Banner_Model_Selectblock extends Varien_Object {
	static public function getOptionArray() {
		$block = Mage::getSingleton('banner/block')->getCollection();

		$data = array();
		foreach ($block as $b) {
			$data[] = array(
				'value' => $b->getId(),
				'label' => $b->getTitle(),
			);
		}
		return $data;
	}

	static public function getOptionHash() {
		$block = Mage::getSingleton('banner/block')->getCollection();
		$data  = array();
		foreach ($block as $b) {
			$data[$b->getId()] = $b->getTitle();
		}
		return $data;
	}
}
