<?php

class Ronisbt_Feedback_Block_Adminhtml_Ronisbtfeedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsFeedbackGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ronisbtfeedback/feedback')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => 'Name',
            'align'     => 'left',
            'index'     => 'name',
        ));

        $this->addColumn('email', array(
            'header'    => 'Email',
            'align'     => 'left',
            'index'     => 'email',
        ));

        $this->addColumn('phone', array(
            'header'    => 'Phone',
            'align'     => 'left',
            'index'     => 'phone',
        ));

        $this->addColumn('subject', array(
            'header'    => 'Subject',
            'align'     => 'left',
            'index'     => 'subject',
            'type'      => 'options',
            'options'   => Mage::getModel('ronisbtfeedback/source_subject')->toArray()
        ));

        $this->addColumn('other_subject', array(
            'header'    => 'Other subject',
            'align'     => 'left',
            'index'     => 'other_subject',
        ));

        $this->addColumn('message', array(
            'header'    => 'Message',
            'align'     => 'left',
            'index'     => 'message',
        ));

        $this->addColumn('status', array(
            'header'    => 'Status',
            'align'     => 'left',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getModel('ronisbtfeedback/source_status')->toArray()
        ));

        $this->addColumn('customer_id', array(
            'type'      => 'number',
            'header'    => 'Customer ID',
            'align'     => 'left',
            'index'     => 'customer_id',
        ));

        $this->addColumn('user_agent', array(
            'header'    => 'User agent',
            'align'     => 'left',
            'index'     => 'user_agent',
        ));

        $this->addColumn('user_ip', array(
            'header'    => 'User ip',
            'align'     => 'left',
            'index'     => 'user_ip',
        ));

        $this->addColumn('created_at', array(
            'header'    => 'Created At',
            'index'     => 'created_at',
            'type'      => 'date',
            'format'    => 'yyyy-MM-dd HH:mm:ss'

        ));

        $this->addColumn('updated_at', array(
            'header'    => 'Updated At',
            'index'     => 'updated_at',
            'type'      => 'date',
            'format'    => 'yyyy-MM-dd HH:mm:ss'

        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setIdFieldName('id');
        $this->getMassactionBlock()
            ->addItem('delete',
                array(
                    'label' => 'Delete',
                    'url' => $this->getUrl('*/*/massDelete'),
                    'confirm' => 'Are you sure?'
                )
            )
            ->addItem('status',
                array(
                    'label' => 'Update status',
                    'url' => $this->getUrl('*/*/massStatus'),
                    'additional' =>
                        array('status'=>
                        array(
                            'name' => 'status',
                            'type' => 'select',
                            'class' => 'required-entry',
                            'label' => 'Status',
                            'values' => Mage::getModel('ronisbtfeedback/source_status')->toOptionArray()
                        )
                    )
                )
            );

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/feedback/grid', array('_current'=>true));
    }
 
}