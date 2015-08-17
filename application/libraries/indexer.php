<?php

class indexer {

	public function __construct($index='activedeals') {
		$this -> initialize();
		$this -> load_index($index);
	}

	public function save($data, $unique_id=null) {	
	
		$parsed_data = $this -> _parse_data($data);					
		if($unique_id === null) $unique_id   = $this -> _extract_unique_id($data);
		
		return $this -> _save($data, $unique_id);
		
	}
	
	public function remove($unique_id) {
		$this -> _remove($unique_id);
	}
	
	public function initialize() {
		#extend
	}
	
	public function load_index() {
		#extend
	}
	
	protected function _extract_unique_id($data) {
		if(!isset($data['id'])) {
			throw new Exception(kms_lang('Unique identifier "id" not found'));
		} else {
			return $data['id'];
		}
	}
	
	protected function _parse_data($data) {	
		#extend
		return $data;
	}
	
	protected function _save(&$data, $unique_id) {
		#extend 
	}
	
	protected function _remove($unique_id) {
		#extend
	}

}