<?php
class Etheme_Slideshow_Adminhtml_SlidersController extends Mage_Adminhtml_Controller_Action {

    
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')
                                ->isAllowed('etheme_slideshow/ios');
    }
    
    public function indexAction() {

        $this->_redirect('*/*/list');
    }
    
    public function listAction() {

        $this->_getSession()->setFormData(array());
        $this->_title($this->__('Sliders'));
        $this->loadLayout();
        $this->_setActiveMenu('etheme_slideshow/ios');
        $this->_addBreadCrumb($this->__('Sliders'), $this->__('Sliders'));
        $this->_addBreadCrumb($this->__('Ios'), $this->__('Ios'));
        $this->renderLayout();
    }
    
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    
    public function newAction() {
        
        $this->_redirect('*/*/edit');
    }
    
    public function editAction() {
        
        $slider = Mage::getModel('etheme_slideshow/slider');
        Mage::register('current_slider', $slider);
        $id = $this->getRequest()->getParam('id');
        
        try {
            if ($id) {
                if (!$slider->load($id)->getId()) {
                    Mage::throwException($this->__('No record with %s found', $id));
                }
            }
            
            if ($slider->getId()) {
                $pageTitle = $this->__('Edit %s', $slider->getName());
            } 
            else {
                $pageTitle = $this->__('New slider');
            }
            $this->_title($this->__('Slider'))
                ->_title($this->__('Ios'))
                ->_title($pageTitle);
            $this->loadLayout();
            if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
            $this->_setActiveMenu('etheme_slideshow/ios');
            $this->_addBreadCrumb($this->__('Sliders'), $this->__('Sliders'));
            $this->_addBreadCrumb($this->__('Ios'), $this->__('Ios'));
            $this->renderLayout();
        }
        
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
            $this->_redirect('*/*');
        }
    }
    
    public function saveAction() {
        
        if ($data = $this->getRequest()->getPost()) {
            $this->_getSession()->setFormData($data);
            $slider = Mage::getModel('etheme_slideshow/slider');
            $id = $this->getRequest()->getParam('id');
            
            try {
                if ($id) {
                    $slider->load($id);
                }
                $slider->addData($data);
                $slider->save();
                $this->_getSession()->addSuccess($this->__('Slider was successfully added'));
                $this->_getSession()->setFormData(false);
                
                if ($this->getRequest()->getParam('back')) {
                    $params = array('id' => $slider->getId());
                    $this->_redirect('*/*/edit', $params);
                } else {
                    $this->_redirect('*/*/list');
                }
            }
            
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                Mage::logException($e);
                if ($slider && $slider->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $slider->getId()));
                } else {
                    $this->_redirect('*/*/new');
                }
            }
            return;
        }
        
        $this->_getSession->addError($this->__('No data found to save'));
        $this->_redirect('*/*');
    }
    
    public function deleteAction() {
        //
        $slider = Mage::getModel('etheme_slideshow/slider');
        $id = $this->getRequest()->getParam('id');
        try {
            if ($id) {
                if (!$slider->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID %s found', $id));
                }
                $name = $slider->getName();
                $slider->delete();
                $this->_getSession()->addSuccess($this->__('%s was successfully deleted', $name));
                $this->_redirect('*/*');
            }
        }
        
        catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*');
        }
    }
    
    public function massDeleteAction() {
        
        $slider = Mage::getModel('etheme_slideshow/slider');
        $ids = $this->getRequest()->getParam('ids');
        
        try {
            foreach ($ids as $id) {
                
                if (!$slider->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID %s found', $id));
                }
                $name = $slider->getName();
                $slider->delete();
                $this->_getSession()->addSuccess($this->__('%s was successfully deleted', $name));
                $this->_redirect('*/*');
            }
        }
        
        catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*');
        }
    }
    
    public function duplicateAction() {
        
        if ($data = $this->getRequest()->getPost()) {
            $this->_getSession()->setFormData($data);
            $slider = Mage::getModel('etheme_slideshow/slider');
            
            try {
                $slider->addData($data);
                $slider->duplicate();
                $this->_getSession()->addSuccess($this->__('Slider was successfully duplicated'));
                $this->_getSession()->setFormData(false);
                $params = array('id' => $slider->getId());
                $this->_redirect('*/*/edit', $params);
            }
            
            catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*');
            }
            return;
        }
        $this->_getSession()->addError($this->__('No data found to save'));
        $this->_redirect('*/*');
    }

}