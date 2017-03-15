<?php

class Ronisbt_Feedback_Block_Adminhtml_Ronisbtfeedback_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_ronisbtfeedback';
        $this->_blockGroup = 'ronisbtfeedback';

        parent::__construct();

        $this->_updateButton('save', 'label', 'Save Feedback');
        $this->_updateButton('delete', 'label', 'Delete Feedback');

        $this->_addButton('saveandcontinue', array(
            'label'     => 'Save and Continue Edit',
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('ronisbtfeedback_feedback')->getId()) {
            return Mage::helper('ronisbtfeedback')->__("Edit Feedback from %s", $this->escapeHtml(Mage::registry('ronisbtfeedback_feedback')->getName()));
        }
        else {
            return Mage::helper('ronisbtfeedback')->__('New Feedback');
        }
    }
}