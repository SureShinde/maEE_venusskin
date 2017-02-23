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

class Banana_ContentManager_Model_Resource_Content_Collection extends Banana_ContentManager_Model_Resource_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('contentmanager/content');
    }
    
    protected function _initSelect()
    {
        if($this->getStoreId() > 0)
        {
            $adapter        = $this->getConnection();
            $joinCondition  = array(
                'store.entity_id = e.entity_id'
            );
            $this->getSelect()
                ->from(array('e' => $this->getEntity()->getEntityTable()))
                ->joinLeft(
                    array('store' => $this->getTable('contentmanager/contenttype_entity_store')),
                    implode(' AND ', $joinCondition),
                    array())            
                ->where($adapter->quoteInto('store.store_id = ? OR store.store_id = 0', (int) $this->getStoreId()))
                ->distinct();
        }
        else
        {
            $this->getSelect()
                ->from(array('e' => $this->getEntity()->getEntityTable()));
        }
        
        return $this;     
    } 
    
    /**
     * Retrieve attributes load select
     *
     * @param string $table
     * @param array|int $attributeIds
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getLoadAttributesSelect($table, $attributeIds = array())
    {
        if (empty($attributeIds)) {
            $attributeIds = $this->_selectAttributes;
        }
        $storeId = $this->getStoreId();

        if ($storeId) {

            $adapter        = $this->getConnection();
            $entityIdField  = $this->getEntity()->getEntityIdField();
            $joinCondition  = array(
                't_s.attribute_id = t_d.attribute_id',
                't_s.entity_id = t_d.entity_id',
                $adapter->quoteInto('t_s.store_id = ?', $storeId)
            );
            $select = $adapter->select()
                ->from(array('t_d' => $table), array($entityIdField, 'attribute_id'))
                ->joinLeft(
                    array('t_s' => $table),
                    implode(' AND ', $joinCondition),
                    array())
                ->where('t_d.entity_type_id = ?', $this->getEntity()->getTypeId())
                ->where("t_d.{$entityIdField} IN (?)", array_keys($this->_itemsById))
                ->where('t_d.attribute_id IN (?)', $attributeIds)
                ->where('t_d.store_id = ? OR t_d.store_id = 0', $this->getStoreId());
        } else {
            $select = parent::_getLoadAttributesSelect($table)
                ->where('store_id = ?', $this->getDefaultStoreId());
        }

        return $select;
    }    

    /**
     * Add store availability filter. Include availability product
     * for store website
     *
     * @param mixed $store
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addStoreFilter($store = null)
    {
        if ($store === null) {
            $store = $this->getStoreId();
        }
        $store = Mage::app()->getStore($store);
        $this->setStoreId($store);

        return $this;
    }
    
    /**
     * Add attribute filter to collection
     *
     * If $attribute is an array will add OR condition with following format:
     * array(
     *     array('attribute'=>'firstname', 'like'=>'test%'),
     *     array('attribute'=>'lastname', 'like'=>'test%'),
     * )
     *
     * @see self::_getConditionSql for $condition
     * @param Mage_Eav_Model_Entity_Attribute_Interface|integer|string|array $attribute
     * @param null|string|array $condition
     * @param string $operator
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        return parent::addAttributeToFilter($attribute, $condition, $joinType = 'left'); //inner -> left
    }    
}
