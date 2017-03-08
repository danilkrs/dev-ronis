<?php
class Ronisbt_Banners_Adminhtml_BannersController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('ronisbtbanners/adminhtml_ronisbtbanners'));
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {   
        $id = $this->getRequest()->getParam('banner_id');
        $bannerObject = Mage::getModel('ronisbtbanners/banners')->load($id);
        Mage::register('ronisbtbanners_banner', $bannerObject);
        if(count($bannerObject)) {
            Mage::registry('ronisbtbanners_banner')->setData($bannerObject);
        }
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('ronisbtbanners/adminhtml_ronisbtbanners_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            $id = $this->getRequest()->getParam('banner_id');
            $banner = Mage::getModel('ronisbtbanners/banners')->load($id);
            $newPosition = $this->getRequest()->getParam('position');
            $oldPosition = $banner->getPosition();
            if(!$oldPosition){
                $list = Mage::helper('ronisbtbanners/data')->PositionList();
                $oldPosition = count($list);
            }
            $checkPosition = Mage::getModel('ronisbtbanners/banners')
                ->getCollection()->addFieldToFilter('position', $newPosition);
            if($checkPosition){
                foreach ($checkPosition as $oldBanner) {
                    $oldBanner->setPosition($oldPosition);
                    $oldBanner->save();
                    
                }
            }
            $banner
                ->setData($this->getRequest()->getParams());
            $this->_uploadFile('image', $banner);
            $banner
                ->setCreated(Mage::app()->getLocale()->date())
                ->save();
            if(!$banner->getBannerId()) {
                Mage::getSingleton('adminhtml/session')->addError('Cannot save the block');
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setBlockObject($block->getData());
            return  $this->_redirect('*/*/edit',array('banner_id'=>$this->getRequest()->getParam('banner_id')));
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Banner was saved successfully!');
        $this->_redirect('*/*/'.$this->getRequest()->getParam('back','index'),array('banner_id'=>$banner->getBannerId()));
    }

    protected function _uploadFile($fieldName, $model)
    {

        if( !isset($_FILES[$fieldName])) {
            return false;
        }
        $file = $_FILES[$fieldName];
        if(isset($file['name']) && (file_exists($file['tmp_name']))){
            if($model->getBannerId() && !empty($model->getData($fieldName))){
                unlink(Mage::getBaseDir('media') . DS . $model->getData($fieldName));
            }
            try
            {
                $path = Mage::getSingleton('ronisbtbanners/banners_media_config')->getBaseMediaPath();
                //die(var_dump($path));
                //$path = Mage::getBaseDir('media') . DS . 'ronisbtbanners' . DS;
                $uploader = new Varien_File_Uploader($file);
                $uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $file['name']);
                $model->setData($fieldName, $uploader->getUploadedFileName());
                return true;
            }
            catch(Exception $e)
            {
                return false;
            }
        }
    }


    public function deleteAction()
    {
        $banner = Mage::getModel('ronisbtbanners/banners')
            ->setId($this->getRequest()->getParam('banner_id'))
            ->delete();
        if($banner->getBannerId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess('Block was deleted successfully!');
        }
        $this->_redirect('*/*/');

    }


    public function massStatusAction()
    {
        $statuses = $this->getRequest()->getParams();
        $statuses['banner_status'] = (int) $statuses['banner_status'];
        try {
            $banners= Mage::getModel('ronisbtbanners/banners')
                ->getCollection()
                ->addFieldToFilter('banner_id',array('in'=>$statuses['massaction']));
            foreach($banners as $banner) {
                $banner->setBannerStatus($statuses['banner_status'])->save();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Blocks were updated!');

        return $this->_redirect('*/*/');

    }

    public function massDeleteAction()
    {
        $banner = $this->getRequest()->getParams();
        try {
            $banners= Mage::getModel('ronisbtbanners/banners')
                ->getCollection()
                ->addFieldToFilter('banner_id',array('in'=>$banner['massaction']));
            foreach($banners as $banner) {
                $banner->delete();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Banners were deleted!');
        return $this->_redirect('*/*/');

    }
}
