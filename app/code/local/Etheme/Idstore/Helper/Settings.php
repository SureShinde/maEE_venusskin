<?php 
class Etheme_Idstore_Helper_Settings extends Mage_Core_Helper_Abstract
{
    
    public function getAdditionalLinkUrl() {
        return Mage::getStoreConfig('idstore_general/generaloptions/additional_nav', Mage::app()->getStore()->getId());
    }
    
    public function getSomething() {
        return "";
    }
    
    
    public function getOption($themeOption) {
    switch ($themeOption) {
     
     /* GENERAL */
     
     case 'use_gfont_headings':
       return (Mage::getStoreConfig('idstore_general/generaloptions/use_gfont_headings', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'gfont_headings':
        return Mage::getStoreConfig('idstore_general/generaloptions/gfont_headings', Mage::app()->getStore()->getId());
     break;
     case 'use_gfont_menu':
       return (Mage::getStoreConfig('idstore_general/generaloptions/use_gfont_menu', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'gfont_menu':
        return Mage::getStoreConfig('idstore_general/generaloptions/gfont_menu', Mage::app()->getStore()->getId());
     break;
     case 'topbanner':
       return Mage::getStoreConfig('idstore_general/generaloptions/topbanner', Mage::app()->getStore()->getId());
     break;     
     case 'phones':
       return Mage::getStoreConfig('idstore_general/generaloptions/phones', Mage::app()->getStore()->getId());
     break;
     
     case 'topbtn':
       return (Mage::getStoreConfig('idstore_general/generaloptions/topbtn', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'enable_ajax':
       return (Mage::getStoreConfig('idstore_general/generaloptions/enable_ajax', Mage::app()->getStore()->getId()) == 1);
     break;
     
     
     /* Navigation */
     
     case 'menutype':
       return Mage::getStoreConfig('idstore_general/navigation/menutype', Mage::app()->getStore()->getId());
     break;
     
     case 'home_link':
       return Mage::getStoreConfig('idstore_general/navigation/home_link', Mage::app()->getStore()->getId());
     break;
     
     case 'additionalLink':
       return Mage::getStoreConfig('idstore_general/navigation/additional_nav', Mage::app()->getStore()->getId());
     break;
     
     case 'additionalLinkUrl':
       return Mage::getStoreConfig('idstore_general/navigation/additional_nav_href', Mage::app()->getStore()->getId());
     break;
     
      /* BackGroung */
     
     case 'pattern':
       return Mage::getStoreConfig('idstore_general/background/pattern', Mage::app()->getStore()->getId());
     break; 
     case 'maincolor':
       return Mage::getStoreConfig('idstore_general/background/maincolor', Mage::app()->getStore()->getId());
     break;  
     case 'bg_repeat':
       return Mage::getStoreConfig('idstore_general/background/bg_repeat', Mage::app()->getStore()->getId());
     break;  
     case 'bg_attachment':
       return Mage::getStoreConfig('idstore_general/background/bg_attachment', Mage::app()->getStore()->getId());
     break;  
     case 'bg_position_x':
       return Mage::getStoreConfig('idstore_general/background/bg_position_x', Mage::app()->getStore()->getId());
     break; 
     case 'bg_position_y':
       return Mage::getStoreConfig('idstore_general/background/bg_position_y', Mage::app()->getStore()->getId());
     break;   
     
     /* SLIDESHOW */
     
     case 'use_slideshow':
       return (Mage::getStoreConfig('idstore_general/slideshow/use_slideshow', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'autoplay':
       return (Mage::getStoreConfig('idstore_general/slideshow/autoplay', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'speed':
       return Mage::getStoreConfig('idstore_general/slideshow/speed', Mage::app()->getStore()->getId());
     break;
     
     /* CATEGORY PAGE */
     
     case 'hover_swap':
       return (Mage::getStoreConfig('idstore_general/product_list/hover_swap', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'page_layout':
        return Mage::getStoreConfig('idstore_general/product_list/page_layout', Mage::app()->getStore()->getId());
     break;
     case 'column_count':
       return Mage::getStoreConfig('idstore_general/product_list/column_count', Mage::app()->getStore()->getId());
     break;
     case 'layer':
       return Mage::getStoreConfig('idstore_general/product_list/layer', Mage::app()->getStore()->getId());
     break;
     case 'new_label':
       return (Mage::getStoreConfig('idstore_general/product_list/new_label', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'sale_label':
       return (Mage::getStoreConfig('idstore_general/product_list/sale_label', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'addto':
       return (Mage::getStoreConfig('idstore_general/product_list/addto', Mage::app()->getStore()->getId()) == 1);
    
     /* SHARE */
     break;
     case 'use_share':
       return (Mage::getStoreConfig('idstore_general/share/use_share', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'share_code':
       return Mage::getStoreConfig('idstore_general/share/share_code', Mage::app()->getStore()->getId());
     break;
     
     /* PRODUCT PAGE */
     
     case 'layout':
       return Mage::getStoreConfig('idstore_general/productpage/layout', Mage::app()->getStore()->getId());
     break;
     case 'use_zoom':
       return (Mage::getStoreConfig('idstore_general/productpage/use_zoom', Mage::app()->getStore()->getId()) == 1);
     break;
     case 'use_carousel':
       return (Mage::getStoreConfig('idstore_general/productpage/use_carousel', Mage::app()->getStore()->getId()) == 1);
     break;
     
     /* Twitter */
     
     case 'twitter_name':
        return Mage::getStoreConfig('idstore_general/twitter/username');
     break;
     case 'twitter_count':
        return Mage::getStoreConfig('idstore_general/twitter/count');
     break;
     
     /* COLORS */
     
     case 'active_color':
       return Mage::getStoreConfig('idstore_general/colors/active_color');
     break;
     case 'button_hover':
       return Mage::getStoreConfig('idstore_general/colors/button_hover');
     break;
     
	}
    
    }
}
?>