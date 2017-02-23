<?php class Etheme_Idstore_Helper_Category extends Mage_Core_Helper_Abstract {
    
    
    private $columns, $useSidebar, $imgWidth, $imgHeight, $spanClass;
    
    /**
     * used in local.xml to change template to option from backend
     * */
    public function template() {
        
        $sidebar = (Mage::getStoreConfig('idstore_general/product_list/page_layout', Mage::app()->getStore()->getId()) == 1);
        if ($sidebar)
            return 'page/2columns-left.phtml';
        else
            return 'page/1column.phtml';
    }
    
    public function columns() {
        
        return Mage::getStoreConfig('idstore_general/product_list/column_count', Mage::app()->getStore()->getId());
    }
    

    public function setOptions($cols, $template) {
        
        $this->columns = $cols;
        if ($template == 'page/1column.phtml')
            $this->useSidebar = false;
        else
            $this->useSidebar = true;
        switch ($this->columns) {
            case 3: 
                $this->imgWidth = 200;
                $this->imgHeight = 200;
                $this->spanClass = 'span3';
                break;
            case 4: 
                if ($this->useSidebar) {
                    $this->imgWidth = 220;
                    $this->imgHeight = 220;
                    $this->spanClass = 'span3';
                }
                else {
                    $this->imgWidth = 240;
                    $this->imgHeight = 240;
                    $this->spanClass = 'span3';
                }
                break;
            case 5: 
                if ($this->useSidebar) {
                    $this->imgWidth = 120;
                    $this->imgHeight = 120;
                    $this->spanClass = 'span3';
                }
                else {
                    $this->imgWidth = 140;
                    $this->imgHeight = 140;
                    $this->spanClass = 'span3';
                }
                break;
            default: //case 6
                $this->imgWidth = 100;
                $this->imgHeight = 100;
                $this->spanClass = 'span3';
        }
        //$this->layout = $template;
    }
    
    public function getImgWidth() {
        
        return $this->imgWidth;
    }
    
    public function getImgHeight() {
        return $this->imgHeight;
    }
    
    public function getSpanClass() {
        return $this->spanClass;
    }
    
    public function useSidebar() {
        return $this->useSidebar;
    }

}