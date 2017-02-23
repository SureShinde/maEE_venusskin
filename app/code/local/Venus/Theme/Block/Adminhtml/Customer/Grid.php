<?php
class Venus_Theme_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid {
	protected function _prepareMassaction() {
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('customer');

		if (Mage::getSingleton('admin/session')->isAllowed('customer/manage_delete')) {
			$this->getMassactionBlock()->addItem(
				'delete',
				array(
					'label'   => Mage::helper('customer')->__('Delete'),
					'url'     => $this->getUrl('*/*/massDelete'),
					'confirm' => Mage::helper('customer')->__('Are you sure?')
				)
			);
		}

		$this->getMassactionBlock()->addItem(
			'newsletter_subscribe',
			array(
				'label' => Mage::helper('customer')->__('Subscribe to Newsletter'),
				'url'   => $this->getUrl('*/*/massSubscribe')
			)
		);

		$this->getMassactionBlock()->addItem(
			'newsletter_unsubscribe',
			array(
				'label' => Mage::helper('customer')->__('Unsubscribe from Newsletter'),
				'url'   => $this->getUrl('*/*/massUnsubscribe')
			)
		);

		$groups = $this->helper('customer')->getGroups()->toOptionArray();

		array_unshift($groups, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem(
			'assign_group',
			array(
				'label'      => Mage::helper('customer')->__('Assign a Customer Group'),
				'url'        => $this->getUrl('*/*/massAssignGroup'),
				'additional' => array(
					'visibility' => array(
						'name'   => 'group',
						'type'   => 'select',
						'class'  => 'required-entry',
						'label'  => Mage::helper('customer')->__('Group'),
						'values' => $groups
					)
				)
			)
		);

		return $this;
	}
}
