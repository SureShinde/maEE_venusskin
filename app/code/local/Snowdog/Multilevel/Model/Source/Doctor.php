<?php

/**
 * Class Snowdog_Multilevel_Model_Source_Doctor
 */
class Snowdog_Multilevel_Model_Source_Doctor extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        if (null === $this->_options) {
            /**
             * @var Mage_Admin_Model_Resource_User_Collection $collection
             */

            $collection = $this->_getCollection();

            $this->_options = array(
                array(
                    'value' => '',
                    'label' => Mage::helper('multilevel')->__('-- None --')
                )
            );

            /**
             * @var Mage_Admin_Model_User $user
             */
            foreach ($collection as $user) {
                $this->_options[] = array(
                    'value' => $user->getId(),
                    'label' => $user->getName()
                );
            }
        }

        return $this->_options;
    }

    public function toOptionArray()
    {
        $results = array();

        foreach ($this->getAllOptions() as $option) {
            $results[$option['value']] = $option['label'];
        }

        return $results;
    }

    protected function _getCollection()
    {
        $collection = Mage::getResourceModel('admin/user_collection');
        $collection->addFieldToFilter('owner_user_id', array('null' => true))
            ->setOrder('main_table.firstname', $collection::SORT_ORDER_ASC)
            ->setOrder('main_table.lastname', $collection::SORT_ORDER_ASC);

        return $collection;
    }
}
