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

class Banana_ContentManager_Block_Adminhtml_Contenttype_Edit_Tab_Options extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('banana/contentmanager/options.phtml');
    }

    /**
     * Get Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getContentType()
    {
        if (!$this->_contentTypeInstance) {
            if ($contentType = Mage::registry('contentmanager')) {
                $this->_contentTypeInstance = $contentType;
            } else {
                $this->_contentTypeInstance = Mage::getSingleton('contentmanager/contenttype');
            }
        }

        return $this->_contentTypeInstance;
    }

    public function setContentType($contentType)
    {
        $this->_contentTypeInstance = $contentType;
        return $this;
    }

    protected function _prepareLayout()
    {
        $path = 'global/contentmanager/options/custom/groups';
        
        foreach (Mage::getConfig()->getNode($path)->children() as $group) {
            $this->setChild($group->getName() . '_option_type',
                $this->getLayout()->createBlock(
                    (string) Mage::getConfig()->getNode($path . '/' . $group->getName() . '/render')
                )
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * Retrieve html templates for different types of custom options
     *
     * @return string
     */
    public function getTemplatesHtml()
    {
        $templates = $this->getChildHtml();

        return $templates;
    }
    
    /**
     * Retrieve existing fieldsets for this CT
     * @return type
     */
    public function getFieldsets()
    {
        $fieldsets = Mage::getModel('contentmanager/fieldset')
            ->getCollection()
            ->addFieldToFilter('ct_id', $this->getRequest()->getParam('ct_id'))
            ->setOrder('sort_order', 'ASC');
        
        return $fieldsets;
    }
    
    public function getRequireSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => 'contenttype_option_{{id}}_is_require',
                'class' => 'select'
            ))
            ->setName('contenttype[options][{{id}}][is_require]')
            ->setValue(0)
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }   
    
    public function getShowInGridSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => 'contenttype_option_{{id}}_show_in_grid',
                'class' => 'select'
            ))
            ->setValue(0)
            ->setName('contenttype[options][{{id}}][show_in_grid]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }   

    public function getTypeSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => 'contenttype_option_{{id}}_type',
                'class' => 'select select-contenttype-option-type required-option-select'
            ))
            ->setName('contenttype[options][{{id}}][type]')
            ->setOptions(Mage::getSingleton('contentmanager/source_options_type')->toOptionArray());

        return $select->getHtml();
    }
    
    public function getOptionValues()
    {
        $optionsArr = $this->getContentType()->getOptions();

        if (!$this->_values) {
            $values = array();
            foreach ($optionsArr as $option) {
                /* @var $option Mage_Catalog_Model_Product_Option */

                $this->setItemCount($option->getOptionId());

                $value = array();

                $value['id'] = $option->getOptionId();
                $value['option_id'] = $option->getOptionId();
                $value['item_count'] = $this->getItemCount();
                $value['title'] = $this->htmlEscape($option->getTitle());
                $value['type'] = $option->getType();
                $value['is_require'] = $option->getIsRequire();
                $value['show_in_grid'] = $option->getShowInGrid();
                $value['sort_order'] = $option->getSortOrder();
                $value['identifier'] = $option->getIdentifier();
                $value['fieldset_id'] = $option->getFieldsetId();
                $value['note'] = $option->getNote();
                $value['default_value'] = $option->getDefaultValue();
                $value['max_characters'] = $option->getMaxCharacters();
                $value['wysiwyg_editor'] = $option->getWysiwygEditor();
                $value['crop'] = $option->getCrop();
                $value['crop_w'] = $option->getCropW();
                $value['crop_h'] = $option->getCropH();
                $value['keep_aspect_ratio'] = $option->getKeepAspectRatio();
                $value['file_path'] = $option->getFilePath();
                $value['file_extension'] = $option->getFileExtension();
                $value['content_type'] = $option->getData('content_type');
                $value['attribute'] = $option->getData('attribute');
                
                //fields search attributes
                $attributeId = $option->getAttributeId();
                if($attributeId)
                {
                    $attribute = Mage::getModel('contentmanager/attribute')->load($attributeId);
                    $value['is_searchable'] = $attribute->getIsSearchable();
                    $value['search_attribute_weight'] = $attribute->getSearchAttributeWeight();
                }


                if ($option->getGroupByType() == Banana_ContentManager_Model_ContentType_Option::OPTION_GROUP_SELECT) {

                    $i = 0;
                    $itemCount = 0;
                    foreach ($option->getValues() as $_value) {
                        /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        $value['optionValues'][$i] = array(
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $_value->getOptionTypeId(),
                            'title' => $this->htmlEscape($_value->getTitle()),
                            'sku' => $this->htmlEscape($_value->getSku()),
                            'sort_order' => $_value->getSortOrder(),
                            'value' => $_value->getValue(),
                            'default' => $_value->getDefault(),
                        );

                        $i++;
                    }
                }
                    
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }

        return $this->_values;
    }    

}
