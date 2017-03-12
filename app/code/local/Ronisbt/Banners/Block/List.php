<?php
class Ronisbt_Banners_Block_List extends Mage_Core_Block_Template{
    public function getBanners()
    {
        return Mage::getModel('ronisbtbanners/banners')->getCollection()
            ->addFieldToFilter('banner_status', Ronisbt_Banners_Model_Source_Status::ENABLED)
            ->setOrder('position', 'ASC');
    }
}
