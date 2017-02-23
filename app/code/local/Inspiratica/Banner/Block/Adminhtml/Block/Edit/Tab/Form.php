<?php
class Inspiratica_Banner_Block_Adminhtml_Block_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$this->setForm($form);

		$fieldset = $form->addFieldset('block_form', array('legend' => Mage::helper('banner')->__('Block Information')));

		$fieldset->addField('alias', 'text', array(
			'label'    => Mage::helper('banner')->__('Alias'),
			'class'    => 'required-entry validate-identifier',
			'required' => true,
			'name'     => 'alias',
		));

		$fieldset->addField('name', 'text', array(
			'label'    => Mage::helper('banner')->__('Name'),
			'class'    => 'required-entry',
			'name'     => 'name',
			'required' => true,
		));

		$fieldset->addField('status', 'select', array(
			'label'  => Mage::helper('banner')->__('Status'),
			'name'   => 'status',
			'values' => array(
				array(
					'value' => 1,
					'label' => Mage::helper('banner')->__('Enabled'),
				),
				array(
					'value' => 2,
					'label' => Mage::helper('banner')->__('Disabled'),
				)
			),
		));

		$fieldset->addField('target_method', 'select', array(
			'label'  => Mage::helper('banner')->__('Target'),
			'name'   => 'target_method',
			'values' => array(
				array(
					'value' => 'PARENT',
					'label' => Mage::helper('banner')->__('Current Window'),
				),
				array(
					'value' => 'NEW',
					'label' => Mage::helper('banner')->__('New Window'),
				)
			),
		));

		$fieldset->addField('sort_method', 'select', array(
			'label'  => Mage::helper('banner')->__('Sort Type'),
			'name'   => 'sort_method',
			'values' => array(
				array(
					'value' => 'RANDOM',
					'label' => Mage::helper('banner')->__('Random'),
				),
				array(
					'value' => 'ORDER',
					'label' => Mage::helper('banner')->__('Ordered'),
				)
			),
		));

		$fieldset->addField('display_method', 'select', array(
			'label'  => Mage::helper('banner')->__('Display Type'),
			'name'   => 'display_method',
			'values' => array(
				array(
					'value' => 'NORMAL',
					'label' => Mage::helper('banner')->__('Normal'),
				),
				array(
					'value' => 'SLIDER',
					'label' => Mage::helper('banner')->__('Slider'),
				)
			),
		));

		$fieldset->addField('width', 'text', array(
			'label'    => Mage::helper('banner')->__('Width (px)'),
			'name'     => 'width',
			'class'    => 'validate-zero-or-greater'
		));

		$fieldset->addField('height', 'text', array(
			'label'    => Mage::helper('banner')->__('Height (px)'),
			'name'     => 'height',
			'class'    => 'validate-zero-or-greater'
		));

		$fieldset->addField('delay_time', 'text', array(
			'label' => Mage::helper('banner')->__('Delay Time (ms)'),
			'class' => 'validate-zero-or-greater',
			'name'  => 'delay_time',
		));

		$fieldset->addField('speed', 'text', array(
			'label' => Mage::helper('banner')->__("Transition Time (ms)"),
			'class' => 'validate-zero-or-greater',
			'name'  => 'speed',
		));

		$fieldset->addField('num_banners', 'text', array(
			'label'    => Mage::helper('banner')->__('Number of Banners to Show'),
			'class'    => 'required-entry validate-greater-than-zero',
			'name'     => 'num_banners',
			'required' => true,
		));

		if (Mage::getSingleton('adminhtml/session')->getBlockData()) {
			$block_data = Mage::getSingleton('adminhtml/session')->getBlockData();
			Mage::getSingleton('adminhtml/session')->getBlockData(null);
		} elseif (Mage::registry('block_data')) {
			$block_data = Mage::registry('block_data')->getData();
		}

		$form->setValues($block_data);

		return parent::_prepareForm();
	}
}
