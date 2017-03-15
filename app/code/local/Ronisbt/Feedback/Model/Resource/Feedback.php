<?php
class Ronisbt_Feedback_Model_Resource_Feedback extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('ronisbtfeedback/feedback','id'); 
    }

}