<?php

/**
 * Class Snowdog_Multilevel_Block_Sales_Recurring_Profile_View
 */
class Snowdog_Multilevel_Block_Sales_Recurring_Profile_View extends Mage_Sales_Block_Recurring_Profile_View
{
    public function prepareScheduleInfo()
    {
        $this->_shouldRenderInfo = true;

        foreach (array('start_datetime', 'updated_at', 'suspension_threshold') as $key) {
            $this->_addInfo(
                array(
                    'label' => $this->_profile->getFieldLabel($key),
                    'value' => $this->_profile->renderData($key),
                )
            );
        }

        foreach ($this->_profile->exportScheduleInfo() as $i) {
            $this->_addInfo(
                array(
                    'label' => $i->getTitle(),
                    'value' => $i->getSchedule(),
                )
            );
        }
    }
}
