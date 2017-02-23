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

class Banana_ContentManager_Model_Resource_Content extends Banana_ContentManager_Model_Resource_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $resource = Mage::getSingleton('core/resource');
        
        $this->setType(Banana_ContentManager_Model_Content::ENTITY)->setConnection(
            $resource->getConnection('contenttype_read'),
            $resource->getConnection('contenttype_write')
        );
    }

    /**
     * Set store Id
     *
     * @param integer $storeId
     * @return Banana_ContentManager_Model_Resource_Category
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Return store id
     *
     * @return integer
     */
    public function getStoreId()
    {
        if ($this->_storeId === null) {
            return Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }
    
    /**
     * Process content data before deleting
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Banana_ContentManager_Model_Resource_Content
     */
    protected function _beforeDelete(Varien_Object $object)
    {
        $condition = array(
            'entity_id = ?'     => (int) $object->getId(),
        );

        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_store'), $condition);

        return parent::_beforeDelete($object);
    }   
    
    /**
     * Delete current store link to this entity
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Banana_ContentManager_Model_Resource_Content
     */
    public function deleteCurrentStoreLink($entity_id, $store_id)
    {
        $condition = array(
            'entity_id = ?'     => (int) $entity_id,
            'store_id = ?'     => (int) $store_id
        );
        
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_store'), $condition);
    }  
    
    /**
     * Delete current attributes this entity for store
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Banana_ContentManager_Model_Resource_Content
     */
    public function deleteCurrentStoreAttributes($entity_id, $store_id, $key)
    {
        $condition = array(
            'entity_id = ?'     => (int) $entity_id,
            'store_id = ?'     => (int) $store_id,
            'attribute_id = ?' => (int) $this->getAttribute($key)->getId()
        );
        
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_datetime'), $condition);
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_decimal'), $condition);
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_int'), $condition);
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_text'), $condition);
        $this->_getWriteAdapter()->delete($this->getTable('contentmanager/contenttype_entity_varchar'), $condition);
    }
    
    /**
     * Check if a content is defined for a specific store_id
     * @param type $entity_id
     * @param type $store_id
     */
    public function existsForStore($entity_id, $store_id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('contentmanager/contenttype_entity_store'), 'store_id')
            ->where('store_id = :store_id')
            ->where('entity_id = :entity_id');

        $binds = array(
            ':store_id' => (int) $store_id,
            ':entity_id' => (int) $entity_id
        );

        return count($adapter->fetchCol($select, $binds)) > 0;
    }
    
    /**
     * Perform operations after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Banana_ContentManager_Model_Resource_Content
     */
    protected function _afterSave(Varien_Object $object)
    {
        if($object->getDoNotSaveStores() !== true)
        {
            $oldStores = $this->lookupStoreIds($object->getId());
            $newStores = array($object->getStoreId());

            $table  = $this->getTable('contentmanager/contenttype_entity_store');
            $insert = array_diff($newStores, $oldStores);
            
            if ($insert) {
                $data = array();

                foreach ($insert as $storeId) {
                    $data[] = array(
                        'entity_id'  => (int) $object->getId(),
                        'store_id' => (int) $storeId
                    );
                }

                $this->_getWriteAdapter()->insertMultiple($table, $data);
            }
        }

        return parent::_afterSave($object);
    }    

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('contentmanager/contenttype_entity_store'), 'store_id')
            ->where('entity_id = :entity_id');
        
        $binds = array(
            ':entity_id' => (int) $id
        );

        return $adapter->fetchCol($select, $binds);
    }

    /**
     * Check is attribute value empty
     *
     * @param Mage_Eav_Model_Entity_Attribute_Abstract $attribute
     * @param mixed $value
     * @return bool
     */
    protected function _isAttributeValueEmpty(Mage_Eav_Model_Entity_Attribute_Abstract $attribute, $value)
    {
        return false; //always save data
    }    
    
    /**
     * Retrieve default entity attributes
     *
     * @return array
     */
    protected function _getDefaultAttributes()
    {
        return array('entity_type_id', 'created_at', 'updated_at');
    }
}
