<?php
class Mage_Adminhtml_Block_Dashboard_Tab_Customers_Inactive extends Mage_Adminhtml_Block_Dashboard_Grid {
	const DEFAULT_PAGE_SIZE = 10;

	public function __construct() {
		parent::__construct();
		$this->setId('customersInactiveGrid');
	}

	protected function _prepareCollection() {
		/* @var $collection Venus_Theme_Model_Reports_Resource_Customer_Collection */
		$collection = Mage::getResourceModel('reports/customer_collection');
		$collection->addCustomerName()
		           ->filterByNoOrders()
		           ->setPageSize(self::DEFAULT_PAGE_SIZE);

		$storeFilter = 0;
		if ($this->getParam('store')) {
			$collection->addAttributeToFilter('store_id', $this->getParam('store'));
			$storeFilter = 1;
		} else if ($this->getParam('website')) {
			$storeIds = Mage::app()->getWebsite($this->getParam('website'))->getStoreIds();
			$collection->addAttributeToFilter('store_id', array('in' => $storeIds));
		} else if ($this->getParam('group')) {
			$storeIds = Mage::app()->getGroup($this->getParam('group'))->getStoreIds();
			$collection->addAttributeToFilter('store_id', array('in' => $storeIds));
		}

		$period = $this->getRequest()->getParam('period', '0');
		if ($period != 'lifetime') {
			$dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);
			$collection->orderByCustomerRegistration('asc')
			           ->addAttributeToFilter('created_at', array('lteq' => $dates['to']))
			           ->addAttributeToFilter('created_at', array('gteq' => $dates['from']));
		}

		$this->setCollection($collection);

		return $this;
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'name', array(
				'header'   => $this->__('Customer Name'),
				'sortable' => false,
				'index'    => 'name'
			)
		);

		$this->addColumn(
			'customer_since', array(
				'header'    => Mage::helper('customer')->__('Registration Date'),
				'type'      => 'datetime',
				'index'     => 'created_at',
				'gmtoffset' => true
			)
		);

		$this->setFilterVisibility(false);
		$this->setPagerVisibility(false);

		return parent::_prepareColumns();
	}

	public function getRowUrl($row) {
		return $this->getUrl('*/customer/edit', array('id' => $row->getEntityId()));
	}
}
