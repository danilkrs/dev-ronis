<?php
class Ronisbt_FeedBack_Block_Contact extends Mage_Core_Block_Template{
    public function getUser()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }
}