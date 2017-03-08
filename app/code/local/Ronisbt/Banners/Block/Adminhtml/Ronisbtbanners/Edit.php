<?php

class Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'banner_id';
        $this->_controller = 'adminhtml_ronisbtbanners';
        $this->_blockGroup = 'ronisbtbanners';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('ronisbtbanners')->__('Save Banner'));
        $this->_updateButton('delete', 'label', Mage::helper('ronisbtbanners')->__('Delete Banner'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "


            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('ronisbtbanners_banner')->getId()) {
            return Mage::helper('ronisbtbanners')->__("Edit Banner '%s'", $this->escapeHtml(Mage::registry('ronisbtbanners_banner')->getTitle()));
        }
        else {
            return Mage::helper('ronisbtbanners')->__('New Banner');
        }
    }
}