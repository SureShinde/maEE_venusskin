<?php

class Snowdog_HideProduct_Model_Entity_Attribute_Backend_Customergroups
    extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Process the attribute value before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Eav_Model_Entity_Attribute_Backend_Abstract
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);

        if (!is_array($data)) {
            $data = explode(',', $data);
        }

        // I like it nice and tidy, this gives us sequential index numbers again as a side effect :)
        sort($data);

        $object->setData($attributeCode, implode(',', $data));
        return parent::beforeSave($object);
    }

    /**
     * Explode the saved array again, because otherwise the indexer will think the value changed,
     * even if it is the same (array(1,2,3) !== "1,2,3").
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Eav_Model_Entity_Attribute_Backend_Abstract
     */
    public function afterSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);
        if (is_string($data)) {
            $object->setData($attributeCode, explode(',', $data));
        }
        return parent::afterSave($object);
    }

    /**
     * In case the data was loaded, explode it into an array
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Eav_Model_Entity_Attribute_Backend_Abstract
     */
    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);

        // only explode and set the value if the attribute is set on the model
        if (null !== $data && is_string($data)) {
            $data = explode(',', $data);
            $object->setData($attributeCode, $data);
        }
        return parent::afterLoad($object);
    }
}
