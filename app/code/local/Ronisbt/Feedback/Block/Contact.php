<?php
class Ronisbt_Feedback_Block_Contact extends Mage_Core_Block_Template{
    const SITE_KEY 		 = 'ronisbtfeedback/CAPTCHA/site_key';
    const CAPTCHA_STATUS = 'ronisbtfeedback/CAPTCHA/enabled';

    public function getUser()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }
    
    public function getSiteKey(){
    	return Mage::getStoreConfig(self::SITE_KEY);
    }


    public function getCaptchaStatus(){
    	return Mage::getStoreConfig(self::CAPTCHA_STATUS);
    }

}