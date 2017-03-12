 <?php
class Ronisbt_Banners_Model_Resource_Banners_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('ronisbtbanners/banners');
    }
    public function getCount(){
    	$this->load();
    	return count($this->_items);
    }
}