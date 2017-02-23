<?php
class Venus_Theme_Block_Sales_Adminhtml_Recurring_Profile_Grid extends Mage_Sales_Block_Adminhtml_Recurring_Profile_Grid {
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('sales/recurring_profile_collection');

		$collection->getSelect()
		           ->joinLeft(array('apa' => $collection->getTable('affiliateplus/account')), 'apa.customer_id = main_table.customer_id', 'name');

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	public function setCollection($collection) {
		if (!$this->_collection) {
			$this->_collection = $collection;
		}
	}

	protected function _prepareColumns() {
		$profile = Mage::getModel('sales/recurring_profile');

		$this->addColumn(
			'reference_id', array(
				              'header'          => $profile->getFieldLabel('reference_id'),
				              'index'           => 'reference_id',
				              'html_decorators' => array('nobr'),
				              'width'           => 1,
			              )
		);

		$this->addColumn(
			'name', array(
				      'header'          => Mage::helper('sales')->__('Patient Name'),
				      'index'           => 'name',
				      'html_decorators' => array('nobr')
			      )
		);

		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn(
				'store_id', array(
					          'header'          => Mage::helper('adminhtml')->__('Store'),
					          'index'           => 'store_id',
					          'type'            => 'store',
					          'store_view'      => true,
					          'display_deleted' => true,
				          )
			);
		}

		$this->addColumn(
			'state', array(
				       'header'          => $profile->getFieldLabel('state'),
				       'index'           => 'state',
				       'type'            => 'options',
				       'options'         => $profile->getAllStates(),
				       'html_decorators' => array('nobr'),
				       'width'           => 1,
			       )
		);

		$this->addColumn(
			'created_at', array(
				            'header'          => $profile->getFieldLabel('created_at'),
				            'index'           => 'created_at',
				            'type'            => 'datetime',
				            'html_decorators' => array('nobr'),
				            'width'           => 1,
			            )
		);

		$this->addColumn(
			'updated_at', array(
				            'header'          => $profile->getFieldLabel('updated_at'),
				            'index'           => 'updated_at',
				            'type'            => 'datetime',
				            'html_decorators' => array('nobr'),
				            'width'           => 1,
			            )
		);

		$methods = array();
		foreach (Mage::helper('payment')->getRecurringProfileMethods() as $method) {
			$methods[$method->getCode()] = $method->getTitle();
		}

		$this->addColumn(
			'method_code', array(
				             'header'  => $profile->getFieldLabel('method_code'),
				             'index'   => 'method_code',
				             'type'    => 'options',
				             'options' => $methods,
			             )
		);

		$this->addColumn(
			'schedule_description', array(
				                      'header' => $profile->getFieldLabel('schedule_description'),
				                      'index'  => 'schedule_description',
			                      )
		);

		$this->addExportType('*/*/exportCsv', Mage::helper('payment')->__('CSV'));

		return $this;
	}
}
