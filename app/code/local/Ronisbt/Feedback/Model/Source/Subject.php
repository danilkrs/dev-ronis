<?php
class Ronisbt_Feedback_Model_Source_Subject
{
    const OTHER = '1';

    public function toOptionArray()
    {
        return array(
            array('value' => self::OTHER, 'label'=> 'Other'),
        );
    }

    public function toArray()
    {
        return array(
            self::OTHER => 'Other',
        );
    }
}