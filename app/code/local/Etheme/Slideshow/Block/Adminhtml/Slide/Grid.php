<?php

class Etheme_Slideshow_Block_Adminhtml_Slide_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    
    public function _construct() {
        parent::_construct();
        $this->setId('slides');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);        
        $this->setUseAjax(false);
    }
    
    protected function _prepareCollection() {
        
        $currentSlider = Mage::registry('current_slider');
        $sliderId = $currentSlider->getId();
        $collection = Mage::getModel('etheme_slideshow/slide')
            ->getCollection()
            ->addFieldToFilter('slider_id', array('eq' => $sliderId));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareMassaction() {
        $currentSliderId = $this->getRequest()->getParam('slider_id');
        $this->setMassActionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('etheme_slideshow')->__('delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' =>  Mage::helper('etheme_slideshow')->__('Are you sure?'),
        ));
        
        //$statuses = Mage::getModel('');
    }
    
    protected function _prepareColumns() {
        $currentSlider = Mage::registry('current_slider');
        $sliderId = $currentSlider->getId();
        $this->addColumn('name', array(
            'header' => Mage::helper('etheme_slideshow')->__('Name'),
            'width' => '500px',
            'index' => 'name'
        ));
        
        $this->addColumn('position', array(
            'header' => Mage::helper('etheme_slideshow')->__('Position'),
            'width' => '500px',
            'index' => 'position'
        ));
        
        $this->addColumn('created', array(
            'header' => Mage::helper('etheme_slideshow')->__('Created'),
            'width' => '500px',
            'index' => 'created_at'
        ));
        
        $this->addColumn('modified', array(
            'header' => Mage::helper('etheme_slideshow')->__('Updated'),
            'width' => '500px',
            'index' => 'updated_at'
        ));
        
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('etheme_slideshow')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('etheme_slideshow')->__('Disabled'),
                1 => Mage::helper('etheme_slideshow')->__('Enabled')
            ),
        ));
        
        $this->addColumn('action', array(
            'header' => $this->__('Action'),
            'width' => '100px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => $this->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false, 
            'sortable' => false
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('current' => true, 'slider_id' => Mage::registry('current_slider')->getId()));
    }
}





