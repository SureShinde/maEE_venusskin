<?php
class Venus_Theme_Block_Adminhtml_Sales_Order_Create_Form_Account extends Mage_Adminhtml_Block_Sales_Order_Create_Form_Account {
	/**
	 * Return header text
	 *
	 * @return string
	 */
	public function getHeaderText() {
		return Mage::helper('sales')->__('Patient Email');
	}
}
