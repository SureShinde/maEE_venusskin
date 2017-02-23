<?php
class Inspiratica_Banner_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('banner/banners')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Banners Manager'), Mage::helper('adminhtml')->__('Banner Manager'));

		return $this;
	}

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');

		$model = Mage::getModel('banner/banner')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);

			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('banner_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('banner/banners');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Banner Manager'), Mage::helper('adminhtml')->__('Banner Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Banner News'), Mage::helper('adminhtml')->__('Banner News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('banner/adminhtml_banner_edit'))
				->_addLeft($this->getLayout()->createBlock('banner/adminhtml_banner_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Banner does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			if (isset($data['image']['delete'])) {
				Mage::helper('banner')->deleteImageFile($data['image']['value']);
			}

			$image = Mage::helper('banner')->uploadBannerImage();

			$id = $this->getRequest()->getParam('id');

			$model = Mage::getModel('banner/banner')->load($id);

			if ($image || (isset($data['image']['delete']) && $data['image']['delete'])) {
				$data['image'] = $image;
			} else {
				unset($data['image']);
			}

			if (is_array($data['blocks'])) {
				$data['blocks'] = implode(',', $data['blocks']);
			}

			if (is_array($data['store_ids'])) {
				$data['store_ids'] = implode(',', $data['store_ids']);
			}

			$model->setData($data);

			$model->setData('id', $this->getRequest()->getParam('id'));

			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('Banner was successfully saved'));
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
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

				return;
			}
		}

		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Unable to find banner to save'));
		$this->_redirect('*/*/');
	}

	public function deleteAction() {
		if ($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('banner/banner');

				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Banner was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$bannerIds = $this->getRequest()->getParam('banner');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select banner(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$banner = Mage::getModel('banner/banner')->load($bannerId);
					$banner->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('adminhtml')->__(
						'Total of %d banner(s) were successfully deleted', count($bannerIds)
					)
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function massSelectBlockAction() {
		$bannerIds = $this->getRequest()->getParam('banner');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select banner(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$banner = Mage::getSingleton('banner/banner')
						->load($bannerId)
						->setBlock($this->getRequest()->getParam('block'))
						->setIsMassupdate(true)
						->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d banner(s) were successfully updated', count($bannerIds))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function massStatusAction() {
		$bannerIds = $this->getRequest()->getParam('banner');
		if (!is_array($bannerIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select banner(s)'));
		} else {
			try {
				foreach ($bannerIds as $bannerId) {
					$banner = Mage::getSingleton('banner/banner')
						->load($bannerId)
						->setStatus($this->getRequest()->getParam('status'))
						->setIsMassupdate(true)
						->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d banner(s) were successfully updated', count($bannerIds))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function exportCsvAction() {
		$fileName = 'banner.csv';
		$content  = $this->getLayout()->createBlock('banner/adminhtml_banner_grid')
			->getCsv();

		$this->_sendUploadResponse($fileName, $content);
	}

	public function exportXmlAction() {
		$fileName = 'banner.xml';
		$content  = $this->getLayout()->createBlock('banner/adminhtml_banner_grid')
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
}
