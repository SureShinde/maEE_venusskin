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

class Banana_ContentManager_Model_Menu extends Mage_Core_Model_Abstract
{
    private $_treeNodes = array();
    
    public function _construct(){
        parent::_construct();
        $this->_init('contentmanager/menu');
    }
    
    public function getTreeNodes($status = false){
        
	    if(empty($this->_treeNodes)){
	        $this->setTreeNodes($status);
	    }
	    return $this->_treeNodes;
	}
	
	public function getNodes($status){
	    $nodes = Mage::getModel('contentmanager/menu_node')
                	    ->getCollection()
                	    ->addFieldToFilter('menu_id', array('eq' => $this->getId()));
            
            $nodes->getSelect()->order('position');
            if($status === true)
            {
                $nodes->addFieldToFilter('status', 1);
            }
            
            return $nodes;
	}
	
	protected function setTreeNodes($status){
	    $this->_treeNodes = $this->getTree(null, $this->getNodes($status));
	}
	
	/**
	 * Format tree menu in array
	 * @param int $level // Level to begin (Used for recursivity)
	 * @param ContentType_Menu_Node_Collection $nodes
	 */
	protected function getTree($parent_id = null, $nodes){
	    
	    $result = array();
            foreach ($nodes as $node) {
                if ($parent_id == $node['parent_id']) {

                    $data = $node->getData();
                    $data['children'] = $this->getTree($node['node_id'], $nodes);

                    $result[] = $data;
                }
                
            }
            return $result;
	}
        
        /**
         * Render menu item
         */
        public function render($node)
        {
            $block = Mage::app()->getLayout()->createBlock(
                        'contentmanager/menu_item',
                        NULL,
                        array(
                            'type' => $node['type'],
                            'node' => $node,
                            'menu' => $this
                        )
                    ); 
            
            return $block->toHtml();
        }
        
        public function getUrl($node)
        {
            $url = '';
            switch($node['type'])
            {
                case 'category':
                    $entity_id = $node['entity_id'];
                    $category = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('url_key')->addAttributeToFilter('entity_id', array('in' => explode(', ', $entity_id)));
                    if($category->getSize() > 0)
                    {
                        $url = $category->getLastItem()->getUrl();
                    }
                    break;
                case 'content':
                    $entity_id = $node['entity_id'];
                    $content = Mage::getModel('contentmanager/content')->getCollection()->addAttributeToSelect('url_key')->addAttributeToFilter('entity_id', $entity_id);
                    if($content->getSize() > 0)
                    {
                        $url = $content->getFirstItem()->getUrl();
                    }
                    break;
                case 'page':
                    $entity_id = $node['entity_id'];
                    $page = Mage::getModel('cms/page')->getCollection()->addFieldToSelect('identifier')->addFieldToFilter('identifier', $entity_id);
                    if($page->getSize() > 0)
                    {
                        $url = Mage::getUrl($page->getFirstItem()->getIdentifier());
                    }                    
                    break;
                case 'product':
                    $sku = $node['entity_id'];
                    $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('url_key')->addAttributeToFilter('sku', $sku);
                    if($product->getSize() > 0)
                    {
                        $url = $product->getFirstItem()->getProductUrl();
                    }                    
                    break;
                case 'custom':
                    $format = unserialize($node['format']);
                    $url = $format['url'];                    
                    break;
                case 'node':
                    $entity_id = $node['entity_id'];
                    $format = unserialize($node['format']);

                    $url = '';

                    if($format['firstchild'] == 1 && count($node['children']) > 0)
                    {
                        $url = $this->getUrl('', array('_direct' => $node['children'][0]));
                    }
                    break;
            }
            
            return $url;
        }
        
        public function getLabel($node)
        {
            $label = $node['label'];
            if(!$label)
            {
                switch($node['type'])
                {
                    case 'category':
                        $entity_id = $node['entity_id'];
                        $category = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('name')->addAttributeToFilter('entity_id', array('in' => explode(', ', $entity_id)));
                        if($category->getSize() > 0)
                        {
                            $label = $category->getLastItem()->getName();
                        }
                        break;
                    case 'content':
                        $entity_id = $node['entity_id'];
                        $content = Mage::getModel('contentmanager/content')->getCollection()->addAttributeToSelect('title')->addAttributeToFilter('entity_id', $entity_id);
                        if($content->getSize() > 0)
                        {
                            $label = $content->getFirstItem()->getTitle();
                        }                    
                        break;
                    case 'page':
                        $entity_id = $node['entity_id'];
                        $page = Mage::getModel('cms/page')->getCollection()->addFieldToSelect('content_heading')->addFieldToFilter('identifier', $entity_id);
                        if($page->getSize() > 0)
                        {
                            if($page->getFirstItem()->getContentHeading())
                            {
                                $label = $page->getFirstItem()->getContentHeading();
                            }
                        }                    
                        break;
                    case 'product':
                        $sku = $node['entity_id'];
                        $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('name')->addAttributeToFilter('sku', $sku);
                        if($product->getSize() > 0)
                        {
                            $label = $product->getFirstItem()->getName();
                        }
                        break;
                }
            }
            
            return $label;
        }        
}