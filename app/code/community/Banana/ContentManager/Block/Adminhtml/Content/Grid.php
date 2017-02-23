<?php
/**
 * Banana ContentManager Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.banana.fr)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.2.4
 */

class Banana_ContentManager_Block_Adminhtml_Content_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('contentGrid');
        // This is the primary key of the database
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('contentmanager/content')
                ->getCollection()
                ->addAttributeToFilter('ct_id', $this->getRequest()->getParam('ct_id'))
                ->addAttributeToSelect('entity_id');
        
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if($storeId)
        {
            $collection->addStoreFilter($storeId);
        }
        
        $this->setCollection($collection);
        parent::_prepareCollection();
        
        //load attributes value for selected store view
        foreach($collection as $item)
        {
            $item = $item->setStoreId($this->_getStore()->getId())->load(null);
        }
        
        return $this;
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('contentmanager')->__('ID'),
            'align'     =>'center',
            'width'     => '20px',
            'index'     => 'entity_id',
        ));
        
        $this->addColumn('title', array(
            'header'    => Mage::helper('contentmanager')->__('Meta Title'),
            'align'     =>'left',
            'index'     => 'title',
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Text(),
        ));
        
        $this->addColumn('url_key', array(
            'header'    => Mage::helper('contentmanager')->__('URL'),
            'align'     =>'left',
            'index'     => 'url_key',
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Text(),
        ));
 
        $this->_prepareDynamicColumns();
        
        $this->addColumn('status', array(
            'header'    => Mage::helper('contentmanager')->__('Status'),
            'align'     =>'left',
            'width'     => '100px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('contentmanager')->__('Disabled'),
                1 => Mage::helper('contentmanager')->__('Enabled')
            ),
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Status(),
        ));
        
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('contentmanager')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_at',
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Time(),
        ));
 
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('contentmanager')->__('Update Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'updated_at',
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Time(),
        ));
        
        $this->addColumn('store', array(
            'header'    => Mage::helper('contentmanager')->__('Available Store View'),
            'align'     =>'left',
            'index'     => 'store',
            'type'          => 'store',
            'width'     => '100px',
            'store_all'     => true,
            'filter'        => false,
            'store_view'    => true,
            'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Store(),
        ));
 
        return parent::_prepareColumns();
    }
    
    protected function _prepareDynamicColumns()
    {
        $contentTypeModel = Mage::registry('current_contenttype');
        
        //load all fields for this contenttype that are displayed in the grid
        $fields = Mage::getModel('contentmanager/contenttype_option')
                    ->getCollection()
                    ->addFieldToFilter('show_in_grid', 1)
                    ->addFieldToFilter('ct_id', $contentTypeModel->getId())
                    ->addTitleToResult($contentTypeModel->getStoreId());
        
        foreach($fields as $field)
        {
            $this->addColumn($field->getIdentifier(), array(
                'header'    => Mage::helper('contentmanager')->__($field->getTitle()),
                'align'     =>'left',
                'index'     => $field->getIdentifier(),
                'renderer'  => new Banana_ContentManager_Block_Adminhtml_Content_Renderer_Text(),
            ));
        }

    }
     
    public function getRowUrl($row)
    {
        return null;
    }
 
 
}