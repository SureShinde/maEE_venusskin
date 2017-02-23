<?php
class Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
	public function render(Varien_Object $row) {
		$rendered       = parent::render($row);
		$isConfigurable = $row->canConfigure();
		$style          = $isConfigurable ? '' : 'style="color: #cccccc;"';
		$prodAttributes = $isConfigurable ? sprintf('list_type = "product_to_add" product_id = %s', $row->getId()) : 'disabled="disabled"';

		return sprintf('<a href="javascript:void(0)" %s class="f-right" %s>%s</a>', $style, $prodAttributes, Mage::helper('sales')->__('Select Shipping Cycle')) . $rendered;
	}
}
