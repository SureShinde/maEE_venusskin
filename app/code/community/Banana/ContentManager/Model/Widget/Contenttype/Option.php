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

class Banana_ContentManager_Model_Widget_Contenttype_Option
{

    public function toOptionArray()
    {
        $values = array();
        $contentTypes = Mage::getModel('contentmanager/contenttype_option')->getCollection()->addTitleToResult(0);
        
        $values[] = array(
            'value' => 'title',
            'label' => Mage::helper('contentmanager')->__('Title'),
        );
        foreach($contentTypes as $contentType)
        {
            $values[] = array(
                'value' => $contentType->getIdentifier(),
                'label' => $contentType->getTitle(),
            );
        }

        return $values;
    }
}