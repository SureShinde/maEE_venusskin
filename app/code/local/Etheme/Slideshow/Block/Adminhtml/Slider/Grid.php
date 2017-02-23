<?php

class Etheme_Slideshow_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    
    public function _construct() {
        parent::_construct();
        $this->setId('sliders');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);      
        $this->setUseAjax(true);
    }
    
    protected function _prepareCollection() {
        
        $collection = Mage::getModel('etheme_slideshow/slider')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareMassaction() {
        $this->setMassActionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('etheme_slideshow')->__('delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' =>  Mage::helper('etheme_slideshow')->__('Are you sure?'),
        ));

    }
    
    protected function _prepareColumns() {

        $this->addColumn('name', array(
            'header' => Mage::helper('etheme_slideshow')->__('Name'),
            'width' => '300px',
            'index' => 'name'
        ));
        
        $this->addColumn('identifier', array(
            'header' => Mage::helper('etheme_slideshow')->__('Identifier'),
            'width' => '300px',
            'index' => 'identifier'
        ));
        
        $this->addColumn('slides_count', array(
            'header' => Mage::helper('etheme_slideshow')->__('Slides Count'),
            'width' => '50px',
            'index' => 'slides_count'
        ));
        
        
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('etheme_slideshow')->__('Store View'),
                'width'         => '200px',
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => true,
                'filter_condition_callback' => array($this,
              		'_filterStoreCondition'),
                
            ));
        }
        
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('etheme_slideshow')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('etheme_slideshow')->__('Disabled'),
                1 => Mage::helper('etheme_slideshow')->__('Enabled')
            ),
        ));
        
        $this->addColumn('created', array(
            'header' => Mage::helper('etheme_slideshow')->__('Created'),
            'width' => '100px',
            'type'  => 'datetime',
            'index' => 'created_at'
        ));
        
        $this->addColumn('modified', array(
            'header' => Mage::helper('etheme_slideshow')->__('Updated'),
            'width' => '100px',
            'type'  => 'datetime',
            'index' => 'updated_at'
        ));
        
        $this->addColumn('action', array(
            'header' => $this->__('Settings'),
            'width' => '100px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => $this->__('Settings'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false, 
            'sortable' => false
        ));
        
        return parent::_prepareColumns();
    }
    
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }
    
    
    public function getRowUrl($row) {
        return $this->getUrl('*/slides/list', array('slider_id' => $row->getId()));
    }
    
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('current' => true));
    }
    
}





