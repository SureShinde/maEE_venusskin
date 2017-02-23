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

class Banana_ContentManager_Model_Resource_Menu_Node extends Mage_Core_Model_Mysql4_Abstract
{
		public function _construct(){
			$this->_init('contentmanager/menu_node', 'node_id');
		}
		
 		/**
 		 * Perform operations before object save
 		 *
 		 * @param Mage_Cms_Model_Block $object
 		 * @return Mage_Cms_Model_Resource_Block
 		 */
 		protected function _beforeSave(Mage_Core_Model_Abstract $object)
 		{
 		    if (! $object->getId()) {
 		        $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
 		    }
 		    $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
 		    return $this;
 		}
}

