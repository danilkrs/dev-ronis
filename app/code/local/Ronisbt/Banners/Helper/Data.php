<?php
class Ronisbt_Banners_Helper_Data extends Mage_Core_Helper_Abstract {
public function PositionList(){
    $banners = Mage::getModel('ronisbtbanners/banners')->getCollection();
    $list = array();
    if(count($banners) == 0){
        $list[1] = 1;
    } else { 
        for($i = 0; $i < count($banners) + 1; $i++){
    	   $list[$i + 1] = $i + 1;
        }
    }
    return $list;
    }
}