<?php
class Ronisbt_Banners_Model_Image extends Mage_Core_Model_Abstract
{
    protected $_width;
    protected $_height;
    protected $_quality = 90;

    protected $_keepAspectRatio  = true;
    protected $_keepFrame        = true;
    protected $_keepTransparency = true;
    protected $_constrainOnly    = false;
    protected $_backgroundColor  = array(255, 255, 255);

    protected $_baseFile;
    protected $_isBaseFilePlaceholder;
    protected $_newFile;
    protected $_processor;
    protected $_destinationSubdir;
    protected $_angle;

    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setQuality($quality)
    {
        $this->_quality = $quality;
        return $this;
    }

    public function getQuality()
    {
        return $this->_quality;
    }

    public function setKeepAspectRatio($keep)
    {
        $this->_keepAspectRatio = (bool)$keep;
        return $this;
    }

    public function setKeepFrame($keep)
    {
        $this->_keepFrame = (bool)$keep;
        return $this;
    }

    public function setKeepTransparency($keep)
    {
        $this->_keepTransparency = (bool)$keep;
        return $this;
    }

    public function setConstrainOnly($flag)
    {
        $this->_constrainOnly = (bool)$flag;
        return $this;
    }

    public function setBackgroundColor(array $rgbArray)
    {
        $this->_backgroundColor = $rgbArray;
        return $this;
    }

    public function setSize($size)
    {
        list($width, $height) = explode('x', strtolower($size), 2);
        foreach (array('width', 'height') as $wh) {
            $$wh  = (int)$$wh;
            if (empty($$wh))
                $$wh = null;
        }

        $this->setWidth($width)->setHeight($height);

        return $this;
    }

    protected function _checkMemory($file = null)
    {
        return $this->_getMemoryLimit() > ($this->_getMemoryUsage() + $this->_getNeedMemoryForFile($file)) || $this->_getMemoryLimit() == -1;
    }

    protected function _getMemoryLimit()
    {
        $memoryLimit = trim(strtoupper(ini_get('memory_limit')));

        if (!isSet($memoryLimit[0])){
            $memoryLimit = "128M";
        }

        if (substr($memoryLimit, -1) == 'K') {
            return substr($memoryLimit, 0, -1) * 1024;
        }
        if (substr($memoryLimit, -1) == 'M') {
            return substr($memoryLimit, 0, -1) * 1024 * 1024;
        }
        if (substr($memoryLimit, -1) == 'G') {
            return substr($memoryLimit, 0, -1) * 1024 * 1024 * 1024;
        }
        return $memoryLimit;
    }

    protected function _getMemoryUsage()
    {
        if (function_exists('memory_get_usage')) {
            return memory_get_usage();
        }
        return 0;
    }

    protected function _getNeedMemoryForFile($file = null)
    {
        $file = is_null($file) ? $this->getBaseFile() : $file;
        if (!$file) {
            return 0;
        }

        if (!file_exists($file) || !is_file($file)) {
            return 0;
        }

        $imageInfo = getimagesize($file);

        if (!isset($imageInfo[0]) || !isset($imageInfo[1])) {
            return 0;
        }
        if (!isset($imageInfo['channels'])) {
            $imageInfo['channels'] = 4;
        }
        if (!isset($imageInfo['bits'])) {
            $imageInfo['bits'] = 8;
        }
        return round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
    }

    protected function _rgbToString($rgbArray)
    {
        $result = array();
        foreach ($rgbArray as $value) {
            if (null === $value) {
                $result[] = 'null';
            }
            else {
                $result[] = sprintf('%02s', dechex($value));
            }
        }
        return implode($result);
    }

