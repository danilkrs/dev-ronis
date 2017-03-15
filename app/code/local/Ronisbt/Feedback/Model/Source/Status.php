<?php
class Ronisbt_Feedback_Model_Source_Status
{
    const UNREAD = '0';
    const READ = '1';
    const SPAM = '2';

    public function toOptionArray()
    {
        return array(
            array('value' => self::UNREAD, 'label' => 'Unread'),
            array('value' => self::READ, 'label' => 'Read'),
            array('value' => self::SPAM, 'label' => 'Spam')
        );
    }

    public function toArray()
    {
        return array(
            self::UNREAD => 'Unread',
            self::READ => 'Read',
            self::SPAM => 'Spam'
        );
    }
}
