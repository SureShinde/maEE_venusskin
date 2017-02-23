<?php
class Inspiratica_Banner_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('banner/banner')->getCollection();
		/*$collection->getSelect()->joinLeft(array('table_alias' => $collection->getTable('banner/block')), 'main_table.blocks = table_alias.id', array('block_title' => 'name'));*/
		$this->setCollection($collection);
		return Mage::getModel('banner/banner')->getCollection1(parent::_prepareCollection());
	}

	protected function _prepareColumns() {
		$this->addColumn('id', array(
			'header' => Mage::helper('banner')->__('ID'),
			'align'  => 'right',
			'width'  => '50px',
			'index'  => 'id',
		));

		$this->addColumn('name', array(
			'header' => Mage::helper('banner')->__('Name'),
			'align'  => 'left',
			'index'  => 'name',
		));

		$this->addColumn('url', array(
			'header' => Mage::helper('banner')->__('URL'),
			'align'  => 'left',
			'index'  => 'url',
		));
/*
		$this->addColumn('blocks', array(
			'header'       => Mage::helper('banner')->__('Blocks'),
			'align'        => 'left',
			'filter_index' => 'table_alias.title',
			'index'        => 'block_title',
		));
*/
		$this->addColumn('order', array(
			'header' => Mage::helper('banner')->__('Order'),
			'align'  => 'left',
			'index'  => 'order',
		));

		$this->addColumn('clicks', array(
			'header' => Mage::helper('banner')->__('Clicks'),
			'align'  => 'left',
			'index'  => 'clicks',
		));

		$this->addColumn('imptotal', array(
			'header' => Mage::helper('banner')->__('Impressions'),
			'align'  => 'left',
			'index'  => 'imptotal',
		));

		$this->addColumn('startdate', array(
			'header' => Mage::helper('banner')->__('Start Date'),
			'align'  => 'left',
			'type'   => 'date',
			'format' => 'yyyy-MM-dd HH:mm:ss',
			'index'  => 'startdate',
		));

		$this->addColumn('enddate', array(
			'header' => Mage::helper('banner')->__('End Date'),
			'align'  => 'left',
			'type'   => 'date',
			'format' => 'yyyy-MM-dd HH:mm:ss',
			'index'  => 'enddate',
		));


		$this->addColumn('status', array(
			'header'  => Mage::helper('banner')->__('Status'),
			'align'   => 'left',
			'width'   => '80px',
			'index'   => 'status',
			'type'    => 'options',
			'options' => array(
				1 => 'Enabled',
				2 => 'Disabled',
			),
		));

		$this->addColumn('action',
			array(
				'header'    => Mage::helper('banner')->__('Action'),
				'width'     => '100',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption' => Mage::helper('banner')->__('Edit'),
						'url'     => array('base' => '*/*/edit'),
						'field'   => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
			));

		$this->addExportType('*/*/exportCsv', Mage::helper('banner')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('banner')->__('XML'));

		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('banner');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'   => Mage::helper('banner')->__('Delete'),
			'url'     => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('banner')->__('Are you sure?')
		));

		$selects  = Mage::getSingleton('banner/selectblock')->getOptionArray();
		$statuses = array();
		array_unshift($statuses, array('label' => '', 'value' => ''));

		$this->getMassactionBlock()->addItem('block', array(
			'label'      => Mage::helper('banner')->__('Select block'),
			'url'        => $this->getUrl('*/*/massSelectBlock', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name'   => 'block',
					'type'   => 'select',
					'class'  => 'required-entry',
					'label'  => Mage::helper('banner')->__('Block'),
					'values' => $selects
				)
			)
		));

		$statuses = Mage::getSingleton('banner/status')->getOptionArray();

		array_unshift($statuses, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('status', array(
			'label'      => Mage::helper('banner')->__('Change status'),
			'url'        => $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name'   => 'status',
					'type'   => 'select',
					'class'  => 'required-entry',
					'label'  => Mage::helper('banner')->__('Status'),
					'values' => $statuses
				)
			)
		));
		return $this;
	}

	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}
