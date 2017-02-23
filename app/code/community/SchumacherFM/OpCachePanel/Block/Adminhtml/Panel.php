<?php

/**
 * @category    SchumacherFM_OpCachePanel
 * @package     Block
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SchumacherFM_OpCachePanel_Block_Adminhtml_Panel extends SchumacherFM_OpCachePanel_Block_Adminhtml_AbstractOpCache
{

    protected function _toHtml()
    {
        if (FALSE === Mage::getSingleton('opcache/cache')->isActive()) {
            return '';
        }
        return parent::_toHtml();
    }

    public function _construct()
    {
        parent::_construct();
        $this->setTitle(Mage::helper('opcache')->__('OpCache Panel'));
    }

    public function getResetButton()
    {
        return $this->getButtonHtml('Reset Cache', 'confirmedCacheAction(\'' . $this->getResetUrl() . '\')');
    }

    public function getResetUrl()
    {
        return $this->getUrl('*/opCachePanel/reset', array('_current' => TRUE));
    }

    public function getRecheckButton()
    {
        return $this->getButtonHtml('Recheck Cache', 'confirmedCacheAction(\'' . $this->getRecheckUrl() . '\')');
    }

    public function getRecheckUrl()
    {
        return $this->getUrl('*/opCachePanel/recheck', array('_current' => TRUE));
    }

    public function getCompileButton()
    {
        if (Mage::getSingleton('opcache/cache')->hasCompiler()) {
            return $this->getButtonHtml('Compile all PHP files', 'confirmedCacheAction(\'' . $this->getCompileUrl() . '\')');
        }
        return '';
    }

    public function getCompileUrl()
    {
        return $this->getUrl('*/opCachePanel/compile', array('_current' => TRUE));
    }

    public function getJsonStatUrl()
    {

        return $this->getUrl('*/opCachePanel/graphDataJson', array('_current' => TRUE));
    }
}
