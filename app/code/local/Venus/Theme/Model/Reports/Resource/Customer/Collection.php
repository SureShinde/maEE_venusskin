<?php
class Venus_Theme_Model_Reports_Resource_Customer_Collection extends Mage_Reports_Model_Resource_Customer_Collection {
	public function filterByNoOrders() {
		$this->getSelect()
		     ->joinLeft(
			     array('orders' => $this->getTable('sales/order')),
			     "orders.customer_id = e.entity_id",
			     array()
		     )
		     ->where('orders.customer_id IS NULL');

		return $this;
	}

	public function filterByMonthlySelection($dates) {
		$this->addAttributeToFilter('created_at', array('lteq' => $dates['to']))
		     ->addAttributeToFilter('created_at', array('gteq' => $dates['from']));

		return $this;
	}
}
