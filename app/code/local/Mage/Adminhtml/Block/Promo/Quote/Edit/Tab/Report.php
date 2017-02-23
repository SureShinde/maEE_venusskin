<?php
class Mage_Adminhtml_Block_Promo_Quote_Edit_Tab_Report extends Mage_Adminhtml_Block_Text_List implements Mage_Adminhtml_Block_Widget_Tab_Interface {
	public function getTabLabel() {
		return Mage::helper('salesrule')->__('Coupon Usage');
	}

	public function getTabTitle() {
		return Mage::helper('salesrule')->__('Coupon Usage');
	}

	public function canShowTab() {
		return $this->_isEditing();
	}

	public function isHidden() {
		return !$this->_isEditing();
	}

	protected function _isEditing() {
		$priceRule = Mage::registry('current_promo_quote_rule');

		return !is_null($priceRule->getRuleId());
	}
}
