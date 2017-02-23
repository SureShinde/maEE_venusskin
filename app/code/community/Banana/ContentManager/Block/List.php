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

class Banana_ContentManager_Block_List extends Mage_Core_Block_Template
{
    private $_filter = array();
    private $_show = array();
    private $_orderIdentifier;
    private $_orderOrder;
    private $_linkLabel;
    private $_linkPosition;
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if(!$this->getTemplate())
        {
            $this->setTemplate('contenttype/list.phtml');
        }
        
        return $this;
    }

    /**
     * Before rendering html, but after trying to load cache
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        //load contents collection
        $collection = $this->getContentsCollection();
        $this->setCollection($collection);
        
        //create pager
        $limit = ($this->getLimit())?$this->getLimit():10;
        
        $pager = $this->getLayout()->createBlock('page/html_pager', 'pager');
        $pager->setAvailableLimit(array($limit=>$limit));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        
        //load
        $this->getCollection()->load();
        
        return $this;
    }
    
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    public function setOrder($identifier, $order)
    {
        $this->_orderIdentifier = $identifier;
        $this->_orderOrder = $order;
    }
    
    public function addLink($label, $position)
    {
        $this->_linkLabel = $label;
        $this->_linkPosition = $position;
    }
    
    public function getLink()
    {
        if(!$this->_linkLabel) return null;
        
        return array(
            'label'     => $this->_linkLabel,
            'position'  => $this->_linkPosition
        );
    }
    
    public function addAttributeToFilter($identifier, $condition, $value)
    {
        if($identifier && $condition && $value)
        {
            $this->_filter[] = array(
                'identifier' => $identifier,
                'condition' => $condition,
                'value' => $value
            );
        }
    }
    
    public function addAttributeToShow($identifier, $params)
    {
        if($identifier)
        {
            $this->_show[] = array(
                'identifier' => $identifier,
                'params' => $params,
            );
        }
    }
    
    public function getAttributeToShow()
    {
        return $this->_show;
    }
    
    public function getContentsCollection()
    {
        $collection = Mage::getModel('contentmanager/content')
                        ->getCollection(strip_tags($this->getCtType()))
                        ->addAttributeToFilter('status', 1)
                        ->addAttributeToSelect('*');
        
        //add filters
        foreach($this->_filter as $filter)
        {
            $collection->addAttributeToFilter($filter['identifier'], array($filter['condition'] => $filter['value']));
        }
        
        //add filters from url
        foreach($this->getRequest()->getParams() as $key => $param)
        {
            if(!in_array($key, array('page_id', 'p')))
            {
                $option = Mage::getModel('contentmanager/contenttype_option')->load($key, 'identifier');

                if($option && in_array($option->getType(), array('drop_down', 'multiple', 'radio', 'checkbox')))
                {
                    $collection->addAttributeToFilter($key, array('like' => '%'.$param.'%'));            
                }
            }
        }
        
        //set order
        $collection->setOrder('created_time', 'DESC');
        if($this->_orderIdentifier)
        {
            if(!$this->_orderOrder)
            {
                $order = 'ASC';
            }
            $collection->setOrder($this->_orderIdentifier, $this->_orderOrder);
        }
        
        return $collection;
    }
    
}