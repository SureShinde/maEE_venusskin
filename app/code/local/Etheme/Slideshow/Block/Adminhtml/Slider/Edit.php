<?php
class Etheme_Slideshow_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
    
    public function _construct() {
        
        //the class that will be inclided is: 
        //this->_blockGroup . '/' . $this->_controller . '/' 
        // . '_' . $this->_mode . '_form'
        parent::_construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'etheme_slideshow';
        $this->_controller = 'adminhtml_slider';
        $this->_mode = 'edit'; //_form
    }
    
    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->_updateButton('save', 'label', $this->__('Save Slider'));
        $this->_updateButton('delete', 'label', $this->__('Remove Slider'));
        if ($this->getRequest()->getParam('id')) {
            $this->_addButton('duplicate', array(
                'label' => $this->__('Duplicate'),
                'onclick' => 'duplicateItem()',
                'class' => 'duplicate',
            ), 50);
            
            $this->_formScripts[] = "
                function duplicateItem() {
                    //editForm.submit($('edit_form').action.replace(/\/id\/\d+/, ''));
                    editForm.submit($('edit_form').action.replace('save', 'duplicate'));
                    
                }
            ";
        }
        $this->_addButton('save_and_continue', array(
            'label' => $this->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);
        
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action + 'back/edit/');
            }
        ";
        return $this;
    }
    
    public function getHeaderText() {
        
        $model = Mage::registry('current_slider');
        if ($model && $model->getId()) {
            return $this->__('Edit slider %s', $model->getName());
        }
        else {
            return $this->__('New slider');
        }
    }
}