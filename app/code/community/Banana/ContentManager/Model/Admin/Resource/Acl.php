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

class Banana_ContentManager_Model_Admin_Resource_Acl extends Mage_Admin_Model_Resource_Acl
{
    
    /**
     * Load ACL for the user
     *
     * @return Mage_Admin_Model_Acl
     */
    public function loadAcl()
    {
        $acl = Mage::getModel('admin/acl');

        Mage::getSingleton('admin/config')->loadAclResources($acl);
        
        //Banana - Add content ACL for CT extension
        $collection = Mage::getModel('contentmanager/contenttype')->getCollection();
        $parentName = 'admin/contentmanager';
        
        $acl->add(Mage::getModel('admin/acl_resource', $parentName.'/content_everything'), $parentName);
        foreach($collection as $contentType)
        {
            $acl->add(Mage::getModel('admin/acl_resource', $parentName.'/content_'.$contentType->getIdentifier()), $parentName);
        }
        
        //Banana - End Add content ACL for CT extension

        $roleTable   = $this->getTable('admin/role');
        $ruleTable   = $this->getTable('admin/rule');
        $assertTable = $this->getTable('admin/assert');

        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($roleTable)
            ->order('tree_level');

        $rolesArr = $adapter->fetchAll($select);

        $this->loadRoles($acl, $rolesArr);

        $select = $adapter->select()
            ->from(array('r' => $ruleTable))
            ->joinLeft(
                array('a' => $assertTable),
                'a.assert_id = r.assert_id',
                array('assert_type', 'assert_data')
            );

        $rulesArr = $adapter->fetchAll($select);

        $this->loadRules($acl, $rulesArr);
     
        return $acl;
    }

}
