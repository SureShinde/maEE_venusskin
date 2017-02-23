<?php
class Venus_Theme_Block_Api2_Adminhtml_Permissions_User_Edit_Tab_Roles extends Mage_Api2_Block_Adminhtml_Permissions_User_Edit_Tab_Roles {
	/**
	 * Returns status flag about this tab hidden or not
	 *
	 * @return true
	 */
	public function isHidden() {
		return true;
	}
}
