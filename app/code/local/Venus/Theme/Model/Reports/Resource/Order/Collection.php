<?php
class Venus_Theme_Model_Reports_Resource_Order_Collection extends Mage_Reports_Model_Resource_Order_Collection {
	public function addCreateAtPeriodFilterForMonthly($dates) {
		if ($this->isLive()) {
			$fieldToFilter = 'created_at';
		} else {
			$fieldToFilter = 'period';
		}

		$this->addFieldToFilter($fieldToFilter, array('from' => $dates['from'], 'to' => $dates['to']));

		return $this;
	}

	public function addDateFilteredOrdersCount($dates) {
		$this->addFieldToFilter('state', array('neq' => Mage_Sales_Model_Order::STATE_CANCELED))
		     ->addAttributeToFilter('created_at', array('lteq' => $dates['to']))
		     ->addAttributeToFilter('created_at', array('gteq' => $dates['from']));
		$this->getSelect()
		     ->columns(array('orders_count' => 'COUNT(main_table.entity_id)'));

		return $this;
	}
}
