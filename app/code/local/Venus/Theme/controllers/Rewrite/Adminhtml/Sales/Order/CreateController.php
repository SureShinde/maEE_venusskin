<?php
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'Order' . DS . 'CreateController.php';

class Venus_Theme_Rewrite_Adminhtml_Sales_Order_CreateController extends Mage_Adminhtml_Sales_Order_CreateController {
	protected function _getAclResourse() {
		$action = strtolower($this->getRequest()->getActionName());
		if (in_array($action, array('index', 'save')) && $this->_getSession()->getReordered()) {
			$action = 'reorder';
		}

		switch ($action) {
			case 'index':
			case 'save':
			case 'cancel':
				$aclResource = 'sales/order/actions/create';
				break;
			case 'reorder':
				$aclResource = 'sales/order/actions/reorder';
				break;
			default:
				$aclResource = 'sales/order/actions';
				break;
		}

		return $aclResource;
	}
}
