<?php

class Etheme_Idstore_Block_Product_Collection extends Mage_Catalog_Block_Product_List {
    
    const DEFAULT_PRODUCTS_COUNT = 50;
    
    private $params, $html;
    
    public function prepareCollection() {

        if ($category = $this->getCategory()) {

            $collection = Mage::getModel('catalog/category')->load($category)
            ->getProductCollection()
            ->addAttributeToSelect('*');
        }
        else
            $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection)->addStoreFilter();
        
        if ($this->getNew() == 1) {
            $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            $collection->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              );
        }
        
        if ($this->getSale() == 1) {
            $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            $collection->addAttributeToFilter('special_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('special_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'special_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'special_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToFilter('special_price', array('is' => new Zend_Db_Expr('not null'))
            );
        }
        
        if ($this->getRandom() == 1)
            $collection->getSelect()->order('rand()');
        
        else {
            $filter = $this->getSort(); 
            
            switch ($filter) {
                case 'id':
                    $orderBy = 'entity_id';
                    break;
                case 'name':
                    $orderBy = 'name';
                    break;
                case 'new':
                    $orderBy = 'news_from_date';
                    break;
                case 'price': 
                    $orderBy = 'price';
                    break;
                default:
                    $orderBy = 'entity_id';
            }
            
            $orderDirection = $this->getSortDirection();
            if ($orderDirection != 'desc') 
                $orderDirection = 'asc';
            
            $collection->addAttributeToSort($orderBy, $orderDirection);
        }
        
        //$collection->addAttributeToSort('product_name', 'asc');
        
        if (!$count = $this->getCount())
            $count = self::DEFAULT_PRODUCTS_COUNT;

        $collection->setPageSize($count)->setCurPage(1);
        $this->setProductCollection($collection);

    }
    
    public function getHtml() {
        
        return $this->html; 
    }
    
    public function getParams() {
        
        return $this->getData();
    }
}