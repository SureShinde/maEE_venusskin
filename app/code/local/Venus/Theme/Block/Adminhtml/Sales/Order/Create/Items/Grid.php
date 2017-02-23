<?php
class Venus_Theme_Block_Adminhtml_Sales_Order_Create_Items_Grid extends Mage_Adminhtml_Block_Sales_Order_Create_Items_Grid {
	public function usedCustomPriceForItem($item) {
		return false;
	}

	public function canApplyCustomPrice($item) {
		return true;
	}
}
