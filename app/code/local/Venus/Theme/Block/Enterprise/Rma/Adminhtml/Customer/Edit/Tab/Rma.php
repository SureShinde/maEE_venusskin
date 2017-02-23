<?php
class Venus_Theme_Block_Enterprise_Rma_Adminhtml_Customer_Edit_Tab_Rma extends Enterprise_Rma_Block_Adminhtml_Customer_Edit_Tab_Rma {
	public function canShowTab() {
		return Mage::getSingleton('admin/session')->isAllowed('customer/manage_view_tabs_rma');
	}
}
