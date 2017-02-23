<?php

abstract class Etheme_Slideshow_Model_Abstract extends Mage_Core_Model_Abstract {
    
    /**
     * Serialize data 
     * selects fields that have to be serialized, returns as serialized string
     * @param $data array result of POST request that contains all form data
     * @param $fields array of fields to be serialized
     * @param $container put whole thing in container.
     * @return $json string serialized fields
     */
    protected function serializeData($data, $fields, $container=null) 
    {
        $retrievedData = array();
        for ($i = 0; $i < count($fields); $i++) {
            if (isset($data[$fields[$i]]))
                $retrievedData[$fields[$i]] = $data[$fields[$i]];
        }
        //because I can
        if ($container) 
            $retrievedData = array($container => $retrievedData);
        return Mage::helper('core')->jsonEncode($retrievedData);
    }
    
    /**
     * Deserialize data taken from database 
     * to array of values
     * @param $raw string of serialized data
     * @return array of key-value form data
     */
    protected function deserializeData($raw) {
        $data = Mage::helper('core')->jsonDecode($raw);
        return $data;
    }

}