<?php 

class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form { 
    
    protected function _prepareLayout() {
        $this->setChild('layers_block',
            $this->getLayout()
            ->createBlock('etheme_slideshow/adminhtml_slide_edit_tab_layers')
        );
    }

    protected function _prepareForm() {
        
        $form = new Varien_Data_Form;
        $form->setHtmlIdPrefix('contents');
        $model = Mage::registry('current_slide');
        $fieldset = $form->addFieldset('contents_form', array(
            'legend' => $this->__('Slide content')
        ));
        $fieldset->addType('slideimage', 'Etheme_Data_Form_Element_Slideimage');
        
        $fieldset->addField('bg', 'slideimage', array(
            'label' => $this->__('Background Image'),
            'name' => 'bg',
            'width' => 300,
            'height' => 300,
            'required' => false,
            'value'     => 'image/url',
        ));
         
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
    
    public function _toHtml() {
        $html = parent::_toHtml();
        return $html . $this->getChildHtml('layers_block');
    }

}