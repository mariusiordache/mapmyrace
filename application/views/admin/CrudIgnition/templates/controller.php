<?php echo '<?php'; ?>

require_once('CrudIgnitionManager.php');
class <?php echo $model; ?>_collection_manager extends CrudIgnitionManager {
	
	public function __construct() {
		parent::__construct();
		$this -> set_model('<? echo $model; ?>');
	}
	
	/* demo custom edit 
	
	function custom_edit($id = 0) {
		
		$data = $this -> template_data;
		<?php						
			foreach($fields as &$field) {
				if(isset($field['data_source']) && strlen($field['data_source'])>0) {	
					echo '$data[\''.$field['name'].'_options\'] = $this -> crud_lib -> _process_data_source($this -> model -> crig_fields[\''.$field['name'].'\'][\'data_source\']);';
				}
			}
		?>
		
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

<?php echo '?>'; ?>