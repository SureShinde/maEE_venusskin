<?php

/**
 * Class Snowdog_Multilevel_Model_Mysql4_Tier
 */
class Snowdog_Multilevel_Model_Mysql4_Tier extends Magestore_Affiliatepluslevel_Model_Mysql4_Tier
{
    public function getTopTierId($tierId)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), 'toptier_id')->where('tier_id = ?', $tierId);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    public function updateToptierId($tierId, $oldToptierId, $newToptierId)
    {
        $this->_getWriteAdapter()->update(
            $this->getMainTable(),
            array('toptier_id' => $newToptierId),
            $this->getReadConnection()->quoteInto('tier_id = ?', $tierId) . ' AND ' . $this->getReadConnection()->quoteInto('toptier_id = ?', $oldToptierId)
        );

        return $this;
    }
}
