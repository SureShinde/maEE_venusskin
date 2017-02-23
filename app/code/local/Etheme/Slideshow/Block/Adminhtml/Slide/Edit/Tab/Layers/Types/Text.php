<?php class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Types_Text extends 
      Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Types_Abstract
{
    
    public function __construct() 
    {
        parent::__construct();
        $this->setTemplate('etheme/slideshow/edit/layers/type/text.phtml');
    }

    public function getStyleSelect() {
        
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId(). '_{{option_id}}_style',
                'class' => 'select select-slide-layer-style select-slide-layer-{{option_id}}'
            ))
            ->setName($this->getFieldName() . '[{{option_id}}][style]')
            ->setExtraParams('data-value="{{style}}"')
            ->setOptions(Mage::getSingleton('etheme_slideshow/system_config_source_slide_layers_style')->toOptionArray());
        return $select->getHtml();

    }

}