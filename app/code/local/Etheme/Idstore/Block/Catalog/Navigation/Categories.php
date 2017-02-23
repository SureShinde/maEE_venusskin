<?php

class Etheme_Idstore_Block_Catalog_Navigation_Categories extends Mage_Core_Block_Template
{

    
    private $currentCategory, $sortField;
   
    
    
    protected function _construct() {
        $this->currentCategory = Mage::registry('current_category');
        $this->sortField = Mage::getStoreConfig('idstore_general/product_list/sidebar_sort_categories', Mage::app()->getStore()->getId());
    }
    
    public function getCurrentCategory() {
        return $this->currentCategory;
    }
    
    function getFirstLevel() {
        return $this->fistLevel;
    }
    
    /**
    * get children of given source category $srcId ordered by $orderBy
    */
    private function getOrderedChildren($srcId, $orderBy='position', $orderDir="asc") {
        $currentCategory = Mage::getModel('catalog/category')->load($srcId);
        $collection = $currentCategory->getCollection();
        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('all_children')
            ->addAttributeToSelect('is_anchor')
            ->addAttributeToFilter('is_active', 1)
            ->addIdFilter($currentCategory->getChildren())
            ->setOrder($orderBy, $orderDir)
            ->load();
        return $collection;
    }
    
    /**
     * print categories tree beginning from initial categories list
     * return string html
     * */
    private function buildTreeHtml($categories, $level) {
        $result = '<ul class="categories-tree">';
        foreach ($categories as $category) {
            $hasChildren = $category->hasChildren();
            $class = 'level' . $level;
            $buttonShow = '';
            if ($hasChildren) {
                $class .= ' parent';
                if ($level == 1)
                    $buttonShow = '<div class="btn-show">&nbsp;</div>';
            }
            if ($category->getId() == $this->getCurrentCategory()->getId())
                $class .= ' active';
            $result .= '<li class="' . $class . '">';
            $result .= '<a href="' . Mage::getModel('catalog/category')->load($category->getId())->getUrl() . '">';
            $result .= $category->getName() . '</a>';
            $result .= $buttonShow;
            if ($hasChildren) {
                //$children = Mage::getModel('catalog/category')->getCategories($category->getId());
                $children = $this->getOrderedChildren($category->getId(), $this->sortField);
                $result .= $this->buildTreeHtml($children, $level+1);
            }
            $result .= '</li>';
        }
        return $result . '</ul>';
        
    }
    public function printCategoriesTree() {

        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId(); 
        $firstLevel = $this->getOrderedChildren($rootCategoryId, $this->sortField);
        return $this->buildTreeHtml($firstLevel, 1);
    }
}
