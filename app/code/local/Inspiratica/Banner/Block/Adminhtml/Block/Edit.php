<?php
class Inspiratica_Banner_Block_Adminhtml_Block_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	public function __construct() {
		parent::__construct();

		$this->_objectId   = 'id';
		$this->_blockGroup = 'banner';
		$this->_controller = 'adminhtml_block';

		$this->_updateButton('save', 'label', Mage::helper('banner')->__('Save Block'));
		$this->_updateButton('delete', 'label', Mage::helper('banner')->__('Delete Block'));

		$this->_addButton('saveandcontinue', array(
			'label'   => Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick' => 'saveAndContinueEdit()',
			'class'   => 'save',
		), -100);

		$this->_formScripts[] = "

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
	}

	public function getHeaderText() {
		if (Mage::registry('block_data') && Mage::registry('block_data')->getId()) {

			return Mage::helper('banner')->__("Edit Block '%s'", $this->htmlEscape(Mage::registry('block_data')->getTitle()));
		} else {

			return Mage::helper('banner')->__('Add Block');
		}
	}
}
