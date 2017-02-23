<?php
class Inspiratica_Banner_Block_Adminhtml_Block_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

	public function __construct() {
		parent::__construct();
		$this->setId('block_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('banner')->__('Block Information'));
	}

	protected function _beforeToHtml() {
		$this->addTab('form_section', array(
			'label'   => Mage::helper('banner')->__('Block Information'),
			'title'   => Mage::helper('banner')->__('Block Information'),
			'content' => $this->getLayout()->createBlock('banner/adminhtml_block_edit_tab_form')->toHtml(),
		));

		$this->addTab('code_section', array(
			'label'   => Mage::helper('banner')->__('Implementation Code'),
			'title'   => Mage::helper('banner')->__('Implementation Code'),
			'content' => $this->getLayout()->createBlock('banner/adminhtml_block_edit_tab_code')->toHtml(),
		));

		return parent::_beforeToHtml();
	}
}
