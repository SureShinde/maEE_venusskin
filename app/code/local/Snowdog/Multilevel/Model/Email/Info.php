<?php

/**
 * Class Snowdog_Multilevel_Model_Email_Info
 */
class Snowdog_Multilevel_Model_Email_Info extends Mage_Core_Model_Email_Info
{
    /**
     * @var array
     */
    protected $_attachments = array();

    /**
     * @param $filename
     * @param $content
     * @return $this
     */
    public function addAttachment($filename, $content)
    {
        $this->_attachments[] = array(
            'filename' => $filename,
            'content' => is_string($content) && is_readable($content) ? file_get_contents($content) : $content
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }
}
