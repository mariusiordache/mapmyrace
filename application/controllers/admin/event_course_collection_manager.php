<?php
require_once('CrudIgnitionManager.php');
class event_course_collection_manager extends CrudIgnitionManager {
	
	public function __construct() {
		parent::__construct();
		$this -> set_model('<? echo $model; ?>');
	}
	
	/* demo custom edit 
	
	function custom_edit($id = 0) {
		
		$data = $this -> template_data;
				
		$item = $this -> _get_item_data($id);			
		if($item === false)
			$this -> _item_not_found($id);
									
		$data['item'] = $item;
			
		if(count($_POST)) {
			if($this -> _validate_post('update')) {				
				$saved = $this -> _save($this -> input -> post(), $id);
			
				if($saved) {
					// code for succes save
				} else {
					// code for unsuccessful save
				}				
			} 
		}
			
		$this -> load -> view($this -> _views_path . $this -> model -> crig_model.'Manager_edit.sample.php', $data);
				
	}
	
	*/
	
}

?>