    public function setBaseFile($file)
    {
        $this->_isBaseFilePlaceholder = false;

        if (($file) && (0 !== strpos($file, '/', 0))) {
            $file = '/' . $file;
        }
        $baseDir = Mage::getSingleton('ronisbtbanners/banners_media_config')->getBaseMediaPath();

        if ('/no_selection' == $file) {
            $file = null;
        }
        if ($file) {
            if ((!$this->_fileExists($baseDir . $file)) || !$this->_checkMemory($baseDir . $file)) {
                $file = null;
            }
        }
        if (!$file) {
            // check if placeholder defined in config
            $isConfigPlaceholder = Mage::getStoreConfig("ronisbt/placeholder/{$this->getDestinationSubdir()}_placeholder");
            $configPlaceholder   = '/placeholder/' . $isConfigPlaceholder;
            if ($isConfigPlaceholder && $this->_fileExists($baseDir . $configPlaceholder)) {
                $file = $configPlaceholder;
            }
            else {
                // replace file with skin or default skin placeholder
                $skinBaseDir     = Mage::getDesign()->getSkinBaseDir();
                $skinPlaceholder = "/images/ronisbt/banner/placeholder/{$this->getDestinationSubdir()}";
                $file = $skinPlaceholder;
                if (file_exists($skinBaseDir . $file)) {
                    $baseDir = $skinBaseDir;
                }
                else {
                    $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default'));
                    if (!file_exists($baseDir . $file)) {
                        $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'base'));
                    }
                }
            }
            $this->_isBaseFilePlaceholder = true;
        }

        $baseFile = $baseDir . $file;

        if ((!$file) || (!file_exists($baseFile))) {
            throw new Exception("Here: " . $baseFile);
        }

        $this->_baseFile = $baseFile;

        // build new filename (most important params)
        $path = array(
            Mage::getSingleton('ronisbtbanners/banners_media_config')->getBaseMediaPath(),
            'cache',
            Mage::app()->getStore()->getId(),
            $path[] = $this->getDestinationSubdir()
        );
        if((!empty($this->_width)) || (!empty($this->_height)))
            $path[] = "{$this->_width}x{$this->_height}";

        // add misk params as a hash
        $miscParams = array(
                ($this->_keepAspectRatio  ? '' : 'non') . 'proportional',
                ($this->_keepFrame        ? '' : 'no')  . 'frame',
                ($this->_keepTransparency ? '' : 'no')  . 'transparency',
                ($this->_constrainOnly ? 'do' : 'not')  . 'constrainonly',
                $this->_rgbToString($this->_backgroundColor),
                'angle' . $this->_angle,
                'quality' . $this->_quality
        );

        // if has watermark add watermark params to hash
        if ($this->getWatermarkFile()) {
            $miscParams[] = $this->getWatermarkFile();
            $miscParams[] = $this->getWatermarkImageOpacity();
            $miscParams[] = $this->getWatermarkPosition();
            $miscParams[] = $this->getWatermarkWidth();
            $miscParams[] = $this->getWatermarkHeigth();
        }

        $path[] = md5(implode('_', $miscParams));

        // append prepared filename
        $this->_newFile = implode('/', $path) . $file; // the $file contains heading slash

        return $this;
    }

    public function getBaseFile()
    {
        return $this->_baseFile;
    }

    public function getNewFile()
    {
        return $this->_newFile;
    }

    public function setImageProcessor($processor)
    {
        $this->_processor = $processor;
        return $this;
    }

    public function getImageProcessor()
    {
        if( !$this->_processor ) {
            $this->_processor = new Varien_Image($this->getBaseFile());
        }
        $this->_processor->keepAspectRatio($this->_keepAspectRatio);
        $this->_processor->keepFrame($this->_keepFrame);
        $this->_processor->keepTransparency($this->_keepTransparency);
        $this->_processor->constrainOnly($this->_constrainOnly);
        $this->_processor->backgroundColor($this->_backgroundColor);
        $this->_processor->quality($this->_quality);
        return $this->_processor;
    }

    public function resize()
    {
        if (is_null($this->getWidth()) && is_null($this->getHeight())) {
            return $this;
        }
        $this->getImageProcessor()->resize($this->_width, $this->_height);
        return $this;
    }

    public function rotate($angle)
    {
        $angle = intval($angle);
        $this->getImageProcessor()->rotate($angle);
        return $this;
    }

    public function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    public function saveFile()
    {
        $filename = $this->getNewFile();
        $this->getImageProcessor()->save($filename);
        Mage::helper('core/file_storage_database')->saveFile($filename);
        return $this;
    }

    public function getUrl()
    {
        $baseDir = Mage::getBaseDir('media');
        $path = str_replace($baseDir . DS, "", $this->_newFile);
        return Mage::getBaseUrl('media') . str_replace(DS, '/', $path);
    }

    public function push()
    {
        $this->getImageProcessor()->display();
    }

    public function setDestinationSubdir($dir)
    {
        $this->_destinationSubdir = $dir;
        return $this;
    }

    public function getDestinationSubdir()
    {
        return $this->_destinationSubdir;
    }

    public function isCached()
    {
        return $this->_fileExists($this->_newFile);
    }

    public function clearCache()
    {
        $directory = Mage::getBaseDir('media') . DS.'ronisbt'.DS.'banner'.DS.'cache'.DS;
        $io = new Varien_Io_File();
        $io->rmdir($directory, true);

        Mage::helper('core/file_storage_database')->deleteFolder($directory);
    }

    protected function _fileExists($filename) {
        if (file_exists($filename)) {
            return true;
        } else {
            return Mage::helper('core/file_storage_database')->saveFileToFilesystem($filename);
        }
    }
}