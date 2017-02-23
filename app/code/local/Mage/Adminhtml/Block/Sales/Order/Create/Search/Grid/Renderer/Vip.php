<?php
class Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid_Renderer_Vip extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
	public function render(Varien_Object $row) {
		return $row->canConfigure() ? $this->__('VIP') : $this->__('Non-VIP');
	}
}
