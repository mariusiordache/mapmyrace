<?php

class GeoCoding {
    
    public $data = array(); 
    
    private function __construct() { }
    
    public static function getAddress($longitude, $latitude) {  
            
        $json = file_get_contents("http://nominatim.maproom.ro/reverse?format=json&lat={$latitude}&lon={$longitude}");

        $data = json_decode($json, true);
        
        if ($data && !empty($data["address"])) {
            $ret = array();
            $ret['s'] = !empty($data["address"]["road"]) ? $data["address"]["road"] : '';
            $ret['o'] = !empty($data["address"]["city"]) ? $data["address"]["city"] : '';
            $ret['v'] = !empty($data["address"]["village"]) ? $data["address"]["village"] : '';
            $ret['j'] = !empty($data["address"]["county"]) ? $data["address"]["county"] : '';
            $ret['t'] = !empty($data["address"]["country"]) ? $data["address"]["country"] : '';
            $ret['display_name'] = !empty($data["display_name"]) ? $data["display_name"] : '';
            return $ret;
        }
        
        return null;
    }
}