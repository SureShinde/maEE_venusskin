<?php
class Inspiratica_Banner_Block_Adminhtml_Block_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('blockGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('banner/block')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
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

		$this->addColumn('alias', array(
			'header' => Mage::helper('banner')->__('Alias'),
			'align'  => 'left',
			'index'  => 'alias',
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
		$this->getMassactionBlock()->setFormFieldName('block');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'   => Mage::helper('banner')->__('Delete'),
			'url'     => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('banner')->__('Are you sure?')
		));


		return $this;
	}

	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}
