<?php

class Snowdog_HideProduct_Model_Mysql4_Hideproduct_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init("hideproduct/hideproduct");
    }
}