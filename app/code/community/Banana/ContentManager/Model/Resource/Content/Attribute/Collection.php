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

class Banana_ContentManager_Model_Resource_Content_Attribute_Collection
    extends Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
{
    /**
     * Main select object initialization.
     * Joins catalog/eav_attribute table
     *
     * @return Banana_ContentManager_Model_Resource_Category_Attribute_Collection
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where('main_table.entity_type_id=?', Mage::getModel('eav/entity')->setType(Banana_ContentManager_Model_Content::ENTITY)->getTypeId())
            ->join(
                array('additional_table' => $this->getTable('contentmanager/eav_attribute')),
                'additional_table.attribute_id = main_table.attribute_id'
            );
        return $this;
    }

    /**
     * Specify attribute entity type filter
     *
     * @param int $typeId
     * @return Banana_ContentManager_Model_Resource_Category_Attribute_Collection
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }
}
