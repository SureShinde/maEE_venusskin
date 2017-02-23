<?php

/**
 * Class Snowdog_Multilevel_Block_Adminhtml_Admin_Notifications
 */
class Snowdog_Multilevel_Block_Adminhtml_Admin_Notifications extends Mage_Core_Block_Text_List
{
    protected function _toHtml()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('all')) {
            return '';
        }

        return parent::_toHtml();
    }
}
