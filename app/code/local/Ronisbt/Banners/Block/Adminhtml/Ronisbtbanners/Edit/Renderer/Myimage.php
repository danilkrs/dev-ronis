<?php

class Ronisbt_Banners_Block_Adminhtml_Ronisbtbanners_Edit_Renderer_Myimage extends Varien_Data_Form_Element_Abstract
{
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    /**
     * Return element html code
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '';

        if ((string)$this->getValue()) {
            $url = $this->_getUrl();

            if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
                $url =  Mage::getSingleton('ronisbtbanners/banners_media_config')->getBaseMediaUrl() . DS . $url;
            }

            $html = '<a href="' . $url . '"'
                . ' onclick="imagePreview(\'' . $this->getHtmlId() . '_image\'); return false;">'
                . '<img src="' . $url . '" id="' . $this->getHtmlId() . '_image" title="' . $this->getValue() . '"'
                . ' alt="' . $this->getValue() . '" height="100" width="150" class="small-image-preview v-middle" />'
                . '</a> ';
            /*$additional = Mage::app()->getLayout()->createBlock('Mage_Adminhtml_Block_Template');
            $additional->setTemplate('siteblocks/image.phtml')
                ->setImageUrl($url);
            $html = $additional->toHtml();*/
#закомментированный выше код мы можем использовать для того, что бы html код строился в темплейте, актуально при использовании сложных элементов
        }
        $this->setClass('input-file');
        $html .= parent::getElementHtml();
        return $html;
    }

    /**
     * Return html code of hidden element
     *
     * @return string
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="' . parent::getName() . '[value]" value="' . $this->getValue() . '" />';
    }

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        return $this->getValue();
    }

    /**
     * Return name
     *
     * @return string
     */
    public function getName()
    {
        return  $this->getData('name');
    }
}
