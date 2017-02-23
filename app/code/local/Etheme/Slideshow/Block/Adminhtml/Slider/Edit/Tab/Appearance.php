<?php

class Etheme_Slideshow_Block_Adminhtml_Slider_Edit_Tab_Appearance extends Mage_Adminhtml_Block_Widget_Form {

     protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('appearance');
        $slider = Mage::registry('current_slider');
        $positionFieldset = $form->addFieldset('appearance_position_form', array(
            'legend' => $this->__('Position')
        ));
        
        $positionFieldset->addField('slider_position', 'select', array(
            'label'     => $this->__('Position on the page'),
            'title'     => $this->__('Position on the page'),
            'name'      => 'slider_position',
            'note' => 'The position of the slider on the page, (float:left, float:right, margin:0px auto;)',
            'options'   => array(
                'left' => $this->__('Left'),
                'center' => $this->__('Center'),
                'right' => $this->__('Right'),
            ),
        ));
        
        $positionFieldset->addField('margin_left', 'text', array(
            'label' => $this->__('Margin Left'),
            'name' => 'margin_left',
            'class' => 'validate-zero-or-greater',
            'note' => 'The left margin of the slider wrapper div',
        ));
        
        $positionFieldset->addField('margin_right', 'text', array(
            'label' => $this->__('Margin Right'),
            'name' => 'margin_right',
            'class' => 'alidate-zero-or-greater',
            'note' => 'The right margin of the slider wrapper div',
        ));
        
        $positionFieldset->addField('margin_top', 'text', array(
            'label' => $this->__('Margin Top'),
            'name' => 'margin_top',
            'class' => 'validate-zero-or-greater',
            'note' => 'The top margin of the slider wrapper div',
        ));
        
        $positionFieldset->addField('margin_bottom', 'text', array(
            'label' => $this->__('Margin Bottom'),
            'name' => 'margin_bottom',
            'class' => 'validate-zero-or-greater',
            'note' => 'The bottom margin of the slider wrapper div',
        ));
        
        $appearanceFieldset = $form->addFieldset('appearance_appearance_form', array(
            'legend' => $this->__('Appearance')
        ));
        
        $appearanceFieldset->addField('shadow_type', 'select', array(
            'label'     => $this->__('Shadow Type'),
            'title'     => $this->__('Shadow Type'),
            'name'      => 'shadow_type',
            'note' => 'The Shadow display underneath the banner',
            'options'   => array(
                '0' => $this->__('No Shadow'),
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ),
        ));
        
        $showTimerline = $appearanceFieldset->addField('show_timerline', 'select', array(
            'label'     => $this->__('Show Timerline'),
            'title'     => $this->__('Show Timerline'),
            'name'      => 'show_timerline',
            'note' => 'Show the top running timer line',
            'options'   => array(
                '0' => $this->__('Hide'),
                '1' => $this->__('Show'),
            ),
        ));
        
        $timerlinePosition = $appearanceFieldset->addField('timerline_position', 'select', array(
            'label'     => $this->__('Timerline Postion'),
            'title'     => $this->__('Timerline Postion'),
            'name'      => 'timerline_position',
            'note' => 'Set the timer line position to top or bottom',
            'options'   => array(
                'top' => $this->__('Top'),
                'bottom' => $this->__('Bottom'),
            ),
        ));
        
        $appearanceFieldset->addField('background_color', 'text', array(
            'label' => $this->__('Background Color'),
            'name' => 'background_color',
            'note' => 'Slider wrapper div background color, for transparent slider, leave empty.',
        ));
        
        $appearanceFieldset->addField('padding', 'text', array(
            'label' => $this->__('Padding'),
            'name' => 'padding',
            'class' => 'validate-zero-or-greater',
            'note' => 'The wrapper div padding, if it has value, then together with background color it it will make border around the slider.',
        ));
        
        $showBgImage = $appearanceFieldset->addField('show_bg_image', 'select', array(
            'label'     => $this->__('Show Background Image'),
            'title'     => $this->__('Show Background Image'),
            'name'      => 'show_bg_image',
            'note' => 'yes / no to put background image to the main slider wrapper.',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));
        
        $bgImageUrl = $appearanceFieldset->addField('bg_image_url', 'text', array(
            'label' => $this->__('Background Image Url'),
            'name' => 'bg_image_url',
            'note' => 'The background image that will be on the slider wrapper. Will be shown at slider preloading.',
        ));
        
        $mobileFieldset = $form->addFieldset('appearance_mobile_form', array(
            'legend' => $this->__('Mobile')
        ));
        
        $mobileFieldset->addField('hide_slider_under', 'text', array(
            'label' => $this->__('Hide Slider Under Width'),
            'name' => 'hide_slider_under',
            'class' => 'validate-zero-or-greater',
        ));
        
        /*
        $mobileFieldset->addField('hide_defined_layers_under', 'text', array(
            'label' => $this->__('Hide Slider Under Width'),
            'name' => 'hide_defined_layers_under',
            'class' => 'validate-zero-or-greater',
        ));
        */
        
        $mobileFieldset->addField('hide_all_layers_under', 'text', array(
            'label' => $this->__('Hide All Layers Under Width'),
            'name' => 'hide_all_layers_under',
            'class' => 'validate-zero-or-greater',
        ));
        
        $form->setValues($slider->getData());
        //$form->addValues($this->_getFormData());
        $this->setForm($form);
        
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($showTimerline->getHtmlId(), $showTimerline->getName())
            ->addFieldMap($timerlinePosition->getHtmlId(), $timerlinePosition->getName())
            ->addFieldMap($showBgImage->getHtmlId(), $showBgImage->getName())
            ->addFieldMap($bgImageUrl->getHtmlId(), $bgImageUrl->getName())
            ->addFieldDependence(
                $timerlinePosition->getName(),
                $showTimerline->getName(),
                '1'
            )
            ->addFieldDependence(
                $bgImageUrl->getName(),
                $showBgImage->getName(),
                '1'
            )
        );
        
        return parent::_prepareForm();
    }
    
    
}