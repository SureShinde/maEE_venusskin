<?php

class Snowdog_ShippingPriority_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    static public function getOptionArray0()
    {
        $data_array = array();
        $data_array['Ground'] = 'Ground';
        $data_array['2 Day Express'] = '2 Day Express';
        $data_array['Overnight Express'] = 'Overnight Express';
        $data_array['Backordered'] = 'Backordered';
        return ($data_array);
    }

    static public function getValueArray0()
    {
        $data_array = array();
        foreach (Snowdog_ShippingPriority_Block_Adminhtml_Order_Grid::getOptionArray0() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);

    }


}