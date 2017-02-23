<?php

class Etheme_Slideshow_Block_Test extends Mage_Adminhtml_Block_Widget {
    
    public function __construct()
    {
        parent::__construct();
        //My template here
        //$this->setTemplate('catalog/product/edit/options.phtml');
    }
    
    protected function _prepareLayout() 
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Option'),
                    'class' => 'add',
                    'id'    => 'add_new_defined_option'))
        );
        
        /*
        My custom option block
        $this->setChild('options_box',
            $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_options_option')
        );
        */
        return parent::_prepareLayout();
    }
    
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }
    
    //remove, phtml will be used
    public function _toHtml() 
    {
        $model = Mage::registry('current_slide');
        $some = $model->getSomeOption();
        /*
        $html = <<<INPUT
<input id="contentssome_option" name="some_option" value="$some" class=" input-text" type="text" />
INPUT;
*/
        $html = '<h1>test block </h1>';
        //return 'Hello my name is Test Block and welcome to Jackass';
        return $html;
    }
}