<?php 
class Etheme_Idstore_Block_Catalog_Navigation_Mobile extends Mage_Catalog_Block_Navigation {
    
    protected function _renderCategoryMenuOptionHtml($category, $level = '', $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);

        // prepare list item html classes
        $active = '';
        if ($this->isCategoryActive($category)) {
            $active = 'selected';
        }


        // assemble list item with attributes

        $html[] = '<option value="'.$this->getCategoryUrl($category).'" '.$active.'>';
        $html[] = $level.' '.$this->escapeHtml($category->getName());
        $html[] = '</option>';

        // render children
        $htmlChildren = '';
        $j = 0;
        $columns=0;
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryMenuOptionHtml(
                $child,
                ($level.'-'),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
            $columns++;
        }
        if (!empty($htmlChildren)) {
            $html[] = $htmlChildren;
        }
        $active = '';

        $html = implode("\n", $html);
        return $html;
    }    
    
    public function renderMenu($level = 0, $outermostItemClass = '', $childrenWrapClass = '')
    {
        $activeCategories = array();
        foreach ($this->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $html = '';
        $j = 0;
        foreach ($activeCategories as $category) {
            $html .= $this->_renderCategoryMenuOptionHtml(
                $category,
                $level,
                ($j == $activeCategoriesCount - 1),
                ($j == 0),
                true,
                $outermostItemClass,
                $childrenWrapClass,
                true
            );
            $j++;
        }

        return $html;
    }
    
    public function _toHtml() {
        return $this->renderMenu();
        }
}