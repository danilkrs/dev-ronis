<?php
class Ronisbt_Feedback_Adminhtml_FeedbackController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {   
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('ronisbtfeedback/adminhtml_ronisbtfeedback'));
        $this->renderLayout();
    }
    public function editAction()
    {   
        $id = $this->getRequest()->getParam('id');
        $feedback = Mage::getModel('ronisbtfeedback/feedback')->load($id);
        $feedback->setStatus(1);
        $feedback->save();
        Mage::register('ronisbtfeedback_feedback', $feedback);
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('ronisbtfeedback/adminhtml_ronisbtfeedback_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $feedbackMessage = Mage::getModel('ronisbtfeedback/feedback')->load($id);
            $feedbackMessage->setData($this->getRequest()->getParams());
            $feedbackMessage->save();
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return  $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Message was saved successfully!');
        $this->_redirect('*/*/'.$this->getRequest()->getParam('back','index'), array('id'=>$feedbackMessage->getId()));
    }

    public function deleteAction()
    {
        $feedbackMessage = Mage::getModel('ronisbtfeedback/feedback')
            ->setId($this->getRequest()->getParam('id'))
            ->delete();
        if($feedbackMessage->getId()) {
            Mage::getSingleton('adminhtml/session')->addSuccess('Message was deleted successfully!');
        }
        $this->_redirect('*/*/');
    }

    public function massStatusAction()
    {
        $statuses = $this->getRequest()->getParams();
        $statuses['status'] = (int) $statuses['status'];
        try {
            $feedbackMessages= Mage::getModel('ronisbtfeedback/feedback')
                ->getCollection()
                ->addFieldToFilter('id',array('in'=>$statuses['massaction']));
            foreach($feedbackMessages as $feedbackMessage) {
                $feedbackMessage->setStatus($statuses['status'])->save();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Messages status were updated!');

        return $this->_redirect('*/*/');
    }
    public function massDeleteAction()
    {   
        $feedbackMessagesId = $this->getRequest()->getParams();
        try {
            $feedbackMessages= Mage::getModel('ronisbtfeedback/feedback')
                ->getCollection()
                ->addFieldToFilter('id', array('in'=>$feedbackMessagesId['massaction']));
            foreach($feedbackMessages as $feedbackMessage) {
                $feedbackMessage->delete();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Messages were deleted!');
        return $this->_redirect('*/*/');
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('ronisbtfeedback/adminhtml_ronisbtfeedback_grid')->toHtml()
        );
    }
}
