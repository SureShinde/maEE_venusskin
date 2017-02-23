<?php
class Inspiratica_Banner_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('banner_form', array('legend' => Mage::helper('banner')->__('Banner information')));

		$image_calendar = Mage::getBaseUrl('skin') . 'adminhtml/default/default/images/grid-cal.gif';

		if (Mage::getSingleton('adminhtml/session')->getBannerData()) {
			$bannerData = Mage::getSingleton('adminhtml/session')->getBannerData();
			Mage::getSingleton('adminhtml/session')->setBannerData(null);
		} elseif (Mage::registry('banner_data')) {
			$bannerData = Mage::registry('banner_data')->getData();
		}

		if (isset($bannerData['image']) && $bannerData['image']) {
			$bannerData['image'] = Mage::getBaseUrl('media') . 'banners/' . $bannerData['image'];
		}

		$fieldset->addField(
			'name', 'text',
			array(
				'label'    => Mage::helper('banner')->__('Name'),
				'class'    => 'required-entry',
				'required' => true,
				'name'     => 'name',
			)
		);

		$fieldset->addField(
			'alt', 'text',
			array(
				'label' => Mage::helper('banner')->__('Alt Tag'),
				'name'  => 'alt',
			)
		);

		$fieldset->addField(
			'order', 'text',
			array(
				'label'    => Mage::helper('banner')->__('Order'),
				'class'    => 'required-entry validate-zero-or-greater',
				'name'     => 'order',
				'required' => true
			)
		);

		$fieldset->addField(
			'status', 'select',
			array(
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
			)
		);

		$fieldset->addField(
			'blocks', 'multiselect',
			array(
				'label'    => Mage::helper('banner')->__('Blocks'),
				'class'    => 'required-entry',
				'name'     => 'blocks',
				'values'   => Mage::helper('banner')->getOptionBlockId(),
				'required' => true
			)
		);

		if (!Mage::app()->isSingleStoreMode()) {
			$field    = $fieldset->addField(
				'store_ids', 'multiselect',
				array(
					'name'     => 'store_ids[]',
					'label'    => Mage::helper('banner')->__('Store View'),
					'title'    => Mage::helper('banner')->__('Store View'),
					'required' => true,
					'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				)
			);
			$renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
			$field->setRenderer($renderer);
		} else {
			$fieldset->addField(
				'store_ids', 'hidden',
				array(
					'name'  => 'store_ids[]',
					'value' => Mage::app()->getStore(true)->getId()
				)
			);
			$bannerData['store_ids'] = Mage::app()->getStore(true)->getId();
		}

		$fieldset->addField(
			'imptotal', 'text',
			array(
				'label' => Mage::helper('banner')->__('Max Impressions (leave blank for unlimited)'),
				'name'  => 'imptotal'
			)
		);

		$fieldset->addField(
			'url', 'text',
			array(
				'label'    => Mage::helper('banner')->__('URL'),
				'class'    => 'required-entry validate-url',
				'name'     => 'url',
				'required' => true
			)
		);

		$fieldset->addField(
			'clicks', 'text',
			array(
				'label'    => Mage::helper('banner')->__('Clicks'),
				'readonly' => 'readonly',
				'name'     => 'clicks',
			)
		);

		$fieldset->addField(
			'impmade', 'text',
			array(
				'label'    => Mage::helper('banner')->__('Impressions Made'),
				'readonly' => 'readonly',
				'name'     => 'impmade',
			)
		);

		$fieldset->addField(
			'image', 'image',
			array(
				'label' => Mage::helper('banner')->__('Banner Image'),
				'name'  => 'image',
			)
		);

		$fieldset->addField(
			'startdate', 'date',
			array(
				'label'    => Mage::helper('banner')->__('Start date'),
				'format'   => 'yyyy-MM-dd HH:mm:ss',
				'image'    => $image_calendar,
				'time'     => true,
				'name'     => 'startdate',
				'style'    => 'width: 150px',
				'required' => true
			)
		);

		$fieldset->addField(
			'enddate', 'date',
			array(
				'label'    => Mage::helper('banner')->__('End date'),
				'format'   => 'yyyy-MM-dd HH:mm:ss',
				'image'    => $image_calendar,
				'time'     => true,
				'name'     => 'enddate',
				'style'    => 'width: 150px',
				'required' => true
			)
		);

		$form->setValues($bannerData);

		return parent::_prepareForm();
	}
}
