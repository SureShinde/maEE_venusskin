<?php
class Venus_Theme_Block_Sales_Adminhtml_Customer_Edit_Tab_Recurring_Profile extends Mage_Sales_Block_Adminhtml_Customer_Edit_Tab_Recurring_Profile {
	public function canShowTab() {
		return Mage::getSingleton('admin/session')->isAllowed('customer/manage/view_recurring');
	}
}
