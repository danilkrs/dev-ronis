<?php
class Ronisbt_Feedback_Model_Source_Captchastatus
{
    const ENABLED = '1';
    const DISABLED = '0';

    public function toOptionArray()
    {
        return array(
            array('value' => self::ENABLED, 'label'=>'Enabled'),
            array('value' => self::DISABLED, 'label'=>'Disabled'),
        );
    }

    public function toArray()
    {
        return array(
            self::DISABLED => 'Disabled',
            self::ENABLED => 'Enabled',
        );
    }
}