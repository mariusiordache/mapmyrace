<?php

class course_collection extends kms_item_collection {
	
    public function __construct() {
        parent::__construct();
        $this -> _load_crud_data('course');
    }
    
    public function getFilePath($id) {
        $data = $this->get_one(array('id' => $id));
        return  $this->config->item('private_data_dir') . '/' . $data['file_id'];
    }
    
    public function delete($id) {
        $path = $this->getFilePath($id);
        if (is_file($path)) {
            unlink($path);
        }
        
        return parent::delete($id);
    }
	
}
