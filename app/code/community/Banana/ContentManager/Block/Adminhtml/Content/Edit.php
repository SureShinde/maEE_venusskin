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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Banana
 * @package		Banana_ContentManager
 * @copyright	Copyright (c) 2014 Banana Content Manager (http://www.banana.fr)
 * @author		Banana Team
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version		1.2.4
 */

class Banana_ContentManager_Block_Adminhtml_Content_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'contentmanager';
        $this->_controller = 'adminhtml_content';
        
        parent::__construct();
 
        $this->_updateButton('save', 'label', Mage::helper('contentmanager')->__('Save'));
        $this->_removeButton('delete');
        
        //BTN Save and continue
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
        
        //BTN delete
        $objId = $this->getRequest()->getParam($this->_objectId);

        if (! empty($objId)) {
            $this->_addButton('delete_all', array(
                'label'     => Mage::helper('adminhtml')->__('Delete All'),
                'class'     => 'delete',
                'onclick'   => 'deleteConfirm(\''. Mage::helper('adminhtml')->__('Are you sure you want to do delete this content and all his translations?')
                    .'\', \'' . $this->getDeleteUrl() . '\')',
            ));
            
            $store_id = Mage::app()->getRequest()->getParam('store', 0);
            if($store_id)
            {
                $flag = Mage::getModel('contentmanager/flag')->load($store_id);
                $flag_img = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'contenttype/flags/'.$flag->getValue().'';
            }
            else
            {
                $flag_img = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'contenttype/flags/world.png';               
                $store_id = 0;
            }
            $flag = '<div style="height: 19px; width: 20px; position: absolute; right: 0px; top: 0px; background: url('.$flag_img.') no-repeat center center;"></div>';
            
            $this->_addButton('delete_store', array(
                'style'     => 'position: relative; padding-right: 25px;',
                'label'     => Mage::helper('adminhtml')->__('Delete Translation').$flag,
                'title'     => Mage::helper('adminhtml')->__('Delete Translation'),
                'class'     => 'delete',
                'onclick'   => 'deleteConfirm(\''. Mage::helper('adminhtml')->__('Are you sure you want to do delete this translation?')
                    .'\', \'' . $this->getDeleteStoreUrl($store_id) . '\')',
            ));       
        }
    }
 
    public function getHeaderText()
    {
        $store_id = Mage::app()->getRequest()->getParam('store', 0);
        if($store_id)
        {
            $flag = Mage::getModel('contentmanager/flag')->load($store_id);
            $flag_img = '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'contenttype/flags/'.$flag->getValue().'" /> ';
        }
        else
        {
            $flag_img = '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'contenttype/flags/world.png" /> ';
        }
        
        if( Mage::registry('content_data') && Mage::registry('content_data')->getId() ) {
            return $flag_img.Mage::helper('contentmanager')->__("Edit content '%s'", $this->htmlEscape(Mage::registry('content_data')->getContentType()->getTitle()));
        } else {
            return $flag_img.Mage::helper('contentmanager')->__('Add Content');
        }
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('*/*/', array('ct_id' => $this->getRequest()->getParam('ct_id')));
    }
    
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array('ct_id' => $this->getRequest()->getParam('ct_id'), $this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }
    
    public function getDeleteStoreUrl($store_id)
    {
        return $this->getUrl('*/*/deletestore', array('store_id' => $store_id, 'ct_id' => $this->getRequest()->getParam('ct_id'), $this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }

}