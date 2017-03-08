<?php
class Ronisbt_Banners_Model_Source_Status
{
    const ENABLED = '1';
    const DISABLED = '0';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::ENABLED, 'label'=>Mage::helper('ronisbtbanners')->__('Enabled')),
            array('value' => self::DISABLED, 'label'=>Mage::helper('ronisbtbanners')->__('Disabled')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::DISABLED => Mage::helper('ronisbtbanners')->__('Disabled'),
            self::ENABLED => Mage::helper('ronisbtbanners')->__('Enabled'),
        );
    }
}