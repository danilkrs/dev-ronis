<?php
class Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_ronisbtbanners';
        $this->_blockGroup = 'ronisbtbanners';
        $this->_headerText = 'RonisBT Banners';
        $this->_addButtonLabel = 'Add new banner';
    }
}
