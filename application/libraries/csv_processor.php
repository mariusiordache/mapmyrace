<?php

class csv_processor {

	public function __construct() {
		get_instance() -> load -> library('fileManagement');
	}
	
	protected function _is_comment($line) {
		return substr($line, 0, 1) === '#';
	}
	
	protected function _get_delimiter($line) {
		$delimiters = array(',', ';', "\t");
		
		$max_count = 0;
		$max_count_delimiter = ',';		
		
		foreach($delimiters as $delimiter) {
			if(substr_count($line, $delimiter) > $max_count) {
				$max_count = substr_count($line, $delimiter);
				$max_count_delimiter = $delimiter;
			}
		}
		
		return $max_count_delimiter;		
	}
	
}