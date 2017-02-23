<?php
class Snowdog_HideProduct_Model_Mysql4_Hideproduct extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("hideproduct/hideproduct", "id");
    }
}