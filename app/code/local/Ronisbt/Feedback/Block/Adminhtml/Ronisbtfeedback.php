<?php
class Ronisbt_Feedback_Block_Adminhtml_Ronisbtfeedback extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_ronisbtfeedback';
        $this->_blockGroup = 'ronisbtfeedback';
        $this->_headerText = 'Contact Form';
        $this->_removeButton('add');
    }
}
