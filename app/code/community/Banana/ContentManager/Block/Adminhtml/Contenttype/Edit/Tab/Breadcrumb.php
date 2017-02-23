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

class Banana_ContentManager_Block_Adminhtml_Contenttype_Edit_Tab_Breadcrumb extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('contenttype_form', array('legend'=>Mage::helper('contentmanager')->__('Breadcrumbs')));
       
        $fieldset->addField('breadcrumb', 'select', array(
            'name'      => 'breadcrumb',
            'label'     => Mage::helper('contentmanager')->__('Last crumb'),
            'title'     => Mage::helper('contentmanager')->__('Last crumb'),
            'note'      => Mage::helper('contentmanager')->__('Select the field to use as breadcrumb. You can create a new field dedicated to the breadcrumb name. Save your content in order to see your new fields in this list.'),
            'required'  => false,
            'options'   => Banana_ContentManager_Model_Source_Options_Type::toOptionByContentTypeArray(),
        ));
        
        $stores = Mage::app()->getStores();
        foreach($stores as $store)
        {
            $fieldsetStore[$store->getId()] = $form->addFieldset('contenttype_form'.$store->getId(), array('legend'=>Mage::helper('contentmanager')->__('Middle breadcrumb - '.$store->getName().' ('.$store->getCode().')')));
            $fieldsetStore[$store->getId()]->addField('breadcrumb_prev_name_'.$store->getId(), 'text', array(
                'name'      => 'breadcrumb_prev_name['.$store->getId().']',
                'label'     => Mage::helper('contentmanager')->__('N-1 bread crumb name'),
                'title'     => Mage::helper('contentmanager')->__('N-1 bread crumb name'),
                'note'      => Mage::helper('contentmanager')->__('You can add a middle crumb, keep empty to skip this feature.'),
                'required'  => false,
            ));

            $fieldsetStore[$store->getId()]->addField('breadcrumb_prev_link_'.$store->getId(), 'text', array(
                'name'      => 'breadcrumb_prev_link['.$store->getId().']',
                'label'     => Mage::helper('contentmanager')->__('N-1 bread crumb link'),
                'title'     => Mage::helper('contentmanager')->__('N-1 bread crumb link'),
                'note'      => Mage::helper('contentmanager')->__('Type your middle crumb link, keep empty for no link on it.'),
                'required'  => false,
            ));            
        }

        if ( Mage::getSingleton('adminhtml/session')->getContentTypeData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getContentTypeData());
            Mage::getSingleton('adminhtml/session')->setContentTypeData(null);
        } elseif ( Mage::registry('contenttype_data') ) {
            $data = Mage::registry('contenttype_data')->getData();
            
            if(isset($data['breadcrumb_prev_name']))
                $data['breadcrumb_prev_name'] = unserialize($data['breadcrumb_prev_name']);
            
            if(isset($data['breadcrumb_prev_link']))
                $data['breadcrumb_prev_link'] = unserialize($data['breadcrumb_prev_link']);
            
            $stores = Mage::app()->getStores();
            foreach($stores as $store)
            {
                if(isset($data['breadcrumb_prev_name']) && isset($data['breadcrumb_prev_name'][$store->getId()]))
                    $data['breadcrumb_prev_name_'.$store->getId()] = $data['breadcrumb_prev_name'][$store->getId()];
                
                if(isset($data['breadcrumb_prev_link']) && isset($data['breadcrumb_prev_link'][$store->getId()]))
                    $data['breadcrumb_prev_link_'.$store->getId()] = $data['breadcrumb_prev_link'][$store->getId()];
            }
            
            $form->setValues($data);
        }
        return parent::_prepareForm();
    }
}