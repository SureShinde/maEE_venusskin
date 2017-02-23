<?php class Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Types_Video extends 
      Etheme_Slideshow_Block_Adminhtml_Slide_Edit_Tab_Layers_Types_Abstract 
{
    public function __construct() 
    {
        parent::__construct();
        $this->setTemplate('etheme/slideshow/edit/layers/type/video.phtml');
    }
}