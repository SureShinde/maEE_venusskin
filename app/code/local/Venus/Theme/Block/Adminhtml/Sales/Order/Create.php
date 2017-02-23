<?php
class Venus_Theme_Block_Adminhtml_Sales_Order_Create extends Mage_Adminhtml_Block_Sales_Order_Create {
	public function __construct() {
		if ($adminUser = Mage::getSingleton('admin/session')->getUser()) {
			/**
			 * @var Mage_Admin_Model_User $adminUser
			 */

			if (!$adminUser->getRole()->getGwsIsAll()) {
				$storeIds = array_filter($adminUser->getRole()->getGwsStores());

				if (count($storeIds) === 1) {
					$this->_getSession()->setStoreId(reset($storeIds));
				}
			}
		}

		parent::__construct();
	}
}
