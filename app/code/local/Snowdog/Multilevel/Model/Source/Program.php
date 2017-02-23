<?php

/**
 * Class Snowdog_Multilevel_Model_Source_Program
 */
class Snowdog_Multilevel_Model_Source_Program
{
    protected $_options;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => '', 'label' => Mage::helper('multilevel')->__('-- Please Select --'))
            );
            $programCollection = Mage::getResourceModel('affiliateplusprogram/program_collection');
            foreach ($programCollection as $program) {
                $id = $program->getId();
                if ($id != 0) {
                    $this->_options[] = array('value' => $id, 'label' => $program->getName());
                }
            }
        }

        return $this->_options;
    }
}
