<?php
class Ronisbt_Feedback_Block_Adminhtml_Ronisbtfeedback_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('banner_form');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('ronisbtfeedback_feedback');
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save',array('id'=>$this->getRequest()->getParam('id'))),
                'method' => 'post',
            )
        );

        $fieldset = $form->addFieldset('base_fieldset',
            array(
                'legend'=> 'General Information',
                'class' => 'fieldset-wide')
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('name', 'label', array(
            'label'     => 'Name',
        ));

        $fieldset->addField('email', 'label', array(
            'label'     => 'Email',
        ));

        $fieldset->addField('phone', 'label', array(
            'label'     => 'Phone',
        ));

        $fieldset->addField('subject', 'label', array(
            'label'     => 'Subject',
        ));

        $fieldset->addField('other_subject', 'label', array(
            'label'     => 'Other subject',
        ));

        $fieldset->addField('status', 'select', array(
            'name'      => 'status',
            'label'     => 'Status',
            'values'   => Mage::getModel('ronisbtfeedback/source_status')->toOptionArray()
        ));

        $fieldset->addField('customer_id', 'label', array(
            'label'     => 'Customer ID',
        ));

        $fieldset->addField('user_agent', 'label', array(
            'label'     => 'User Agent',
        ));

        $fieldset->addField('user_ip', 'label', array(
            'label'     => 'User IP',
        ));

        $fieldset->addField('message', 'textarea', array(
            'name'      => 'message',
            'label'     =>  'Message',
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}