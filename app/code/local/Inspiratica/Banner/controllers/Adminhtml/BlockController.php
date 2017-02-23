<?php
class Inspiratica_Banner_Adminhtml_BlockController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('banner/blocks')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Blocks Manager'), Mage::helper('adminhtml')->__('Block Manager'));

		return $this;
	}

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id    = $this->getRequest()->getParam('id');
		$model = Mage::getModel('banner/block')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('block_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('banner/blocks');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Block Manager'), Mage::helper('adminhtml')->__('Block Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Block News'), Mage::helper('adminhtml')->__('Block News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('banner/adminhtml_block_edit'))
				->_addLeft($this->getLayout()->createBlock('banner/adminhtml_block_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Block does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$blockId = $this->getRequest()->getParam('id');
			if (is_array($blockId)) $blockId = null;
			$model = Mage::getModel('banner/block');
			$model->setData($data)
				->setId($blockId);

			try {
				$model->save();
				/*
				if (isset($data['block_banner'])) {
					$bannerIds = array();
					parse_str($data['block_banner'], $bannerIds);
					$bannerIds   = array_keys($bannerIds);
					$unSelecteds = Mage::getResourceModel('banner/banner_collection')
						->addFieldToFilter('block', $model->getId());
					if (count($bannerIds))
						$unSelecteds->addFieldToFilter('id', array('nin' => $bannerIds));
					foreach ($unSelecteds as $banner)
						$banner->setBlock(0)->save();
					$selectBanner = Mage::getResourceModel('banner/banner_collection')
						->addFieldToFilter('id', array('in' => $bannerIds))
						->addFieldToFilter('block', array('neq' => $model->getId()));
					foreach ($selectBanner as $banner)
						$banner->setBlock($model->getId())->save();
				}
				*/
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('Block was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $blockId));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Unable to find block to save'));
		$this->_redirect('*/*/');
	}

	public function deleteAction() {
		if ($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('banner/block');

				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Block was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$blockIds = $this->getRequest()->getParam('block');
		if (!is_array($blockIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select block(s)'));
		} else {
			try {
				foreach ($blockIds as $blockId) {
					$block = Mage::getModel('banner/block')->load($blockId);
					$block->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('adminhtml')->__(
						'Total of %d block(s) were successfully deleted', count($blockIds)
					)
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function massStatusAction() {
		$blockIds = $this->getRequest()->getParam('block');
		if (!is_array($blockIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select block(s)'));
		} else {
			try {
				foreach ($blockIds as $blockId) {
					$block = Mage::getSingleton('banner/block')
						->load($blockId)
						->setStatus($this->getRequest()->getParam('status'))
						->setIsMassupdate(true)
						->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d block(s) were successfully updated', count($bannerIds))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function exportCsvAction() {
		$fileName = 'category.csv';
		$content  = $this->getLayout()->createBlock('banner/adminhtml_category_grid')
			->getCsv();

		$this->_sendUploadResponse($fileName, $content);
	}

	public function exportXmlAction() {
		$fileName = 'category.xml';
		$content  = $this->getLayout()->createBlock('banner/adminhtml_category_grid')
			->getXml();

		$this->_sendUploadResponse($fileName, $content);
	}

	protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
		$response = $this->getResponse();
		$response->setHeader('HTTP/1.1 200 OK', '');
		$response->setHeader('Pragma', 'public', true);
		$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
		$response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
		$response->setHeader('Last-Modified', date('r'));
		$response->setHeader('Accept-Ranges', 'bytes');
		$response->setHeader('Content-Length', strlen($content));
		$response->setHeader('Content-type', $contentType);
		$response->setBody($content);
		$response->sendResponse();
		die;
	}

	protected function customAction() {
		$this->loadLayout();
		$this->getLayout()->getBlock('block.edit.tab.custom')
			->setCustom($this->getRequest()->getPost('banner', null));
		$this->renderLayout();
	}

	public function customgridAction() {
		$this->loadLayout();
		$this->getLayout()->getBlock('block.edit.tab.custom')
			->setCustom($this->getRequest()->getPost('banner', null));
		$this->renderLayout();
	}
}
