<?php
/**
 * Banana ContentManager Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@advancedcontentmanager.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.advancedcontentmanager.com)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.2.4
 */

class Banana_ContentManager_Block_Filter extends Mage_Core_Block_Template
{
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if(!$this->getTemplate())
        {
            $this->setTemplate('contenttype/filter.phtml');
        }
        
        return $this;
    }
    
    public function getAttributeValues()
    {
        $option = Mage::getModel('contentmanager/contenttype_option')->load($this->getAttributeToFilter(), 'identifier');
        
        if($option && in_array($option->getType(), array('drop_down', 'multiple', 'radio', 'checkbox')))
        {
            $collectionOptionsValues = Mage::getModel('contentmanager/contenttype_option_value')
                    ->getCollection()
                    ->addTitleToResult(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('option_id', $option->getId());

            $collectionOptionsValues->getSelect()->order('sort_order');
            return $collectionOptionsValues;
        }
        
        return null;
    }

    public function getFilteredUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }
    
}