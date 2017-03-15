<?php
    class Ronisbt_Banners_Model_Banners extends Mage_Core_Model_Abstract{

        public function __construct(){
            $this->_init('ronisbtbanners/banners');
        }
        
        protected function _beforeSave()
        {
            if($this->isObjectNew()){
                $this->setCreatedAt(Mage::app()->getLocale()->date());
            }
            $this->setUpdatedAt(Mage::app()->getLocale()->date());
        }
}