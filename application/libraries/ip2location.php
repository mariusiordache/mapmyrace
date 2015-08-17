<?php

class ip2location {

	protected $_ip = '';

	public function __construct() {
		$this -> _ip = $_SERVER['REMOTE_ADDR'];	
	}	
	
	public function isIPv6($ip) {
		if(strpos($ip, ':')!==false)
			return true;
		return false;
	}
	
	public function detect($ip = '') {
		if($ip == '')
			$ip = $this -> _ip;
		
		if($this -> isIPv6($ip)) {
			return $this -> detectIPv6($ip);
		} else {
			return $this -> detectIPv4($ip);
		}
	}
	
	public function _include_files() {		
		include(APPPATH.'libraries/GeoIP/geoipcity.inc');
		include(APPPATH.'libraries/GeoIP/geoipregionvars.php');
		$this -> _geoip_region_name = $GEOIP_REGION_NAME;
	}
	
	public function detectIPv4($ip) {	
		$this -> _include_files();
		$gi = geoip_open(APPPATH.'libraries/GeoIP/GeoLiteCity.dat', GEOIP_STANDARD);
		$record = geoip_record_by_addr($gi,$ip);	
		$return = $this -> _buildResultFromGeoIPRecord($record);
		geoip_close($gi);
		return $return;
	}
	
	public function detectIPv6($ip) {
		$this -> _include_files();
		$gi = geoip_open(APPPATH.'libraries/GeoIP/GeoLiteCityv6.dat',GEOIP_STANDARD);
		$record = geoip_record_by_addr_v6($gi,"::24.24.24.24");	
		$return = $this -> _buildResultFromGeoIPRecord($record);
		geoip_close($gi);
		return $return;	
	}
	
	protected function _buildResultFromGeoIPRecord($record) {
		
		$CI = get_instance();
		$CI -> load -> model('tag_collection');
		$CI -> load -> library('geography');
		
		
		/* forcing bucharest, @todo change to real detection */
		if($record -> country_code == 'RO') {
			$tag = $CI -> tag_collection -> get_one(array('a.id=219286'));
			return array(
				'tag_id' => $tag['id'],
				'full_label' => $tag['full_label'],
				'label' => $tag['label']
			);
		}	
		 
		/*
		print '<pre>';
		print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "\n";
		print $record->region . " " . $this -> _geoip_region_name[$record->country_code][$record->region] . "\n";
		print $record->city . "\n";
		print $record->postal_code . "\n";
		print $record->latitude . "\n";
		print $record->longitude . "\n";
		print $record->metro_code . "\n";
		print $record->area_code . "\n";
		print $record->continent_code . "\n";
		print '</pre>';
		*/
	}
	
}