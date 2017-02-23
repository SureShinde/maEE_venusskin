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

class Banana_ContentManager_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    const CT_IMAGE_FOLDER = 'contenttype';
    const CT_IMAGE_CROPPED_FOLDER = 'crop';
    
    public function getCtImageFolder()
    {
        return Banana_ContentManager_Helper_Data::CT_IMAGE_FOLDER;
    }
    
    public function getCtImageCroppedFolder()
    {
        return Banana_ContentManager_Helper_Data::CT_IMAGE_CROPPED_FOLDER;
    }
    
    /**
     * Return attribute type corresponding to contenttype type / For EAV storage
     */
    public function getAttributeTypeByFieldType($field_type)
    {
        $fieldTypeToDataType = array(
            'field' => 'varchar',
            'area' => 'text',
            'password' => 'varchar',
            'file' => 'varchar',
            'image' => 'varchar',
            'image_dimensions' => 'text',
            'drop_down' => 'varchar',
            'radio' => 'varchar',
            'checkbox' => 'text',
            'multiple' => 'text',
            'date' => 'datetime',
            'date_time' => 'datetime',
            'product'    => 'text',
            'content'    => 'text',
            'int'    => 'int',
        );
        
        return (isset($fieldTypeToDataType[$field_type]))?$fieldTypeToDataType[$field_type]:'text';
    }
    
    /**
     * Return frontend renderer type corresponding to contenttype type / For render in FORM (when creating new content)
     */
    public function getRendererTypeByFieldType($field_type)
    {
        $fieldTypeToDataType = array(
            'field' => 'text',
            'area' => 'textarea',
            'password' => 'password',
            'file' => 'file',
            'image' => 'image',
            'drop_down' => 'select',
            'radio' => 'radios',
            'checkbox' => 'checkboxes',
            'multiple' => 'multiselect',
            'date' => 'date',
            'date_time' => 'date',
            'int' => 'text',
        );
        
        return (isset($fieldTypeToDataType[$field_type]))?$fieldTypeToDataType[$field_type]:'text';
    }
    
    /**
     * 
     * @param string $identifier
     * @param Banana_ContentManager_Model_Content || integer $content (i.e. can be content_id or content Model)
     * 
     * @return Banana_ContentManager_Model_ContentType_Option
     * 
     * @todo Implement looking for option when content is null 
     */
    
    public function getOptionByFieldIdentifier($identifier, $content = null){
        
        if(is_numeric($content)){
            $content = Mage::getModel('contentmanager/content')->load($content);
        }
        
        //new content
        if($content === null)
        {
            $contentType = Mage::registry('current_contenttype');
        }
        else
        {
            $contentType = $content->getContentType();
        }
        
        foreach($contentType->getOptions() as $option){
            if($option->getIdentifier() == $identifier){
                break;
            }
        }
        
        return $option;
    }
    

    /**
     * Resize Image proportionally and return the resized image url
     *
     * @param string $imageName         name of the image file
     * @param integer|null $width       resize width
     * @param integer|null $height      resize height
     * @param string|null $imagePath    directory path of the image present inside media directory
     * @return string               full url path of the image
     */
    public function resize($imageDirectory, $imageSrc, $imageName, $width=NULL, $height=NULL)
    {
        $resizePath = 'cache' . DS . $width . 'x' . $height;
        $resizeSrc = 'cache' . '/' . $width . 'x' . $height;
        
        $resizeDirectory = $imageDirectory . $resizePath . DS . $imageName;

        try {
            if (file_exists($imageDirectory) && !file_exists($resizeDirectory)) {
                $imageObj = new Varien_Image($imageDirectory.$imageName);
                $imageObj->constrainOnly(TRUE);
                $imageObj->quality(89);
                $imageObj->keepAspectRatio(TRUE);
                $imageObj->keepTransparency(TRUE);
                $imageObj->resize($width,$height);
                $imageObj->save($resizeDirectory);
            }
        }
        catch(Exception $e)
        {
            Mage::logException($e);
        }
        return $imageSrc . $resizeSrc . '/' . rawurlencode($imageName);
    }
    
    /**
     * Replace {{.*}} patterns in data
     * @param string $data
     * @param Content $content
     * @return string
     */
    public function applyPattern($data, $content)
    {
        $matches = array();
        preg_match_all('/{{([a-zA-Z0-9_\|]*)}}/', $data, $matches);

        if(!empty($matches[1]))
        {
            foreach($matches[1] as $key => $replacement)
            {
                $attributeContent = $content->getData($replacement);
                if(preg_match('/\|plain/', $replacement))
                {
                    $replacement = str_replace('|plain', '', $replacement);
                    $attributeContent = $this->_getPlainValue($content->getData($replacement));
                }
                $data = str_replace($matches[0][$key], $attributeContent, $data);
            }
        }
        
        return $data;
    }
    
    /**
     * Get plain value for a content
     */
    private function _getPlainValue($str)
    {
        return strip_tags($str);
    }
    
    /**
     * Return forbidden identifier for field identifier
     * @return array
     */
    public function getForbiddenIdentifier()
    {
        $result = array(
                'content',
                'title',
                'description',
                'keywords',
                'robots',
                'og_title',
                'og_url',
                'og_description',
                'og_image',
                'og_type',
                'use_default_title',
                'use_default_description',
                'use_default_keywords',
                'use_default_robots',
                'use_default_og_title',
                'use_default_og_url',
                'use_default_og_description',
                'use_default_og_image',
                'use_default_og_type',
                'status',
                'store'
            );
        return $result;
    }
    
    public function getContentsByOptionIds($ids = null, $attribute_code = null, $content_type = null, $condition = 'or')
    {
        $collection = Mage::getModel('contentmanager/content')
                ->getCollection($content_type)
                ->addAttributeToSelect('*');
                
        if($ids && $attribute_code && $condition == 'or')
        {
            $arrayFilter = array();
            foreach(explode(',', $ids) as $id)
            {
                $arrayFilter[] = array(
                    'attribute' => $attribute_code,
                    'finset' => $id
                );
            }
            $collection->addAttributeToFilter($attribute_code, $arrayFilter);
        }
        else if(is_array($ids) && $attribute_code && $condition == 'and')
        {
            foreach($ids as $id)
            {
                $collection->addAttributeToFilter($attribute_code, array('finset' => $id));
            }
        }
        
        $collection->addAttributeToFilter('status', 1);
        
        return $collection;
    }
}