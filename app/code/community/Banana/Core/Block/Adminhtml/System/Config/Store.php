<?php
/**
 * Banana ContentManager Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@advancedcontentmanager.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.advancedcontentmanager.com)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.1.1
 */

class Banana_Core_Block_Adminhtml_System_Config_Store
    extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
	
    public function render(Varien_Data_Form_Element_Abstract $fieldset)
    {
        return $this->toHtml();
    }
    
    protected function _toHtml(){
    	return $this->getBananaStore();
    }
    
    public function getBananaStore()
    {
    	$response = Mage::app()->loadCache("banana_core_store");
    	if (!$response){
	    	$url = "http://www.advancedcontentmanager.com/distant-about";
	    	
	        $curl = new Varien_Http_Adapter_Curl();
	        $curl->setConfig(array('timeout' => 10));
	        $curl->write(Zend_Http_Client::GET, $url, '1.0');
	        
	        $response = $curl->read();
	
	        if ($response !== false) {
	            $response = preg_split('/^\r?$/m', $response, 2);
	            $response = trim($response[1]);
	            Mage::app()->saveCache($response, "banana_core_store", null, 86400);
	        }
	        else {
	            $response =  Mage::app()->loadCache("banana_core_store");
	            if (!$response) {
	                Mage::getSingleton('adminhtml/session')->addError(
	                	$this->__("Sorry but Banana addons website is not available. Please try again or contact magento@banana.fr")
	                );
	            }
	        }
	        $curl->close();
    	}
    	
    	$this->_data = $response;
	    return $this->_data;
    }
    
}
