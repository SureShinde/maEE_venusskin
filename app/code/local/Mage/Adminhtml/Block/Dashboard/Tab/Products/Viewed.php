<?php
class Mage_Adminhtml_Block_Dashboard_Tab_Products_Viewed extends Mage_Adminhtml_Block_Dashboard_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('productsReviewedGrid');
	}

	protected function _prepareCollection() {
		if ($this->getParam('website')) {
			$storeIds = Mage::app()->getWebsite($this->getParam('website'))->getStoreIds();
			$storeId  = array_pop($storeIds);
		} else if ($this->getParam('group')) {
			$storeIds = Mage::app()->getGroup($this->getParam('group'))->getStoreIds();
			$storeId  = array_pop($storeIds);
		} else {
			$storeId = (int)$this->getParam('store');
		}

		$collection = Mage::getResourceModel('reports/product_collection')
		                  ->addAttributeToSelect('*')
		                  ->setStoreId($storeId)
		                  ->addStoreFilter($storeId);

		$period = $this->getRequest()->getParam('period', '0');
		if ($period != 'lifetime') {
			$dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);
			$collection->addViewsCount($dates['from'], $dates['to']);
		}

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'name', array(
				'header'   => Mage::helper('reports')->__('Product Name'),
				'sortable' => false,
				'index'    => 'name'
			)
		);

		$this->addColumn(
			'price', array(
				'header'        => Mage::helper('reports')->__('Price'),
				'width'         => '120px',
				'type'          => 'currency',
				'currency_code' => (string)Mage::app()->getStore((int)$this->getParam('store'))->getBaseCurrencyCode(),
				'sortable'      => false,
				'index'         => 'price'
			)
		);

		$this->addColumn(
			'views', array(
				'header'   => Mage::helper('reports')->__('Number of Views'),
				'width'    => '120px',
				'align'    => 'right',
				'sortable' => false,
				'index'    => 'views'
			)
		);

		$this->setFilterVisibility(false);
		$this->setPagerVisibility(false);

		return parent::_prepareColumns();
	}

	public function getRowUrl($row) {
		$params = array('id' => $row->getId());
		if ($this->getRequest()->getParam('store')) {
			$params['store'] = $this->getRequest()->getParam('store');
		}

		return $this->getUrl('*/catalog_product/edit', $params);
	}
}
