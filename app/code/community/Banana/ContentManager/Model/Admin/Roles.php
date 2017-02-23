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
 * @version		1.2.4
 */

class Banana_ContentManager_Model_Admin_Roles extends Mage_Admin_Model_Roles
{
    
    

    /**
     * Return tree of acl resources
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesTree()
    {
        $result = $this->_buildResourcesArray(null, null, null, null, true);
        $this->_addCctMenusXml($result);
        return $result;
    }

    /**
     * Return list of acl resources
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesList()
    {
        $result = $this->_buildResourcesArray();
        $this->_addCctMenus($result);
        return $result;
    }

    /**
     * Return list of acl resources in 2D format
     *
     * @return array|null|Varien_Simplexml_Element
     */
    public function getResourcesList2D()
    {
        $result = $this->_buildResourcesArray(null, null, null, true);
        $this->_addCctMenus2D($result);
        return $result;
    }
    

    /**
     * Add CT menus 
     */
    public function _addCctMenus2D(&$result)
    {
        $collection = Mage::getModel('contentmanager/contenttype')->getCollection();
        $result[] = 'admin/contentmanager/content_everything';
        foreach($collection as $contentType)
        {
            $result[] = 'admin/contentmanager/content_'.$contentType->getIdentifier();
        }
        
        return $result;
    }    
    
    /**
     * Add CT menus 
     */
    public function _addCctMenus(&$result)
    {
        $collection = Mage::getModel('contentmanager/contenttype')->getCollection();
        $result['admin/contentmanager/content_everything'] = array(
            'name' => 'Content Manager - All content types',
            'level' => 6
        );            
        foreach($collection as $contentType)
        {
            $result['admin/contentmanager/content_'.$contentType->getIdentifier()] = array(
                'name' => $contentType->getTitle(),
                'level' => 6
            );            
        }
        
        return $result;
    }
    
    /**
     * Add CT menus 
     */
    public function _addCctMenusXml(&$result)
    {
        if(!isset($result->admin) || !isset($result->admin->children) || !isset($result->admin->children->cms))
        {
            return;
        }
        
        $collection = Mage::getModel('contentmanager/contenttype')->getCollection();
        
        //add everything acl
        $element = new Varien_Simplexml_Element('<content_everything />');
        $element->addChild('title', 'Content Manager - All content types');
        $element->addChild('sort_order', 9);
        $element->addAttribute("aclpath", 'admin/contentmanager/content_everything');
        $element->addAttribute("module_c", 'contentmanager');
        $result->admin->children->cms->children->appendChild($element);  
        
        foreach($collection as $contentType)
        {
            $element = new Varien_Simplexml_Element('<content_'.$contentType->getIdentifier().' />');
            $element->addChild('title', $contentType->getTitle());
            $element->addChild('sort_order', 10);
            $element->addAttribute("aclpath", 'admin/contentmanager/content_'.$contentType->getIdentifier());
            $element->addAttribute("module_c", 'contentmanager');
            
            $result->admin->children->cms->children->appendChild($element);
        }
    }
    
}
