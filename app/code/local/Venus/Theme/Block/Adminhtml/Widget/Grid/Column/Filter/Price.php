<?php
class Venus_Theme_Block_Adminhtml_Widget_Grid_Column_Filter_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Price {
	public function getHtml() {
		$html = '<div class="range">';
		$html .= '<div class="range-line"><span class="label">' . Mage::helper('adminhtml')->__('From') . ':</span> <input type="text" name="' . $this->_getHtmlName() . '[from]" id="' . $this->_getHtmlId() . '_from" value="' . $this->getEscapedValue('from') . '" class="input-text no-changes"/></div>';
		$html .= '<div class="range-line"><span class="label">' . Mage::helper('adminhtml')->__('To') . ' : </span><input type="text" name="' . $this->_getHtmlName() . '[to]" id="' . $this->_getHtmlId() . '_to" value="' . $this->getEscapedValue('to') . '" class="input-text no-changes"/></div>';
		$html .= '</div>';

		return $html;
	}
}
