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

class Banana_ContentManager_Block_Search_Result extends Mage_Core_Block_Template
{
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if(!$this->getTemplate())
        {
            $this->setTemplate('contenttype/search/results.phtml');
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
        
        //limit
        $limit = ($this->getLimit())?$this->getLimit():20;
        
        //create pager
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
    
    public function getContentsCollection()
    {
        $collection = Mage::getModel('contentmanager/indexer_fulltext')
                                            ->getCollection();

        $query = Mage::helper('catalogsearch')->getQuery();
        //$query->getQueryText();
        //$query->getPopularity()
        //$query->getNumResults()
        
        $collection->addBindParam(':query', $query->getQueryText());
        $field = new Zend_Db_Expr("MATCH (main_table.data_index) AGAINST (:query IN BOOLEAN MODE)");
        $collection->getSelect()
                ->columns(array('relevance' => $field))
                ->where('MATCH (main_table.data_index) AGAINST (:query IN BOOLEAN MODE)') // @see http://dev.mysql.com/doc/refman/5.0/fr/fulltext-search.html 
                ->order('relevance DESC');

        $entities = array();
        foreach($collection as $results){
            $entities[] = $results->getEntityId();
        }
        
        $contents = Mage::getModel('contentmanager/content')
                ->getCollection()
                ->addAttributeToSelect('url_key')
                ->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', array('in' => $entities));

        return $contents;
    }
    
    
}