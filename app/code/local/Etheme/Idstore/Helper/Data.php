<?php 
class Etheme_Idstore_Helper_Data extends Mage_Core_Helper_Data 
{
    
    /* General options */
    public function getSiteLayout() {
        return Mage::getStoreConfig('idstore_general/generaloptions/sitelayout', Mage::app()->getStore()->getId());
    }

    public function isResponsiveEnabled()
    {
        return (Mage::getStoreConfig('idstore_general/generaloptions/enable_responsive', Mage::app()->getStore()->getId()) == 1);
    }

    

    public function getLargeResolutionWidth()
    {
        return Mage::getStoreConfig('idstore_general/generaloptions/large_resolution', Mage::app()->getStore()->getId());
    }

    public function getHeaderType() {
        return Mage::getStoreConfig('idstore_general/generaloptions/header_type', Mage::app()->getStore()->getId());
    }

    public function useFixedHeader() {
        return (Mage::getStoreConfig('idstore_general/generaloptions/use_fixed_header', Mage::app()->getStore()->getId()) == 1);
    }

    public function getAdditionalLinkUrl() {
        return Mage::getStoreConfig('idstore_general/generaloptions/additional_nav', Mage::app()->getStore()->getId());
    }
    
    public function useGfontHeadings() {
        return (Mage::getStoreConfig('idstore_general/generaloptions/use_gfont_headings', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function gfontHeadings() {
        return Mage::getStoreConfig('idstore_general/generaloptions/gfont_headings', Mage::app()->getStore()->getId());
    }
    
    public function useGfontMenu() {
        return (Mage::getStoreConfig('idstore_general/generaloptions/use_gfont_menu', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function gfontMenu() {
        return Mage::getStoreConfig('idstore_general/generaloptions/gfont_menu', Mage::app()->getStore()->getId());
    }
    
    public function backToTop() {
         return Mage::getStoreConfig('idstore_general/generaloptions/topbtn', Mage::app()->getStore()->getId());
    }
    
    public function ajaxIsEnabled() {
        return (Mage::getStoreConfig('idstore_general/generaloptions/enable_ajax', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function googleMap() {
        return Mage::getStoreConfig('idstore_general/generaloptions/google_ltd', Mage::app()->getStore()->getId());
    }

    public function useCustomCss() {
        return (Mage::getStoreConfig('idstore_general/generaloptions/use_custom_css', Mage::app()->getStore()->getId()) == 1);
    }

    public function getCustomCss() {
        return 'css/' . Mage::getStoreConfig('idstore_general/generaloptions/custom_css_file', Mage::app()->getStore()->getId());
    }
    
    /* Navigation */
    
    public function menuType() {
        return Mage::getStoreConfig('idstore_general/navigation/menutype', Mage::app()->getStore()->getId());   
    }
    public function mobileMenuType() {
        return Mage::getStoreConfig('idstore_general/navigation/mobile_menutype', Mage::app()->getStore()->getId());   
    }
    
    public function getHomeLink() {
        return Mage::getStoreConfig('idstore_general/navigation/home_link', Mage::app()->getStore()->getId());
    }
    
    public function additionalLink() {
        return Mage::getStoreConfig('idstore_general/navigation/additional_nav', Mage::app()->getStore()->getId());
    }
    
    public function additionalLinkUrl() {
        return Mage::getStoreConfig('idstore_general/navigation/additional_nav_href', Mage::app()->getStore()->getId());
    }
    
    /* Background */
    
    public function getBgPattern() {
        return Mage::getStoreConfig('idstore_general/background/pattern', Mage::app()->getStore()->getId());
    }
    
    public function getMainColor() {
        return Mage::getStoreConfig('idstore_general/background/maincolor', Mage::app()->getStore()->getId());
    }
    
    public function bgRepeat() {
        return Mage::getStoreConfig('idstore_general/background/bg_repeat', Mage::app()->getStore()->getId());
    }
    
    public function bgAttachment() {
        return Mage::getStoreConfig('idstore_general/background/bg_attachment', Mage::app()->getStore()->getId());
    }

    public function bgExpanding() {
        return Mage::getStoreConfig('idstore_general/background/bg_expanding', Mage::app()->getStore()->getId());
    }
    
    public function bgPositionX() {
        Mage::getStoreConfig('idstore_general/background/bg_position_x', Mage::app()->getStore()->getId());
    }
    
    public function bgPositionY() {
        return Mage::getStoreConfig('idstore_general/background/bg_position_y', Mage::app()->getStore()->getId());
    }
    
    /* SLIDESHOW */
    
    public function useSlideshow() {
        return (Mage::getStoreConfig('idstore_general/slideshow/use_slideshow', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function slideshowAutoplay() {
        return (Mage::getStoreConfig('idstore_general/slideshow/autoplay', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function slideshowSpeed() {
        return Mage::getStoreConfig('idstore_general/slideshow/speed', Mage::app()->getStore()->getId());
    }
    
    /* CATEGORY PAGE */
     
    public function useHoverSwap() {
        return (Mage::getStoreConfig('idstore_general/product_list/hover_swap', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function catPageLayout() {
        return Mage::getStoreConfig('idstore_general/product_list/page_layout', Mage::app()->getStore()->getId());
    }
    
    public function isSidebarRight() {
        return Mage::getStoreConfig('idstore_general/product_list/sidebar_position', Mage::app()->getStore()->getId() == 1);
    }
    
    public function gridColumnCount() {
        return Mage::getStoreConfig('idstore_general/product_list/column_count', Mage::app()->getStore()->getId());
    }
    
    public function layerIsOpened() {
        return (Mage::getStoreConfig('idstore_general/product_list/layer', Mage::app()->getStore()->getId()) == 2);
    }
    
    public function useNewLabel() {
        return (Mage::getStoreConfig('idstore_general/product_list/new_label', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function useSaleLabel() {
        return (Mage::getStoreConfig('idstore_general/product_list/sale_label', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function useAddtoButtons() {
        return (Mage::getStoreConfig('idstore_general/product_list/addto', Mage::app()->getStore()->getId()) == 1);
    }
    
    /* Product page */
    
    public function getProductLayout() {
        return Mage::getStoreConfig('idstore_general/productpage/layout', Mage::app()->getStore()->getId());
    }
    
    public function getZoomType() {
        return Mage::getStoreConfig('idstore_general/productpage/zoom_type', Mage::app()->getStore()->getId());
    }
    
    public function useCarousel() {
        return (Mage::getStoreConfig('idstore_general/productpage/use_carousel', Mage::app()->getStore()->getId()) == 1);
    }

    public function isSizeguideEnabled()
    {
        return (Mage::getStoreConfig('idstore_general/productpage/use_sizeguide', Mage::app()->getStore()->getId()) == 1);
    }

    public function getSizeguideImageSrc()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'wysiwyg/idstore/'.Mage::getStoreConfig('idstore_general/productpage/sizeguide_image', Mage::app()->getStore()->getId());
    }
    
    /* footer */
    
    public function showFooterLinks() {
        return (Mage::getStoreConfig('idstore_general/footer/show_links', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function useTwitter() {
        return (Mage::getStoreConfig('idstore_general/footer/use_twitter', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function twitterName() {
        return Mage::getStoreConfig('idstore_general/footer/twitter_username', Mage::app()->getStore()->getId());
    }
    
    public function twitterCount() {
        return Mage::getStoreConfig('idstore_general/footer/twitter_count', Mage::app()->getStore()->getId());
    }
    
    public function useFlickr() {
        return (Mage::getStoreConfig('idstore_general/footer/use_flickr', Mage::app()->getStore()->getId()) == 1);
    }
    
    public function flickrUsername() {
        return Mage::getStoreConfig('idstore_general/footer/flickr_username', Mage::app()->getStore()->getId());
    }
    
    public function flickrApiKey() {
        return Mage::getStoreConfig('idstore_general/footer/flickr_api_key', Mage::app()->getStore()->getId());
    }
    
    public function flickrCount() {
        return Mage::getStoreConfig('idstore_general/footer/flickr_count', Mage::app()->getStore()->getId());
    }
    
    /* colors */
    public function getActiveColor() {
        return Mage::getStoreConfig('idstore_general/colors/active_color');
    }
    public function getHoverColor() {
        return Mage::getStoreConfig('idstore_general/colors/button_hover');
    }
    /* sidebars */
    public function hasProductSidebar() {
		$staticBlock = Mage::getModel('cms/block')->load('product_sidebar');
		// Check if block is active
		if($staticBlock->getIsActive() == 1) {
		    return true;
		}
		return false;
    }

    public function jsString($str) {
        return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
    }
}