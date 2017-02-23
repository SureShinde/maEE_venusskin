<?php
class Inspiratica_Banner_Model_Banner extends Mage_Core_Model_Abstract {
	public function _construct() {
		parent::_construct();
		$this->_init('banner/banner');
	}


	function getCollection1($grid_data) {
		$collection = $grid_data->getCollection();

		foreach ($collection as $item_data) {
			if ($item_data['impmade'] > 0) {
				$item_data['clicks'] = $item_data['clicks'] . " - " . number_format(((100.0 * $item_data['clicks'] / $item_data['impmade'])), 2, '.', '') . " %";
			} else {
				$item_data['clicks'] = "0 - 0%";
			}

			if ($item_data['imptotal'] == 0) {
				$item_data['imptotal'] = $item_data['impmade'] . " of unlimited";
			} else {
				$item_data['imptotal'] = $item_data['impmade'] . " of " . $item_data['imptotal'];
			}
		}
		return $collection;
	}

	public function click() {
		$this->setData("clicks", $this->getData("clicks") + 1);
		try {
			$this->save();
		} catch (Exception $e) {

		}
	}
}
