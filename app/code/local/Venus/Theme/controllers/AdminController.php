<?php
class Venus_Theme_AdminController extends Mage_Adminhtml_Controller_Action {
	public function ajaxBlockAction() {
		$output   = '';
		$blockTab = $this->getRequest()->getParam('block');
		if (in_array($blockTab, array('tab_orders', 'tab_amounts', 'totals', 'grids'))) {
			$output = $this->getLayout()->createBlock('adminhtml/dashboard_' . $blockTab)->toHtml();
		}
		$this->getResponse()->setBody($output);

		return;
	}
}
