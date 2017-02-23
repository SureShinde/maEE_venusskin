<?php

/**
 * Class Venus_Theme_Block_Adminhtml_Sales_Order_Create_Search_Grid
 */
class Venus_Theme_Block_Adminhtml_Sales_Order_Create_Search_Grid extends Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
{
    /**
     * Venus_Theme_Block_Adminhtml_Sales_Order_Create_Search_Grid constructor.\
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_create_search_grid');
        $this->setRowClickCallback('order.productGridRowClick.bind(order)');
        $this->setCheckboxCheckCallback('order.productGridCheckboxCheck.bind(order)');
        $this->setRowInitCallback('order.productGridRowInit.bind(order)');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter(
            'product_sell_types',
            array(
                'header' => Mage::helper('sales')->__('Product sell type'),
                'type' => 'options',
                'options' => $this->_getAttributeOptions('product_sell_type'),
                'sortable' => false,
                'index'     => 'product_sell_type'
            ),
            'name'
        );

        return parent::_prepareColumns();
    }

    /**
     * Prepare collection to be displayed in the grid
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
     */
    protected function _prepareCollection()
    {
        $attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection
            ->setStore($this->getStore())
            ->addAttributeToSelect($attributes)
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('product_sell_type')
            ->addStoreFilter()
            ->addAttributeToFilter('type_id', array_keys(
                Mage::getConfig()->getNode('adminhtml/sales/order/create/available_product_types')->asArray()
            ))
            ->addAttributeToSelect('gift_message_available');

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);

        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    /**
     * Get attribute options by code
     *
     * @param $attribute_code
     * @return array
     * @throws Mage_Core_Exception
     */
    protected function _getAttributeOptions($attribute_code)
    {
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attribute_code);
        $options = array();
        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
