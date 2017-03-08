 <?php
class Ronisbt_Banners_Model_Resource_Banners extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('ronisbtbanners/banners','banner_id'); 
    }

    }