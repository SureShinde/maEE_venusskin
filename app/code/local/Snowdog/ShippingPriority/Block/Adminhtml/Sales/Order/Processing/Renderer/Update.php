<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Sales_Order_Processing_Renderer_Update extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if (!is_null($row->getData('original_increment_id'))) {
            return '<img width="25" height="25" src="' . $this->getSkinUrl('images/icon-warning.png', array('_secure' => true)) . '"/>';
        }
    }
}
