<?php
class Ronisbt_FeedBack_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'ronisbtfeedback/settings/email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';
    const XML_PATH_ENABLED          = 'contacts/contacts/enabled';
    const SECRET_KEY                = 'ronisbtfeedback/CAPTCHA/secret_key';
    const CAPTCHA_STATUS            = 'ronisbtfeedback/CAPTCHA/enabled';
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('contact_us')
            ->setFormAction(Mage::getUrl('*/*/post') );
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
    public function postAction(){
        $post = $this->getRequest()->getPost();
        $captchaStatus = Mage::getStoreConfig(self::CAPTCHA_STATUS);
        if ($post) {
            try {
                if(!isset($post['customer_id']) && $captchaStatus){
                    $url = "https://www.google.com/recaptcha/api/siteverify";
                    $secret = Mage::getStoreConfig(self::SECRET_KEY);
                    $postData = "secret=" . $secret . "&response=" . $post['g-recaptcha-response'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $serverOutput = curl_exec($ch);
                    $result = json_decode($serverOutput);
                    curl_close($ch);
                    if(!$result->success) {
                        throw new Exception();
                    }
                }

                $postObject = new Varien_Object();
                $postObject->setData($post);
                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['message']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['subject']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['other_subject']) , 'NotEmpty')) {
                    $error = true;

                }
                if ($error) {
                    throw new Exception();
                }

                $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }else{
                    $feedbackModel = Mage::getModel('ronisbtfeedback/feedback');
                    $feedbackModel->setData($post);
                    $feedbackModel->setUserAgent(Mage::helper('core/http')->getHttpUserAgent());
                    $feedbackModel->setUserIp(Mage::helper('core/http')->getRemoteAddr());
                    $feedbackModel->save();
                }
                Mage::getSingleton('customer/session')->unsetData('back_with_input');
                Mage::getSingleton('customer/session')->addSuccess('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.');
                $this->_redirect('*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addError('Unable to submit your request. Please, try again later');
                $this->_redirect('*/');
                Mage::getSingleton('customer/session')->setBackWithInput($post);
                return;
            }
        } else {
            $this->_redirect('*/*/');
        }
    }
}