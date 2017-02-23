<?php
class Venus_Theme_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Dashboard {
	protected function _prepareLayout() {
		$this->setChild(
			'date',
			$this->getLayout()->createBlock('adminhtml/dashboard_monthly_date')
		);

		$this->setChild(
			'monthly_totals',
			$this->getLayout()->createBlock('adminhtml/dashboard_totals')
		);

		parent::_prepareLayout();
	}
}
