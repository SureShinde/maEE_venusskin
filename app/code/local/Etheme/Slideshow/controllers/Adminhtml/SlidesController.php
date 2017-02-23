<?php
class Etheme_Slideshow_Adminhtml_SlidesController extends Mage_Adminhtml_Controller_Action {

    
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')
                                ->isAllowed('etheme_slideshow/ios');
    }
    
    public function indexAction() {
        
        $this->_redirect('*/*/list');
    }
    
    public function listAction() {

        $this->_getSession()->setFormData(array());
        $slider = Mage::getModel('etheme_slideshow/slider');
        Mage::register('current_slider', $slider);
        $sliderId = $this->getRequest()->getParam('slider_id');
        
        try {
            if ($sliderId) {
                if (!$slider->load($sliderId)->getId()) {
                    Mage::throwException($this->__('No slider with %s found', $sliderId));
                }
            }
            else {
                $this->_getSession()->addError('Slider id is not specified');
                $this->_redirect('*/sliders/list');
            }
        }
        
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
            $this->_redirect('*/sliders/list');
        }
        
        
        $this->_title($this->__('Sliders'))
            ->_title($this->__('Slides'));
        
        $this->loadLayout();
        $this->_setActiveMenu('etheme_slideshow/ios');
        $this->_addBreadCrumb($this->__('Sliders'), $this->__('Sliders'));
        $this->_addBreadCrumb($this->__('Ios'), $this->__('Ios'));
        
        $this->renderLayout();
    }
    
    public function gridAction() {
    	$slider = Mage::getModel('etheme_slideshow/slider');
        $sliderId = $this->getRequest()->getParam('slider_id');
        Mage::register('current_slider', $slider->setId($sliderId));
        $this->loadLayout()->renderLayout();
    }
    
    public function newAction() {
        $this->_redirect('*/*/edit');
    }
    
    public function editAction() {
        
        $slide = Mage::getModel('etheme_slideshow/slide');
        $id = $this->getRequest()->getParam('id');
        $sliderId = $this->getRequest()->getParam('slider_id');
        if ($sliderId) {
            $slide->setData('slider_id', $sliderId);
        } 
        Mage::register('current_slide', $slide);
        try {
            if ($id) {
                if (!$slide->load($id)->getId()) {
                    Mage::throwException($this->__('No slide with %s found', $id));
                }
            }
            
            if ($slide->getId()) {
                $pageTitle = $this->__('Edit %s', $slide->getName());
            } 
            else {
                $pageTitle = $this->__('New slide');
            }
            $this->_title($this->__('Slide'))
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
            $this->_redirect('*/slides/list');
        }
    }
    
    public function saveAction() {
        
        if ($data = $this->getRequest()->getPost()) {
            
            $this->_getSession()->setFormData($data);
            $slide = Mage::getModel('etheme_slideshow/slide');
            $id = $this->getRequest()->getParam('id');
            $sliderId = $data['slider_id'];
            //try save model
            try {
                
                if ($id)
                    $slide->load($id);
                $slide->addData($data);
                $slide->save();
                $slide = $slide->load($slide->getId()); 
                $slide->saveImages();
                
                $this->_getSession()->addSuccess(
                    $this->__('Slide was successfully added'));
                $this->_getSession()->setFormData(false);
                
                if ($this->getRequest()->getParam('back')) {
                    $params = array('id' => $slide->getId());
                    $this->_redirect('*/*/edit', $params);
                } else {
                    $this->_redirect('*/*/list', array('slider_id' => $sliderId));
                }
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                Mage::logException($e);
                if ($slide && $slide->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $slide->getId()));
                } else {
                    $this->_redirect('*/*/new');
                }
            }
            return;
        }
        
        $this->_getSession()->addError($this->__('No data found to save'));
        $this->_redirect('*/*');
    }
    
    public function deleteAction() {
        
        $slide = Mage::getModel('etheme_slideshow/slide');
        $id = $this->getRequest()->getParam('id');
        try {
            if ($id) {
                if (!$slide->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID %s found', $id));
                }
                $name = $slide->getName();
                $sliderId = $slide->getSliderId();
                $slide->delete();
                $this->_getSession()->addSuccess($this->__('%s was successfully deleted', $name));
                $this->_redirect('*/*/list', array('slider_id' => $sliderId));
            }
        }
        
        catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*');
        }
    }
    
    public function massDeleteAction() {
        
        $slide = Mage::getModel('etheme_slideshow/slide');
        $ids = $this->getRequest()->getParam('ids');
        $currentSliderId = $slide->load($ids[0])->getSliderId();
        
        try {
            foreach ($ids as $id) {
                if (!$slide->load($id)->getId()) {
                    Mage::throwException($this->__('No record with ID %s found', $id));
                }
                $name = $slide->getName();
                $slide->delete();
                $this->_getSession()->addSuccess($this->__('%s was successfully deleted', $name));
            }
            $this->_redirect('*/*/list', array('slider_id' => $currentSliderId));
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
            $slide = Mage::getModel('etheme_slideshow/slide');
            $srcId = $this->getRequest()->getParam('id');

            try {
                $slide->addData($data);
                $slide->duplicate();
                $this->_getSession()->addSuccess($this->__('Slide was successfully duplicated'));
                $this->_getSession()->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $slide->getId()));
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


