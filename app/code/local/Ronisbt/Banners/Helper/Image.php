<?php

class Ronisbt_Banners_Helper_Image extends Mage_Core_Helper_Abstract
{
    protected $_model;
    protected $_scheduleResize = false;
    protected $_scheduleRotate = false;
    protected $_angle;
    protected $_banner;
    protected $_imageFile;
    protected $_placeholder;

    protected function _reset()
    {
        $this->_model = null;
        $this->_scheduleResize = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_banner = null;
        $this->_imageFile = null;
        return $this;
    }

    public function init(Ronisbt_Banners_Model_Banners $banner, $attributeName, $imageFile=null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('ronisbtbanners/image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->setBanner($banner);

        

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            $this->_getModel()->setBaseFile($this->getBanner()->getImage());
        }
        return $this;
    }

    public function resize($width, $height = null)
    {
        $this->_getModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;
        return $this;
    }

    public function setQuality($quality)
    {
        $this->_getModel()->setQuality($quality);
        return $this;
    }

    public function keepAspectRatio($flag)
    {
        $this->_getModel()->setKeepAspectRatio($flag);
        return $this;
    }

    public function keepFrame($flag, $position = array('center', 'middle'))
    {
        $this->_getModel()->setKeepFrame($flag);
        return $this;
    }

    public function keepTransparency($flag, $alphaOpacity = null)
    {
        $this->_getModel()->setKeepTransparency($flag);
        return $this;
    }

    public function constrainOnly($flag)
    {
        $this->_getModel()->setConstrainOnly($flag);
        return $this;
    }

    public function backgroundColor($colorRGB)
    {
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getModel()->setBackgroundColor($colorRGB);
        return $this;
    }

    public function rotate($angle)
    {
        $this->setAngle($angle);
        $this->_getModel()->setAngle($angle);
        $this->_scheduleRotate = true;
        return $this;
    }

    public function placeholder($fileName)
    {
        $this->_placeholder = $fileName;
    }

    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/ronisbt/banner/placeholder/'.$attr;
        }
        return $this->_placeholder;
    }

    public function __toString()
    {
        try {
            $model = $this->_getModel();

            if ($this->getImageFile()) {
                $model->setBaseFile($this->getImageFile());
            } else {
                $model->setBaseFile($this->getBanner()->getData($model->getDestinationSubdir()));
            }

            if ($model->isCached()) {
                return $model->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                $url = $model->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }

    protected function _setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    protected function _getModel()
    {
        return $this->_model;
    }

    protected function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    protected function getAngle()
    {
        return $this->_angle;
    }

    protected function setBanner($banner)
    {
        $this->_banner = $banner;
        return $this;
    }

    protected function getBanner()
    {
        return $this->_banner;
    }

    protected function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    protected function getImageFile()
    {
        return $this->_imageFile;
    }

    protected function parseSize($string)
    {
        $size = explode('x', strtolower($string));
        if (sizeof($size) == 2) {
            return array(
                'width' => ($size[0] > 0) ? $size[0] : null,
                'heigth' => ($size[1] > 0) ? $size[1] : null,
            );
        }
        return false;
    }

    public function getOriginalWidth()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalWidth();
    }
 
    public function getOriginalHeigh()
    {
        return $this->getOriginalHeight();
    }

    public function getOriginalHeight()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalHeight();
    }

    public function getOriginalSizeArray()
    {
        return array(
            $this->getOriginalWidth(),
            $this->getOriginalHeight()
        );
    }

    public function validateUploadFile($filePath) {
        if (!getimagesize($filePath)) {
            Mage::throwException($this->__('Disallowed file type.'));
        }

        $_processor = new Varien_Image($filePath);
        return $_processor->getMimeType() !== null;
    }
}