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

class Banana_ContentManager_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Renders Content Page
     *
     * @param string $coreRoute
     */
    public function viewAction()
    {
        //load content
        $contentId = $this->getRequest()->getParam('content_id', $this->getRequest()->getParam('id', false));
        $content = Mage::getModel('contentmanager/content')->load($contentId);
        Mage::register('current_content', $content);
        
        //load cct
        $cct = Mage::getModel('contentmanager/contenttype')->load($content->getCtId());
        Mage::register('current_ct', $cct);
        
        //load layout & update
        Mage::dispatchEvent('cct_content_render', array('content' => $content, 'cct' => $cct, 'controller_action' => $this));
        
        //Content type dynamic layout handler
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();
        $update->addHandle('CONTENT_TYPE_VIEW_'.$cct->getIdentifier());
        $update->setCacheId('LAYOUT_'.Mage::app()->getStore()->getId().md5(join('__', $update->getHandles())));

        //load layout
        $this->loadLayout();

        $this->loadLayoutUpdates();
        $layoutUpdate = $cct->getLayoutUpdateXml();
        $this->getLayout()->getUpdate()->addUpdate($layoutUpdate);
        $this->generateLayoutXml()->generateLayoutBlocks();
        
        //add body class
        $root = $this->getlayout()->getBlock('root');
        if($root) {
            $root->addBodyClass('contentmanager-contenttype-'.$cct->getIdentifier());
            $root->addBodyClass('contentmanager-content-'.$contentId);
        }

        //apply root template update
        if ($cct->getRootTemplate()) {
            $this->getLayout()->helper('page/layout')
                ->applyTemplate($cct->getRootTemplate());
        }
        
        //apply breadcrumb
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if($breadcrumbs)
        {
            $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl()));
            $storeId = Mage::app()->getStore()->getId();

            if($cct->getBreadcrumbPrevName() && $cct->getBreadcrumbPrevLink())
            {
                $breadcrumbPrevName = unserialize($cct->getBreadcrumbPrevName());
                $breadcrumbPrevLink = unserialize($cct->getBreadcrumbPrevLink());

                $breadcrumbs->addCrumb('cct_content_prev', array('label'=>$breadcrumbPrevName[$storeId], 'title'=>$breadcrumbPrevName[$storeId], 'link' => $breadcrumbPrevLink[$storeId]));
            }
            elseif($cct->getBreadcrumbPrevName() && !$cct->getBreadcrumbPrevLink())
            {
                $breadcrumbPrevName = unserialize($cct->getBreadcrumbPrevName());
                $breadcrumbs->addCrumb('cct_content_prev', array('label'=>$breadcrumbPrevName[$storeId], 'title'=>$breadcrumbPrevName[$storeId]));
            }

            if ($cct->getBreadcrumb())
            {
                $breadcrumbs->addCrumb('cct_content', array('label'=>$content->getData($cct->getBreadcrumb()), 'title'=>$content->getData($cct->getBreadcrumb())));
            }
        }

        /* @TODO: Move catalog and checkout storage types to appropriate modules */
        $messageBlock = $this->getLayout()->getMessagesBlock();
        foreach (array('catalog/session', 'checkout/session', 'customer/session') as $storageType) {
            $storage = Mage::getSingleton($storageType);
            if ($storage) {
                if (version_compare(Mage::getVersion(), '1.6', '>=')){
                    //version is 1.6 or greater   
                    $messageBlock->addStorageType($storageType);
                }
                $messageBlock->addMessages($storage->getMessages(true));
            }
        }
        
        //set page title
        $this->_setTitleMetas();
        
        //render page
        $this->renderLayout();
    }
    
    
    public function _setTitleMetas()
    {
        $content = Mage::registry('current_content');

        $head = $this->getLayout()->getBlock('head');
        
        if($head)
        {
            //add title and meta tags
            $head->setTitle($content->getTitle());
            $head->setKeywords($content->getKeywords());
            $head->setDescription($content->getDescription());
            $head->setRobots($content->getRobots());
        }
        
        //add open graph block
        $block_og = $this->getLayout()->createBlock('contentmanager/og');
        $block_og->setData('og_title', $content->getOgTitle());
        $block_og->setData('og_description', $content->getOgDescription());
        $block_og->setData('og_url', $content->getOgUrl());
        $block_og->setData('og_type', $content->getOgType());
        $block_og->setData('og_image', $content->getOgImage());
        
        $head->append($block_og, 'og_tags');
        
    }
    
}
