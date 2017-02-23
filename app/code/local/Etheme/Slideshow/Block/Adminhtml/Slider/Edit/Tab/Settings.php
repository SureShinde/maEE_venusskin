<?php 

class Etheme_Slideshow_Block_Adminhtml_Slider_Edit_Tab_Settings extends Mage_Adminhtml_Block_Widget_Form {
    
    protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('settings');
        $slider = Mage::registry('current_slider');
        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('Settings')
        ));
        
        $fieldset->addField('delay', 'text', array(
            'label' => $this->__('Delay'),
            'required' => true,
            'name' => 'delay',
            'class' => 'required-entry validate-zero-or-greater',
            'note' => 'The time one slide stays on the screen in Milliseconds',
        ));
        
        $fieldset->addField('touch_enabled', 'select', array(
            'label'     => Mage::helper('cms')->__('Touch Enabled'),
            'title'     => Mage::helper('cms')->__('Touch Enabled'),
            'name'      => 'touch_enabled',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('cms')->__('Enabled'),
                '0' => Mage::helper('cms')->__('Disabled'),
            ),
            'note' => 'Enable Swipe Function on touch devices',
        ));
        
        $fieldset->addField('stop_on_hover', 'select', array(
            'label'     => Mage::helper('cms')->__('Stop On Hover'),
            'title'     => Mage::helper('cms')->__('Stop On Hover'),
            'name'      => 'stop_on_hover',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('cms')->__('Yes'),
                '0' => Mage::helper('cms')->__('No'),
            ),
            'note' => 'Stop the Timer when hovering the slider',
        ));
        
        $fieldset->addField('shuffle', 'select', array(
            'label'     => Mage::helper('cms')->__('Shuffle Mode'),
            'title'     => Mage::helper('cms')->__('Shuffle Mode'),
            'name'      => 'shuffle',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('cms')->__('On'),
                '0' => Mage::helper('cms')->__('Off'),
            ),
            'note' => 'Turn Shuffle Mode on and off! Will be randomized only once at the start.',
        ));
        
        /*
        $loadGfont = $fieldset->addField('load_gfont', 'select', array(
            'label'     => Mage::helper('cms')->__('Load Google Font'),
            'title'     => Mage::helper('cms')->__('Load Google Font'),
            'name'      => 'load_gfont',
            'required'  => false,
            'options'   => array(
                '1' => Mage::helper('cms')->__('Yes'),
                '0' => Mage::helper('cms')->__('No'),
            ),
            'note' => 'yes / no to load google font',
        ));
        
        $gfontFamily = $fieldset->addField('gfont_family', 'text', array(
            'label' => $this->__('Google Font'),
            'required' => false,
            'name' => 'gfont_family',
            'note' => 'The google font family to load',
        ));
        */

        $stopSlider = $fieldset->addField('stop_slider', 'select', array(
            'label'     => Mage::helper('cms')->__('Stop Slider'),
            'title'     => Mage::helper('cms')->__('Stop Slider'),
            'name'      => 'stop_slider',
            'required'  => false,
            'options'   => array(
                '1' => Mage::helper('cms')->__('On'),
                '0' => Mage::helper('cms')->__('Off'),
            ),
            'note' => 'On / Off to stop slider after some amount of loops / slides',
        ));
        
        $stopAfter = $fieldset->addField('stop_after_loops', 'text', array(
            'label' => $this->__('Stop After Loops'),
            'required' => false,
            'name' => 'stop_after_loops',
            'note' => 'Stop the slider after certain amount of loops. 0 related to the first loop.',
        ));
        
        $stopAt = $fieldset->addField('stop_at', 'text', array(
            'label' => $this->__('Stop At Slide'),
            'required' => false,
            'name' => 'stop_at',
            'note' => 'Stop the slider at the given slide',
        ));
        
        
        
        $form->setValues($slider->getData());
        //$form->addValues($this->_getFormData());
        $this->setForm($form);
        
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap($stopSlider->getHtmlId(), $stopSlider->getName())
                ->addFieldMap($stopAfter->getHtmlId(), $stopAfter->getName())
                ->addFieldMap($stopAt->getHtmlId(), $stopAt->getName())
                //->addFieldMap($loadGfont->getHtmlId(), $loadGfont->getName())
                //->addFieldMap($gfontFamily->getHtmlId(), $gfontFamily->getName())
                ->addFieldDependence(
                    $stopAfter->getName(),
                    $stopSlider->getName(),
                    '1'
                )
                ->addFieldDependence(
                    $stopAt->getName(),
                    $stopSlider->getName(),
                    '1'
                )
        );
        return parent::_prepareForm();
    }

}