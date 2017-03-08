<?php

class Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsBlockGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ronisbtbanners/banners')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('title', array(
            'header'    => Mage::helper('ronisbtbanners')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('banner_status', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'align'     => 'left',
            'type'      => 'options',
            'options'   => Mage::getModel('ronisbtbanners/source_status')->toArray(),
            'index'     => 'banner_status'
        ));

        $this->addColumn('url', array(
            'header'    => Mage::helper('ronisbtbanners')->__('URL'),
            'index'     => 'url',
            'type'      => 'options '
        ));

        
        $this->addColumn('position', array(
            'header'    => Mage::helper('ronisbtbanners')->__('Position'),
            'index'     => 'position',
            'type'      => 'options '
        ));

        $this->addColumn('image', array(
            'header'    => Mage::helper('ronisbtbanners')->__('Image'),
            'align'     => 'left',
            'index'     => 'image',
            'filter'    => false, 
            'sortable'  => false,
            'renderer'  => 'Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners_Grid_Renderer_Image',
        ));

        $this->addColumn('created', array(
            'header'    => Mage::helper('ronisbtbanners')->__('Created At'),
            'index'     => 'created',
            'type'      => 'date',

        ));



        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setIdFieldName('banner_id');
        $this->getMassactionBlock()
            ->addItem('delete',
                array(
                    'label' => Mage::helper('ronisbtbanners')->__('Delete'),
                    'url' => $this->getUrl('*/*/massDelete'),
                    'confirm' => Mage::helper('ronisbtbanners')->__('Are you sure?')
                )
            )
            ->addItem('status',
                array(
                    'label' => Mage::helper('ronisbtbanners')->__('Update status'),
                    'url' => $this->getUrl('*/*/massStatus'),
                    'additional' =>
                        array('banner_status'=>
                        array(
                            'name' => 'banner_status',
                            'type' => 'select',
                            'class' => 'required-entry',
                            'label' => Mage::helper('ronisbtbanners')->__('Status'),
                            'values' => Mage::getModel('ronisbtbanners/source_status')->toOptionArray()
                        )
                    )
                )
            );

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('banner_id' => $row->getId()));
    }

}