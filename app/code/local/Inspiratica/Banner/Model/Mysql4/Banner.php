<?php
class Inspiratica_Banner_Model_Mysql4_Banner extends Mage_Core_Model_Mysql4_Abstract {
	public function _construct() {
		$this->_init('banner/banner', 'id');
	}

	public function getListBannerOfBlock($block) {
		try {
			$randomise = $block['sort_method'] == 'RANDOM';
			$today     = Mage::getModel('core/date')->date("Y-m-d H:i:s");
			$select    = $this->_getReadAdapter()->select()
				->from($this->getTable('banner'), array('*', $randomise ? 'RAND() as order' : ''))
				->where('FIND_IN_SET(?, blocks)', $block['id'])
				->where('FIND_IN_SET(?, store_ids) OR FIND_IN_SET(\'0\', store_ids)', Mage::app()->getStore()->getId())
				->where('status = ?', 1)
				->where('startdate <= ?', $today)
				->where('enddate >= ?', $today)
				->limit($block['num_banners'], 0)
				->order("order", "ASC");

			$items = $this->_getReadAdapter()->fetchAll($select);
			$this->impress($items);

			return $items;
		} catch (Exception $e) {
			return null;
		}
	}

	public function impress($list) {
		for ($i = 0; $i < count($list); $i++) {
			$banner = $list[$i];
			$banner['impmade']++;
			$expire = ($banner['impmade'] >= $banner['imptotal']) && ($banner['imptotal'] != 0);
			$write  = $this->_getWriteAdapter();
			$write->beginTransaction();
			$data['impmade'] = $banner['impmade'];
			if ($expire) {
				$data['status'] = '0';
			}
			$write->update($this->getTable("banner"), $data, "id=" . $banner['id']);
			$write->commit();
		}
	}
}
