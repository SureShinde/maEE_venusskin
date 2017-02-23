<?php
abstract class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Types_Abstract
	extends Mage_Adminhtml_Block_Widget {

	public function getFieldName() {
		return 'layers';
	}

	public function getFieldId() {
		return 'slide_layer';
	}

	public function getAnimationSelect() {
		$select = $this->getLayout()->createBlock('adminhtml/html_select')
		               ->setData(
			               array(
				               'id'    => $this->getFieldId() . '_{{option_id}}_animation',
				               'class' => 'select select-slide-layer-animation required-option-select select-slide-layer-{{option_id}}'
			               )
		               )
		               ->setName($this->getFieldName() . '[{{option_id}}][animation]')
		               ->setExtraParams('data-value="{{animation}}"')
		               ->setOptions(Mage::getSingleton('etheme_slideshow/system_config_source_slide_layers_animation')->toOptionArray());

		return $select->getHtml();
	}

	public function getEasingSelect() {
		$select = $this->getLayout()->createBlock('adminhtml/html_select')
		               ->setData(
			               array(
				               'id'    => $this->getFieldId() . '_{{option_id}}_easing',
				               'class' => 'select select-slide-layer-easing required-option-select select-slide-layer-{{option_id}}',
			               )
		               )
		               ->setName($this->getFieldName() . '[{{option_id}}][easing]')
		               ->setExtraParams('data-value="{{easing}}"')
		               ->setOptions(Mage::getSingleton('etheme_slideshow/system_config_source_slide_layers_easing')->toOptionArray());

		return $select->getHtml();
	}

	public function getAppearanceSettings() {

		$fields = '<tr><td>';
		$fields .= '<label for="slide_layer_{{option_id}}_xoffset">X</label>: <input type="text" class="input-text" id="slide_layer_{{option_id}}_xoffset" name="layers[{{option_id}}][xoffset]" value="{{xoffset}}">';
		$fields .= '</td></tr>';

		$fields .= '<tr><td>';
		$fields .= '<label for="slide_layer_{{option_id}}_yoffset">Y</label>: <input type="text" class="input-text" id="slide_layer_{{option_id}}_yoffset" name="layers[{{option_id}}][yoffset]" value="{{yoffset}}">';
		$fields .= '</td></tr>';

		$fields .= '<tr><td>';
		$fields .= '<label for="slide_layer_{{option_id}}_speed">Speed</label>: <input type="text" class="input-text" id="slide_layer_{{option_id}}_speed" name="layers[{{option_id}}][speed]" value="{{speed}}">';
		$fields .= '</td></tr>';

		return $fields;
	}

	public function getHideCheckbox() {
		return '';
	}
}






