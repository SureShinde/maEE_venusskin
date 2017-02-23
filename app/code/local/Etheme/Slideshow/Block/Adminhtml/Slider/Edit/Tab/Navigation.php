<?php

class Etheme_Slideshow_Block_Adminhtml_Slider_Edit_Tab_Navigation extends Mage_Adminhtml_Block_Widget_Form {

     protected function _prepareForm() {
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('navigation');
        $slider = Mage::registry('current_slider');
        
        /**
         * Navigation
         */
         
        $navFieldset = $form->addFieldset('navigation_navigation_form', array(
            'legend' => $this->__('Navigation')
        ));
        
        $navType = $navFieldset->addField('navigation_type', 'select', array(
            'label'     => $this->__('Navigation Type'),
            'title'     => $this->__('Navigation Type'),
            'name'      => 'navigation_type',
            'note' => 'Display type of the navigation bar (Default:none)',
            'options'   => array(
                'none' => $this->__('None'),
                'bullet' => $this->__('Bullet'),
                'thumb' => $this->__('Thumb'),
                'both' => $this->__('Both'),
            ),
        ));
        
        $navArrows = $navFieldset->addField('navigation_arrows', 'select', array(
            'label'     => $this->__('Navigation Arrows'),
            'title'     => $this->__('Navigation Arrows'),
            'name'      => 'navigation_arrows',
            'note' => 'Display position of the Navigation Arrows (By navigation Type Thumb arrows always centered or none visible)',
            'options'   => array(
                'nexttobullets' => $this->__('With Bullets'),
                'solo' => $this->__('Solo'),
                'none' => $this->__('None'),
            ), 
        ));
        
        $navFieldset->addField('navigation_style', 'select', array(
            'label'     => $this->__('Navigation Style'),
            'title'     => $this->__('Navigation Style'),
            'name'      => 'navigation_style',
            'note' => 'Look of the navigation bullets  ** If you choose navbar, we recommend to choose Navigation Arrows to nexttobullets',
            'options'   => array(
                'round' => $this->__('Round'),
                'navbar' => $this->__('Navbar'),
                'round-old' => $this->__('Old Round'),
                'square-old' => $this->__('Old Square'),
                'navbar-old' => $this->__('Old Navbar'),
            ),
        ));
        
        $alwaysShowNavi = $navFieldset->addField('navigation_always_on', 'select', array(
            'label'     => $this->__('Always Show Navigation'),
            'title'     => $this->__('Always Show Navigation'),
            'name'      => 'navigation_always_on',
            'note' => 'Always show the navigation and the thumbnails.',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));
        
        $hideNaviAfter = $navFieldset->addField('hide_thumbs', 'text', array(
            'label' => $this->__('Hide Navitagion After'),
            'name' => 'hide_thumbs',
            'value' => '200',
            'class' => 'validate-zero-or-greater',
            'note' => 'Time after that the Navigation and the Thumbs will be hidden(Default: 200 ms)',
        ));        
        
        $navHorAlign = $navFieldset->addField('navigation_align_hor', 'select', array(
            'label'     => $this->__('Navigation Horizontal Align'),
            'title'     => $this->__('Navigation Horizontal Align'),
            'name'      => 'navigation_align_hor',
            'note' => 'Horizontal Align of Bullets / Thumbnails',
            'options'   => array(
                'left' => $this->__('Left'),
                'center' => $this->__('Center'),
                'right' => $this->__('Right'),
            ),
        ));
        
        $navVertAlign = $navFieldset->addField('navigation_align_vert', 'select', array(
            'label'     => $this->__('Navigation Vertical Align'),
            'title'     => $this->__('Navigation Vertical Align'),
            'name'      => 'navigation_align_vert',
            'note' => 'Vertical Align of Bullets / Thumbnails',
            'options'   => array(
                'top' => $this->__('Top'),
                'center' => $this->__('Center'),
                'bottom' => $this->__('Bottom'),
            ),
        ));
        
        $navHorOffset = $navFieldset->addField('navigation_offset_hor', 'text', array(
            'label' => $this->__('Navigation Horizontal Offset'),
            'name' => 'navigation_offset_hor',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Horizontal position of Bullets / Thumbnails negative and positive direction',
        ));
        
        $navVertOffset = $navFieldset->addField('navigation_offset_vert', 'text', array(
            'label' => $this->__('Navigation Vertical Offset'),
            'name' => 'navigation_offset_vert',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Vertical  position of Bullets / Thumbnails negative and positive direction',
        ));
        
        $leftArrHorAlign = $navFieldset->addField('leftarrow_align_hor', 'select', array(
            'label'     => $this->__('Left Arrow Horizontal Align'),
            'title'     => $this->__('Left Arrow Horizontal Align'),
            'name'      => 'leftarrow_align_hor',
            'note' => 'Horizontal Align of left Arrow (only if arrow is not next to bullets)',
            'options'   => array(
                'left' => $this->__('Left'),
                'center' => $this->__('Center'),
                'right' => $this->__('Right'),
            ),
        ));
        
        $leftArrVertAlign = $navFieldset->addField('leftarrow_align_vert', 'select', array(
            'label'     => $this->__('Left Arrow Vertical Align'),
            'title'     => $this->__('Left Arrow Vertical Align'),
            'name'      => 'leftarrow_align_vert',
            'note' => 'Vertical Align of left Arrow (only if arrow is not next to bullets)',
            'options'   => array(
                'top' => $this->__('Top'),
                'center' => $this->__('Center'),
                'bottom' => $this->__('Bottom'),
            ),
        ));
        
        $leftArrHorOffset = $navFieldset->addField('leftarrow_offset_hor', 'text', array(
            'label' => $this->__('Left Arrow Horizontal Offset'),
            'title' => $this->__('Left Arrow Horizontal Offset'),
            'name' => 'leftarrow_offset_hor',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Horizontal position of of left Arrow  negative and positive direction',
        ));
        
        $leftArrVertOffset = $navFieldset->addField('leftarrow_offset_vert', 'text', array(
            'label' => $this->__('Left Arrow Vertical Offset'),
            'title' => $this->__('Left Arrow Vertical Offset'),
            'name' => 'leftarrow_offset_vert',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Vertical position of of left Arrow negative and positive direction',
        ));
        
        
        $rightArrHorAlign = $navFieldset->addField('rightarrow_align_hor', 'select', array(
            'label'     => $this->__('Right Arrow Horizontal Align'),
            'title'     => $this->__('Right Arrow Horizontal Align'),
            'name'      => 'rightarrow_align_hor',
            'note' => 'Horizontal Align of right Arrow (only if arrow is not next to bullets)',
            'options'   => array(
                'left' => $this->__('Left'),
                'center' => $this->__('Center'),
                'right' => $this->__('Right'),
            ),
        ));
        
        $rightArrVertAlign = $navFieldset->addField('rightarrow_align_vert', 'select', array(
            'label'     => $this->__('Right Arrow Vertical Align'),
            'title'     => $this->__('Right Arrow Vertical Align'),
            'name'      => 'rightarrow_align_vert',
            'note' => 'Vertical Align of right Arrow (only if arrow is not next to bullets)',
            'options'   => array(
                'top' => $this->__('Top'),
                'center' => $this->__('Center'),
                'bottom' => $this->__('Bottom'),
            ),
        ));
        
        $rightArrHorOffset = $navFieldset->addField('rightarrow_offset_hor', 'text', array(
            'label' => $this->__('Right Arrow Horizontal Offset'),
            'title' => $this->__('Right Arrow Horizontal Offset'),
            'name' => 'rightarrow_offset_hor',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Horizontal position of of right Arrow negative and positive direction',
        ));
        
        $rightArrVertOffset = $navFieldset->addField('rightarrow_offset_vert', 'text', array(
            'label' => $this->__('Right Arrow Vertical Offset'),
            'title' => $this->__('Right Arrow Vertical Offset'),
            'name' => 'rightarrow_offset_vert',
            'class' => 'validate-zero-or-greater',
            'note' => 'Offset from current Vertical position of of right Arrow negative and positive direction',
        ));
        
        /**
         * Thumbnails
         */
        
        $thumbnailsFieldset = $form->addFieldset('navigation_thumbnails_form', array(
            'legend' => $this->__('Thumbnails')
        ));
        
        $thumbnailsFieldset->addField('thumb_width', 'text', array(
            'label' => $this->__('Thumb Width'),
            'name' => 'thumb_width',
            'class' => 'validate-zero-or-greater',
            'note' => 'The basic Width of one Thumbnail (only if thumb is selected)',
        ));
        
        $thumbnailsFieldset->addField('thumb_height', 'text', array(
            'label' => $this->__('Thumb Height'),
            'name' => 'thumb_height',
            'class' => 'validate-zero-or-greater',
            'note' => 'the basic Height of one Thumbnail (only if thumb is selected)',
        ));
        
        $thumbnailsFieldset->addField('thumb_amount', 'text', array(
            'label' => $this->__('Thumb Amount'),
            'name' => 'thumb_amount',
            'class' => 'validate-zero-or-greater',
            'note' => 'the amount of the Thumbs visible same time (only if thumb is selected)',
        ));
        
        $form->setValues($slider->getData());
        //$form->addValues($this->_getFormData());
        $this->setForm($form);
        
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($navType->getHtmlId(), $navType->getName())
            ->addFieldMap($navArrows->getHtmlId(), $navArrows->getName())
            ->addFieldMap($alwaysShowNavi->getHtmlId(), $alwaysShowNavi->getName())
            ->addFieldMap($hideNaviAfter->getHtmlId(), $hideNaviAfter->getName())
            ->addFieldMap($navHorAlign->getHtmlId(), $navHorAlign->getName())
            ->addFieldMap($navVertAlign->getHtmlId(), $navVertAlign->getName())
            ->addFieldMap($navHorOffset->getHtmlId(), $navHorOffset->getName())
            ->addFieldMap($navVertOffset->getHtmlId(), $navVertOffset->getName())
            ->addFieldMap($leftArrHorAlign->getHtmlId(), $leftArrHorAlign->getName())
            ->addFieldMap($leftArrVertAlign->getHtmlId(), $leftArrVertAlign->getName())
            ->addFieldMap($leftArrHorOffset->getHtmlId(), $leftArrHorOffset->getName())
            ->addFieldMap($leftArrVertOffset->getHtmlId(), $leftArrVertOffset->getName())
            ->addFieldMap($rightArrHorAlign->getHtmlId(), $rightArrHorAlign->getName())
            ->addFieldMap($rightArrVertAlign->getHtmlId(), $rightArrVertAlign->getName())
            ->addFieldMap($rightArrHorOffset->getHtmlId(), $rightArrHorOffset->getName())
            ->addFieldMap($rightArrVertOffset->getHtmlId(), $rightArrVertOffset->getName())
            ->addFieldDependence(
                $navHorAlign->getName(),
                $navType->getName(),
                array('bullet', 'thumb', 'both')
            )
            ->addFieldDependence(
                $navVertAlign->getName(),
                $navType->getName(),
                array('bullet', 'thumb', 'both')
            )
            ->addFieldDependence(
                $navHorOffset->getName(),
                $navType->getName(),
                array('bullet', 'thumb', 'both')
            )
            ->addFieldDependence(
                $navVertOffset->getName(),
                $navType->getName(),
                array('bullet', 'thumb', 'both')
            )
            ->addFieldDependence(
                $leftArrHorAlign->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $leftArrVertAlign->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $leftArrHorOffset->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $leftArrVertOffset->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $rightArrHorAlign->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $rightArrVertAlign->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $rightArrHorOffset->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            )
            ->addFieldDependence(
                $rightArrVertOffset->getName(),
                $navArrows->getName(),
                array('nexttobullets', 'solo')
            ) 
            ->addFieldDependence(
                $hideNaviAfter->getName(),
                $alwaysShowNavi->getName(),
                '0'
            )
        );
        
        return parent::_prepareForm(); 
    }
    
}