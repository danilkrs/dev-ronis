<?php
class Ronisbt_Feedback_Model_Feedback extends Mage_Core_Model_Abstract{

    public function __construct(){
        $this->_init('ronisbtfeedback/feedback');
    }
    
    protected function _beforeSave()
    {
        if($this->isObjectNew()){
            $this->setCreatedAt(Mage::app()->getLocale()->date());
        }
        $this->setUpdatedAt(Mage::app()->getLocale()->date());
    }
}