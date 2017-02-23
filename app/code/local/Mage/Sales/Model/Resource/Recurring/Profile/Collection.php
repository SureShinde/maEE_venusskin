<?php
class Mage_Sales_Model_Resource_Recurring_Profile_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	protected $_eventPrefix = 'sales_recurring_profile_collection';
	protected $_eventObject = 'recurring_profile_collection';
	protected $_startOfYear = null;
	protected $_endOfYear   = null;
	protected $_daysInYear  = null;

	protected function _construct() {
		$this->_init('sales/recurring_profile');
		$this->_initDates();
	}

	protected function _initDates() {
		$this->_startOfYear = Mage::app()->getLocale()->date()->setDayOfYear(1)->setTime(0);
		$end                = clone($this->_startOfYear);
		$this->_endOfYear   = $end->addYear(1);
		$this->_daysInYear  = $this->_endOfYear->subDay(1)->toArray()['dayofyear'] + 1;
	}

	public function getDateInfoAsArray() {
		return array(
			'start_date'   => $this->_startOfYear,
			'end_date'     => $this->_endOfYear,
			'days_in_year' => $this->_daysInYear,
		);
	}

	public function addNumberOfPurchasesInYear() {
		$this->getSelect()
		     ->columns(array('estimated_purchases' => new Zend_Db_Expr("CAST($this->_daysInYear / period_frequency AS SIGNED)")));

		return $this;
	}

	public function addEstimatedRevenue() {
		$this->getSelect()
		     ->columns(array('estimated_revenue' => new Zend_Db_Expr("CAST($this->_daysInYear / period_frequency AS SIGNED) * billing_amount")));

		return $this;
	}

	public function getSelectCountSql() {
		$this->_renderFilters();

		$countSelect = clone $this->getSelect();
		$countSelect->reset(Zend_Db_Select::ORDER);
		$countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
		$countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
		$countSelect->reset(Zend_Db_Select::COLUMNS);

		if (count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
			$countSelect->reset(Zend_Db_Select::GROUP);
			$countSelect->distinct(true);
			$group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
			$countSelect->columns("COUNT(DISTINCT " . implode(", ", $group) . ")");
		} else {
			$countSelect->columns('COUNT(*)');
		}

		return $countSelect;
	}
}
