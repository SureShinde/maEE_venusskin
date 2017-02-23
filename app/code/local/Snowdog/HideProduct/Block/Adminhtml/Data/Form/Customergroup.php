<?php

class Snowdog_HideProduct_Block_Adminhtml_Data_Form_Customergroup
    extends Varien_Data_Form_Element_Multiselect
{
    /**
     * Set the default value to USE_DEFAULT. This is needed if the extension is is installed
     * after products already where created.
     *
     * @return int
     */
    public function getValue()
    {
        $value = $this->getData('value');
        if (!is_null($value) && !is_array($value)) {
            $value = explode(',', (string)$value);
        }
        if (empty($value)) {
            $value = array(2);
        }

        return $value;
    }

    /**
     * Depending on the "show_multiselect_field" config value, either
     * return the multiselect element output or use a label
     * element instead.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = parent::getElementHtml();
        return $html;
    }

    /**
     * Return the groups as a string of comma separated names.
     * This is used if the element is displayed as a label element
     * instead of a multiselect element.
     *
     * @return string
     */
    public function getValueAsString()
    {
        $value = $this->getValue();
        $helper = Mage::helper('hideproduct');
        return $helper->getGroupNamesAsString($value);
    }
}
