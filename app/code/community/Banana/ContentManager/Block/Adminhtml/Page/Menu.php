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

class Banana_ContentManager_Block_Adminhtml_Page_Menu extends Mage_Adminhtml_Block_Page_Menu
{

    /**
     * Retrieve Adminhtml Menu array
     *
     * @return array
     */
    public function getMenuArray()
    {
        $menu = $this->_buildMenuArray();
        $this->_addCctMenus($menu);
        return $menu;
    }
    
    /**
     * Add CT menus to the Admin menu
     */
    private function _addCctMenus(&$menu)
    {
        //Retrieve CT collection
        $collection = Mage::getModel('contentmanager/contenttype')->getCollection();
        if($collection->count() > 0)
        {
            //Add Menus
            $i = 1;
            $hasCctShowed = false;
            foreach($collection as $contentType)
            {
                $aclResource = 'admin/contentmanager/content_'.$contentType->getIdentifier();
                $aclResourceAll = 'admin/contentmanager/content_everything';
                
                if (!$this->_checkAcl($aclResource) && !$this->_checkAcl($aclResourceAll)) {
                    continue;
                }
                
                $menu['cms']['children']['contenttype_'.$contentType->getIdentifier()] = array(
                    'label' => $contentType->getTitle(),
                    'url' => Mage::helper("adminhtml")->getUrl("adminhtml/contenttype_content/",array("ct_id"=>$contentType->getId())),
                    'level' => 1,
                    'active' => 0,
                    'last' => ($i == $collection->count())?1:0
                );
                $hasCctShowed = true;
                $i++;
            }
            
            //Remove 'last' tag from CMS menu
            if($hasCctShowed)
            {
                foreach($menu['cms']['children'] as $key => &$children)
                {
                    if(!preg_match("/contenttype_/", $key))
                    {
                        unset($children['last']);                    
                    }
                }
            }
        }
            
    }

}
