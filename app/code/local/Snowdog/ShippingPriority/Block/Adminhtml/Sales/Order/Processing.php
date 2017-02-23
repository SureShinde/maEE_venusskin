<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Sales_Order_Processing extends Venus_Theme_Block_Adminhtml_Sales_Order_Processing
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'shippingpriority';
        $this->_controller = 'adminhtml_sales_order_processing';
        $this->_headerText = Mage::helper('sales')->__('Pending Orders');

        $this->_addButton(
            'view_all',
            array(
                'label' => $this->__('View All Orders'),
                'onclick' => 'setLocation(\'' . $this->getUrl('adminhtml/sales_order') . '\')',
                'class' => 'go',
            ),
            0,
            -1
        );
    }

    public function getCreateUrl()
    {
        return $this->getUrl('adminhtml/sales_order_create/start');
    }
}
