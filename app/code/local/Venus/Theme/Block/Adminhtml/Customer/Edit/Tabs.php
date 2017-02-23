<?php
class Venus_Theme_Block_Adminhtml_Customer_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs {
	protected function _beforeToHtml() {
		$this->addTab(
			'account',
			array(
				'label'   => Mage::helper('customer')->__('Account Information'),
				'content' => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_account')->initForm()->toHtml(),
				'active'  => Mage::registry('current_customer')->getId() ? false : true
			)
		);

		$this->addTab(
			'addresses',
			array(
				'label'   => Mage::helper('customer')->__('Addresses'),
				'content' => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_addresses')->initForm()->toHtml(),
			)
		);

		if (Mage::registry('current_customer')->getId()) {
			if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
				$this->addTab(
					'orders',
					array(
						'label' => Mage::helper('customer')->__('Orders'),
						'class' => 'ajax',
						'url'   => $this->getUrl('*/*/orders', array('_current' => true)),
					)
				);
			}

			if (Mage::getSingleton('admin/session')->isAllowed('customer/manage_view_tabs_cart')) {
				$this->addTab(
					'cart',
					array(
						'label' => Mage::helper('customer')->__('Shopping Cart'),
						'class' => 'ajax',
						'url'   => $this->getUrl('*/*/carts', array('_current' => true)),
					)
				);
			}

			if (Mage::getSingleton('admin/session')->isAllowed('customer/manage_view_tabs_wishlist')) {
				$this->addTab(
					'wishlist',
					array(
						'label' => Mage::helper('customer')->__('Wishlist'),
						'class' => 'ajax',
						'url'   => $this->getUrl('*/*/wishlist', array('_current' => true)),
					)
				);
			}

			if (Mage::getSingleton('admin/session')->isAllowed('newsletter/subscriber')) {
				$this->addTab(
					'newsletter',
					array(
						'label'   => Mage::helper('customer')->__('Newsletter'),
						'content' => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_newsletter')->initForm()->toHtml()
					)
				);
			}

			if (Mage::getSingleton('admin/session')->isAllowed('catalog/reviews_ratings')) {
				$this->addTab(
					'reviews',
					array(
						'label' => Mage::helper('customer')->__('Product Reviews'),
						'class' => 'ajax',
						'url'   => $this->getUrl('*/*/productReviews', array('_current' => true)),
					)
				);
			}

			if (Mage::getSingleton('admin/session')->isAllowed('catalog/tag')) {
				$this->addTab(
					'tags',
					array(
						'label' => Mage::helper('customer')->__('Product Tags'),
						'class' => 'ajax',
						'url'   => $this->getUrl('*/*/productTags', array('_current' => true)),
					)
				);
			}
		}

		$this->_updateActiveTab();
		Varien_Profiler::stop('customer/tabs');

		return Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
	}
}
