<?php
class Mage_Adminhtml_Block_Dashboard_Tab_Products_Ordered extends Mage_Adminhtml_Block_Dashboard_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('productsOrderedGrid');
	}

	protected function _prepareCollection() {
		if (!Mage::helper('core')->isModuleEnabled('Mage_Sales')) {
			return $this;
		}
		if ($this->getParam('website')) {
			$storeIds = Mage::app()->getWebsite($this->getParam('website'))->getStoreIds();
			$storeId  = array_pop($storeIds);
		} else if ($this->getParam('group')) {
			$storeIds = Mage::app()->getGroup($this->getParam('group'))->getStoreIds();
			$storeId  = array_pop($storeIds);
		} else {
			$storeId = (int)$this->getParam('store');
		}

		/** @var Venus_Theme_Model_Sales_Resource_Report_Bestsellers_Collection $collection */
		$collection = Mage::getResourceModel('sales/report_bestsellers_collection');
		$collection->setModel('catalog/product')
		           ->addStoreFilter($storeId);

		$period = $this->getRequest()->getParam('period', '0');
		if ($period != 'lifetime') {
			$dates = Mage::helper('theme')->getDateRangeForMonthly($period, 0, 0, false);
			$collection->addFieldToFilter('period', array('lteq' => $dates['to']))
			           ->addFieldToFilter('period', array('gteq' => $dates['from']));
		}

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'name', array(
				'header'   => Mage::helper('sales')->__('Product Name'),
				'sortable' => false,
				'index'    => 'product_name'
			)
		);

		$this->addColumn(
			'price', array(
				'header'        => Mage::helper('sales')->__('Price'),
				'width'         => '120px',
				'type'          => 'currency',
				'currency_code' => (string)Mage::app()->getStore((int)$this->getParam('store'))->getBaseCurrencyCode(),
				'sortable'      => false,
				'index'         => 'product_price'
			)
		);

		$this->addColumn(
			'ordered_qty', array(
				'header'   => Mage::helper('sales')->__('# of Orders Placed'),
				'width'    => '120px',
				'align'    => 'right',
				'sortable' => false,
				'index'    => 'qty_ordered',
				'type'     => 'number'
			)
		);

		$this->setFilterVisibility(false);
		$this->setPagerVisibility(false);

		return parent::_prepareColumns();
	}

	public function getRowUrl($row) {
		$productId = $row->getProductId();

		if (!$productId) {
			return '';
		}

		$params = array('id' => $productId);
		if ($this->getRequest()->getParam('store')) {
			$params['store'] = $this->getRequest()->getParam('store');
		}

		return $this->getUrl('*/catalog_product/edit', $params);
	}
}
