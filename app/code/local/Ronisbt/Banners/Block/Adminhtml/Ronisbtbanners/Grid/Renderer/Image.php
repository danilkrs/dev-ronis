<?php
class Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners_Grid_Renderer_Image 
extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if( ! $row->getImage()) {
            return '';
        }
        $url =  Mage::getSingleton('ronisbtbanners/banners_media_config')->getBaseMediaUrl() .DS. $row->getImage();
        $html = "<img src='$url' width='300' height='auto'>";
        return $html;
    }
}