<?php

/**
 * Class Snowdog_Multilevel_Helper_Admin
 */
class Snowdog_Multilevel_Helper_Admin extends Mage_Core_Helper_Abstract
{
    const ACL_CUSTOMER_ALL = 'customer_all';

    /**
     * @param int|Mage_Admin_Model_User|null $adminUser
     *
     * @return int|bool
     */
    public function getOwnerAdminUserId($adminUser = null)
    {
        if ($adminUser === null) {
            if (!$this->_getSession()->isLoggedIn()) {
                return false;
            }

            $adminUser = $this->_getSession()->getUser();
        } else if (!is_object($adminUser)) {
            $adminUser = Mage::getModel('admin/user')->load($adminUser);
        }

        return $adminUser->getId();
    }

    /**
     * Return session
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _getSession()
    {
        return Mage::getSingleton('admin/session');
    }
}
