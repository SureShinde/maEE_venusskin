<?php
class Venus_Theme_Helper_Adminhtml_Dashboard_Data extends Mage_Adminhtml_Helper_Dashboard_Data {
	const LIFETIME_STAT_ARRAY_KEY = 'lifetime';
	const YTD_STAT_ARRAY_KEY      = 'ytd';

	public function getMonthlyReportDates() {
		$dateModel = Mage::getModel('core/date');
		$today     = $dateModel->timestamp(time());
		$dateArray = array();

		$dateArray[self::YTD_STAT_ARRAY_KEY] = $this->__('Year to Date');

		for ($i = 0; $i <= 12; ++$i) {
			$dateArray[] = $dateModel->date('F - Y', strtotime($this->__('-%s month', $i), $today));
		}

		$dateArray[self::LIFETIME_STAT_ARRAY_KEY] = $this->__('Lifetime/Overall');

		return $dateArray;
	}
}
