<?php
class Venus_Theme_Block_Adminhtml_Sales_Order_Processing_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid {
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel($this->_getCollectionClass())->addFieldToFilter('status', 'pending');

		$this->setCollection($collection);

		return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn(
			'real_order_id', array(
				'header' => Mage::helper('sales')->__('Order #'),
				'width'  => '80px',
				'type'   => 'text',
				'index'  => 'increment_id',
			)
		);

		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn(
				'store_id', array(
					'header'          => Mage::helper('sales')->__('Purchased From (Store)'),
					'index'           => 'store_id',
					'type'            => 'store',
					'store_view'      => true,
					'display_deleted' => true,
				)
			);
		}

		$this->addColumn(
			'created_at', array(
				'header' => Mage::helper('sales')->__('Purchased On'),
				'index'  => 'created_at',
				'type'   => 'datetime',
				'width'  => '100px',
			)
		);

		$this->addColumn(
			'billing_name', array(
				'header' => Mage::helper('sales')->__('Bill to Name'),
				'index'  => 'billing_name',
			)
		);

		$this->addColumn(
			'shipping_name', array(
				'header' => Mage::helper('sales')->__('Ship to Name'),
				'index'  => 'shipping_name',
			)
		);

		$this->addColumn(
			'base_grand_total', array(
				'header'   => Mage::helper('sales')->__('G.T. (Base)'),
				'index'    => 'base_grand_total',
				'type'     => 'currency',
				'currency' => 'base_currency_code',
			)
		);

		$this->addColumn(
			'grand_total', array(
				'header'   => Mage::helper('sales')->__('G.T. (Purchased)'),
				'index'    => 'grand_total',
				'type'     => 'currency',
				'currency' => 'order_currency_code',
			)
		);

		$this->addColumn(
			'status', array(
				'header'  => Mage::helper('sales')->__('Status'),
				'index'   => 'status',
				'type'    => 'options',
				'width'   => '70px',
				'filter'  => false,
				'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
			)
		);

		if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
			$this->addColumn(
				'action',
				array(
					'header'    => Mage::helper('sales')->__('Action'),
					'width'     => '50px',
					'type'      => 'action',
					'getter'    => 'getId',
					'actions'   => array(
						array(
							'caption' => Mage::helper('sales')->__('View'),
							'url'     => array('base' => 'adminhtml/sales_order/view'),
							'field'   => 'order_id'
						)
					),
					'filter'    => false,
					'sortable'  => false,
					'index'     => 'stores',
					'is_system' => true,
				)
			);
		}

		//$this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));
		//$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
		//$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

		return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
	}

	public function getRowUrl($row) {
		if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
			return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));
		}

		return false;
	}

	public function getGridUrl() {
		return $this->getUrl('*/*/processingGrid', array('_current' => true));
	}
}
