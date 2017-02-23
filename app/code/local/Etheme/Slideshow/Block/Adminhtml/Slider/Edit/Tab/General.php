<?php 

class Etheme_Slideshow_Block_Adminhtml_Slider_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {
    
    protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');
        $slider = Mage::registry('current_slider');
        $fieldset = $form->addFieldset('general_form', array(
            'legend' => $this->__('General Setup')
        ));
        
        if ($slider->getId()) {
            
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
                'label' => $this->__('Slider ID: %s', Mage::registry('current_slider')->getId())
            ));
        }
        
        $fieldset->addField('name', 'text', array(
            'label' => $this->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));
        
        $fieldset->addField('identifier', 'text', array(
            'label'     => $this->__('Identifier'),
            'required'  => true,
            'name'      => 'identifier',
            'required'  => true,
            'class'     => 'validate-xml-identifier',
        ));
        
        $fieldset->addField('is_active', 'select', array(
            'label'     => $this->__('Status'),
            'title'     => $this->__('Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                1 => $this->__('Enabled'),
                0 => $this->__('Disabled'),
            ),
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
           $fieldset->addField('store_id', 'multiselect', array(
               'name'      => 'stores[]',
               'label'     => $this->__('Store View'),
               'title'     => $this->__('Store View'),
               'required'  => true,
               'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
           ));
          }
        else {
            $fieldset->addField('store_id', 'hidden', array(
               'name'      => 'stores[]',
               'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        
        $type = $fieldset->addField('slider_type', 'select', array(
            'label'     => $this->__('Type'),
            'title'     => $this->__('Type'),
            'name'      => 'slider_type',
            'required'  => true,
            'options'   => array(
                'fixed' => $this->__('Fixed'),
                'responsitive' => $this->__('Custom'),
                'fullwidth' => $this->__('Auto Resoponsive'),
                'fullscreen' => $this->__('Full Screen')
            ),
        ));
                
        $gridWidth = $fieldset->addField('grid_width', 'text', array(
            'label' => $this->__('Grid Width'),
            'name' => 'grid_width',
        ));

        $gridHeight = $fieldset->addField('grid_height', 'text', array(
            'label' => $this->__('Grid Height'),
            'name' => 'grid_height',
        ));
        
        $screenWidth1 = $fieldset->addField('screen_width1', 'text', array(
            'label' => $this->__('Screen Width1'),
            'name' => 'screen_width1',
        ));
        
        $sliderWidth1 = $fieldset->addField('slider_width1', 'text', array(
            'label' => $this->__('Slider Width1'),
            'name' => 'slider_width1',
        ));
        
        $screenWidth2 = $fieldset->addField('screen_width2', 'text', array(
            'label' => $this->__('Screen Width2'),
            'name' => 'screen_width2',
        ));
        
        $sliderWidth2 = $fieldset->addField('slider_width2', 'text', array(
            'label' => $this->__('Slider Width2'),
            'name' => 'slider_width2',
        ));
        
        
        $screenWidth3 = $fieldset->addField('screen_width3', 'text', array(
            'label' => $this->__('Screen Width3'),
            'name' => 'screen_width3',
        ));
        
        $sliderWidth3 = $fieldset->addField('slider_width3', 'text', array(
            'label' => $this->__('Slider Width3'),
            'name' => 'slider_width3',
        ));
        
        
        $screenWidth4 = $fieldset->addField('screen_width4', 'text', array(
            'label' => $this->__('Screen Width4'),
            'name' => 'screen_width4',
        ));
        
        $sliderWidth4 = $fieldset->addField('slider_width4', 'text', array(
            'label' => $this->__('Slider Width4'),
            'name' => 'slider_width4',
        ));
        
        
        $screenWidth5 = $fieldset->addField('screen_width5', 'text', array(
            'label' => $this->__('Screen Width5'),
            'name' => 'screen_width5',
        ));
        
        $sliderWidth5 = $fieldset->addField('slider_width5', 'text', array(
            'label' => $this->__('Slider Width5'),
            'name' => 'slider_width5',
        ));
        
        
        $screenWidth6 = $fieldset->addField('screen_width6', 'text', array(
            'label' => $this->__('Screen Width6'),
            'name' => 'screen_width6',
        ));
        
        $sliderWidth6 = $fieldset->addField('slider_width6', 'text', array(
            'label' => $this->__('Slider Width6'),
            'name' => 'slider_width6',
        ));

        $loadGfont = $fieldset->addField('load_gfont', 'select', array(
            'label' => $this->__('Load Google Font'),
            'title' => $this->__('Load Google Font'),
            'name' => 'load_gfont',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            )
        ));
        
        $gfontFamily = $fieldset->addField('gfont_family', 'text', array(
            'label' => $this->__('Google font family'),
            'name' => 'gfont_family',
        ));
        
        
        $form->setValues($slider->getData());
        //$form->addValues($this->_getFormData());
        $this->setForm($form);
        
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($type->getHtmlId(), $type->getName())
            ->addFieldMap($gridWidth->getHtmlId(), $gridWidth->getName()) 
            ->addFieldMap($gridHeight->getHtmlId(), $gridHeight->getName())
            ->addFieldMap($screenWidth1->getHtmlId(), $screenWidth1->getName())
            ->addFieldMap($sliderWidth1->getHtmlId(), $sliderWidth1->getName())
            ->addFieldMap($screenWidth2->getHtmlId(), $screenWidth2->getName())
            ->addFieldMap($sliderWidth2->getHtmlId(), $sliderWidth2->getName())
            ->addFieldMap($screenWidth3->getHtmlId(), $screenWidth3->getName())
            ->addFieldMap($sliderWidth3->getHtmlId(), $sliderWidth3->getName())
            ->addFieldMap($screenWidth4->getHtmlId(), $screenWidth4->getName())
            ->addFieldMap($sliderWidth4->getHtmlId(), $sliderWidth4->getName())
            ->addFieldMap($screenWidth5->getHtmlId(), $screenWidth5->getName())
            ->addFieldMap($sliderWidth5->getHtmlId(), $sliderWidth5->getName())
            ->addFieldMap($screenWidth6->getHtmlId(), $screenWidth6->getName())
            ->addFieldMap($sliderWidth6->getHtmlId(), $sliderWidth6->getName())
            ->addFieldMap($gfontFamily->getHtmlId(), $gfontFamily->getName())
            ->addFieldMap($loadGfont->getHtmlId(), $loadGfont->getName())
            ->addFieldDependence(
                $screenWidth1->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth1->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $screenWidth2->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth2->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $screenWidth3->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth3->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $screenWidth4->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth4->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $screenWidth5->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth5->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $screenWidth6->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $sliderWidth6->getName(),
                $type->getName(),
                'responsitive'
            )
            ->addFieldDependence(
                $gfontFamily->getName(),
                $loadGfont->getName(),
                '1'
            )
        );
        
        return parent::_prepareForm();
    }

}