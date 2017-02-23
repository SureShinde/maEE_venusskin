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
 * to contact@advancedcontentmanager.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.advancedcontentmanager.com)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.2.4
 */

class Banana_ContentManager_Block_Adminhtml_Widget_Chooser_Content extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($arguments=array())
    {
        parent::__construct($arguments);

        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('skuChooserGrid_'.$this->getId());
        }

        $form = $this->getJsFormObject();
        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('sku');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    /**
     * Retrieve quote store object
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore();
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_contents') {
            $selected = $this->_getSelectedProducts();
            if (empty($selected)) {
                $selected = '';
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$selected));
            } else {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare Catalog Product Collection for attribute SKU in Promo Conditions SKU chooser
     *
     * @return Mage_Adminhtml_Block_Promo_Widget_Chooser_Sku
     */
    protected function _prepareCollection()
    {
        if($this->getData('content_type'))
        {
            $collection = Mage::getModel('contentmanager/content')->getCollection($this->getData('content_type'));
        }
        else
        {
            $collection = Mage::getResourceModel('contentmanager/content_collection');
        }
        
        $collection->setStoreId(0)
                    ->addAttributeToSelect(array('title', 'cc_id'));
        

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define Cooser Grid Columns and filters
     *
     * @return Mage_Adminhtml_Block_Promo_Widget_Chooser_Sku
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_contents', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_contents',
            'values'    => $this->_getSelectedProducts(),
            'align'     => 'center',
            'index'     => 'entity_id',
            'use_index' => true,
        ));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('contentmanager')->__('ID'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'entity_id'
        ));
        
        $this->addColumn('title', array(
            'header'    => Mage::helper('contentmanager')->__('Title'),
            'name'      => 'title',
            'index'     => 'title'
        ));

        $this->addColumn('ct_id',
            array(
                'header'=> Mage::helper('contentmanager')->__('Content type'),
                'width' => '60px',
                'index' => 'ct_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('contentmanager/contenttype')->getOptionArray(),
        ));
        
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('contentmanager')->__('Updated At'),
            'name'      => 'updated_at',
            'index'     => 'updated_at'
        ));
        
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('contentmanager')->__('Created At'),
            'name'      => 'created_at',
            'index'     => 'created_at'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/chooser', array(
            '_current'          => true,
            'current_grid_id'   => $this->getId(),
            'collapse'          => null
        ));
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected', array());

        return $products;
    }

}

