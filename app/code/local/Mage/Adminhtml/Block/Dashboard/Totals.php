<?php
class Mage_Adminhtml_Block_Dashboard_Totals extends Mage_Adminhtml_Block_Dashboard_Bar {
	const TRANSACTION_STATUS_COMPLETED = 1;

	protected function _construct() {
		parent::_construct();
		$this->setTemplate('dashboard/totalbar.phtml');
	}

	protected function _prepareLayout() {
		if (!Mage::helper('core')->isModuleEnabled('Mage_Reports')) {
			return $this;
		}
		$isFilter = $this->getRequest()->getParam('store') || $this->getRequest()->getParam('website') || $this->getRequest()->getParam('group');
		$period   = $this->getRequest()->getParam('period', '0');

		$dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);

		$commissionCollection = Mage::getModel('affiliateplus/transaction')->getCollection();
		$commissionCollection->addFieldToFilter('main_table.status', array('eq' => self::TRANSACTION_STATUS_COMPLETED))
		                     ->addExpressionFieldToSelect('sum_commission', 'SUM({{main_table.commission}})', 'main_table.commission')
		                     ->addExpressionFieldToSelect('revenue', 'SUM({{main_table.total_amount}})', 'main_table.total_amount')
		                     ->addExpressionFieldToSelect('quantity', 'COUNT({{main_table.transaction_id}})', 'main_table.transaction_id');

		if ($period != 'lifetime') {
			$commissionCollection->addFieldToFilter('created_time', array('lteq' => $dates['to']))
			                     ->addFieldToFilter('created_time', array('gteq' => $dates['from']));
		}

		$commissionCollection->load();

		$commissions = $commissionCollection->getFirstItem();

		$this->addTotal($this->__('Revenue'), $commissions->getRevenue());
		$this->addTotal($this->__('Commission Earned'), $commissions->getSumCommission());
		$this->addTotal($this->__('# of Completed Orders'), $commissions->getQuantity() * 1, true);
	}
}
