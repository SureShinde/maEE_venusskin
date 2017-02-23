<?php

class Banana_ContentManager_Block_Adminhtml_Content_Renderer_Template extends Mage_Adminhtml_Block_Abstract
{
    public function setRendererTemplate($renderer)
    {
        $this->setTemplate('banana/contentmanager/grid/'.$renderer.'.phtml');
    }
    
    public function createButton($label, $url, $classes = "")
    {
        return '<button title="'.$label.'" type="button" class="scalable '.$classes.'" onclick="setLocation(\''.$url.'\')" style=""><span><span><span>'.$label.'</span></span></span></button>';
    }
    
}

