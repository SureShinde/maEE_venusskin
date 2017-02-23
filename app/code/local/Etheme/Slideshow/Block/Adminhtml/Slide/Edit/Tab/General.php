<?php 

class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {
    
    protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');
        $model = Mage::registry('current_slide');
        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('General Setup')
        ));
        
        if ($model->getId()) {
            
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }
        
        $fieldset->addField('name', 'text', array(
            'label' => $this->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));
        
        $fieldset->addField('position', 'text', array(
            'label' => $this->__('Position'),
            'required' => true,
            'name' => 'position',
            'class' => 'required-entry validate-zero-or-greater',
        ));
        
        $fieldset->addField('slider_id', 'hidden', array(
            'label' => $this->__('Slider id'),
            'required' => true,
            'name' => 'slider_id',
        ));
        
        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('cms')->__('Status'),
            'title'     => Mage::helper('cms')->__('Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('cms')->__('Enabled'),
                '0' => Mage::helper('cms')->__('Disabled'),
            ),
        ));
        
        $fieldset->addField('transition', 'select', array(
            'label'     => Mage::helper('cms')->__('Transition'),
            'title'     => Mage::helper('cms')->__('Transition'),
            'name'      => 'transition',
            'required'  => true,
            'values'   => Mage::getSingleton('etheme_slideshow/system_config_source_slide_transition')->toOptionArray()
        ));
        
        $fieldset->addField('transition_duration', 'text', array(
            'label' => $this->__('Transition Duration'),
            'required' => true,
            'name' => 'transition_duration',
            'class' => 'validate-zero-or-greater',
        ));
        
        $fieldset->addField('delay', 'text', array(
            'label' => $this->__('Delay'),
            'name' => 'delay',
            'class' => 'validate-zero-or-greater',
        ));
        
        $fieldset->addField('slot_amount', 'text', array(
            'label' => $this->__('Slot amount'),
            'name' => 'slot_amount',
            'class' => 'required-entry',
        ));
        
        $fieldset->addField('rotation', 'text', array(
            'label' => $this->__('Rotation'),
            'name' => 'rotation',
            'class' => 'validate-zero-or-greater',
        ));
        
        $fieldset->addField('full_width_cent', 'checkbox', array(
          'label' => $this->__('Full Width Centering'),
          'name'       => 'full_width_cent',
          'checked'    => $this->getFullWidthCent() == 1 ? 'true' : 'false',
          'onclick'    => 'this.value = this.checked ? 1 : 0;'
        ));

        $enableLink = $fieldset->addField('enable_link', 'select', array(
            'label'     => Mage::helper('cms')->__('Enable Link'),
            'title'     => Mage::helper('cms')->__('Enable Link'),
            'name'      => 'enable_link',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('cms')->__('Yes'),
                '0' => Mage::helper('cms')->__('No'),
            ),
        ));

        $slideLink = $fieldset->addField('slide_link', 'text', array(
            'label' => $this->__('Slide link'),
            'name' => 'slide_link',
        ));

        $openLinkIn = $fieldset->addField('link_open_in', 'select', array(
            'label'     => Mage::helper('cms')->__('Open Link In'),
            'title'     => Mage::helper('cms')->__('Open Link In'),
            'name'      => 'link_open_in',
            'required'  => true,
            'options'   => array(
                'current' => Mage::helper('cms')->__('Same Window'),
                'blank' => Mage::helper('cms')->__('New Window'),
            ),
        ));
        
                
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }
        
        $form->setValues($model->getData());
        $this->setForm($form);

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($enableLink->getHtmlId(), $enableLink->getName())
            ->addFieldMap($slideLink->getHtmlId(), $slideLink->getName())
            ->addFieldMap($openLinkIn->getHtmlId(), $openLinkIn->getName())
            ->addFieldDependence(
                    $slideLink->getName(),
                    $enableLink->getName(),
                    '1'
                )
            ->addFieldDependence(
                    $openLinkIn->getName(),
                    $enableLink->getName(),
                    '1'
                )
            );


        return parent::_prepareForm();
    }

}