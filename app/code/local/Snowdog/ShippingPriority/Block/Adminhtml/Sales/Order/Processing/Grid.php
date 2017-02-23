<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Sales_Order_Processing_Grid extends Venus_Theme_Block_Adminhtml_Sales_Order_Processing_Grid
{
    protected $_group_list;

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass())->addFieldToFilter('main_table.status', 'pending');

        $collection->getSelect()->joinLeft(
            'customer_entity',
            'main_table.customer_id = customer_entity.entity_id',
            array('group_id'));

        $collection->getSelect()->join(
            'sales_flat_order',
            'main_table.entity_id = sales_flat_order.entity_id',
            array('shipping_priority', 'original_increment_id'));

        $this->setCollection($collection);


        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'real_order_id', array(
                'header' => Mage::helper('sales')->__('Order #'),
                'width' => '80px',
                'type' => 'text',
                'index' => 'increment_id',
                'filter_index' => 'main_table.increment_id',
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'store_id', array(
                    'header' => Mage::helper('sales')->__('Purchased From (Store)'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'filter_index' => 'main_table.store_id',
                    'store_view' => true,
                    'display_deleted' => true,
                )
            );
        }

        $this->addColumn(
            'created_at', array(
                'header' => Mage::helper('sales')->__('Purchased On'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '100px',
                'filter_index' => 'main_table.created_at',
            )
        );

        $this->addColumn(
            'billing_name', array(
                'header' => Mage::helper('sales')->__('Bill to Name'),
                'index' => 'billing_name',
                'filter_index' => 'main_table.billing_name',
            )
        );

        $this->addColumn(
            'shipping_name', array(
                'header' => Mage::helper('sales')->__('Ship to Name'),
                'index' => 'shipping_name',
                'filter_index' => 'main_table.shipping_name',
            )
        );

        $this->addColumn(
            'base_grand_total', array(
                'header' => Mage::helper('sales')->__('G.T. (Base)'),
                'index' => 'base_grand_total',
                'type' => 'currency',
                'currency' => 'base_currency_code',
                'filter_index' => 'main_table.base_grand_total',
            )
        );

        $this->addColumn(
            'grand_total', array(
                'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
                'index' => 'grand_total',
                'type' => 'currency',
                'currency' => 'order_currency_code',
                'filter_index' => 'main_table.grand_total',
            )
        );

        $this->addColumn(
            'shipping_priority', array(
                'header' => Mage::helper('sales')->__('Shipping priority'),
                'index' => 'shipping_priority',
                'width' => '70px',
                'type' => 'options',
                'options' => array(
                    'Ground' => 'Ground',
                    '2 Day Express' => '2 Day Express',
                    'Overnight Express' => 'Overnight Express',
                    'Backordered' => 'Backordered'
                ),

            )
        );
        
        $this->addColumn(
            'group_id', array(
                'header' => Mage::helper('sales')->__('Customer group'),
                'index' => 'group_id',
                'width' => '70px',
                'type' => 'options',
                'options' => $this->getCustomerGroupArray()
            )
        );

        $this->addColumn(
            'status', array(
                'header' => Mage::helper('sales')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'width' => '70px',
                'filter' => false,
                'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
            )
        );

        $this->addColumn(
            'update', array(
                'header'  => Mage::helper('sales')->__('Update'),
                'index'   => 'update',
                'align' => 'center',
                'width' => '30px',
                'renderer' => 'Snowdog_ShippingPriority_Block_Adminhtml_Sales_Order_Processing_Renderer_Update'

            )
        );

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn(
                'action',
                array(
                    'header' => Mage::helper('sales')->__('Action'),
                    'width' => '50px',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url' => array('base' => 'adminhtml/sales_order/view'),
                            'field' => 'order_id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
                )
            );
        }
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));
        }

        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/processingGrid', array('_current' => true));
    }

    public function getCustomerGroupArray()
    {
        if (!$this->_group_list) {
            $groupArray = array();
            $groupCollection = Mage::getModel('customer/group')->getCollection();
            foreach ($groupCollection as $group) {
                $groupArray[$group->getCustomerGroupId()] = $group->getCustomerGroupCode();
            }

            $this->_group_list = $groupArray;
        }

        return $this->_group_list;
    }
}
