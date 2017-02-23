<?php
class Snowdog_HideProduct_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Customer group collection instance
     *
     * @var $_groups Mage_Customer_Model_Resource_Group_Collection
     */
    protected $_groups;

    /**
     * Return all groups including the NOT_LOGGED_IN group that is normally hidden.
     *
     * @return Mage_Customer_Model_Resource_Group_Collection
     */
    public function getGroups()
    {
        if (is_null($this->_groups)) {
            $this->_groups = Mage::getResourceModel('customer/group_collection')->load();
        }
        return $this->_groups;
    }

    /**
     * Return a string of comma separated customer group names.
     * Used when rendering of multiselect fields in the admin interface is turned off.
     *
     * @param array $value List of customer group ids
     * @return string
     */
    public function getGroupNamesAsString(array $value)
    {
        $list = array();

        if (count($value)) {
            /** @var Mage_Customer_Model_Resource_Group_Collection $groups */
            $groups = Mage::getResourceModel('customer/group_collection');
            $groups->addFieldToFilter('customer_group_id', array('in' => $value));
            foreach ($groups as $group) {
                $list[] = $group->getCustomerGroupCode();
            }
        }
        return implode(', ', $list);
    }
}