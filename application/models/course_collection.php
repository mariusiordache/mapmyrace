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
    
    public function suggestMine($course_id, $filters = array()) {
        return $this->suggest($course_id, array_merge($filters, array(
            'user_id' => $this->current_user->get('login.id')
        )));
    }
    
    public function suggestFriends($course_id, $filters = array()) {
        $this->load->model('friendship_collection');
        $uid = $this->current_user->get('login.id');
        
        $fr = $this->friendship_collection->get_data_table();
        
        return $this->suggest($course_id, array_merge($filters, array("a.user_id <> {$uid}")), array(
            'sql_join' => "INNER JOIN (
                SELECT request_user_id  as user_id FROM {$fr} WHERE target_user_id = {$uid} AND accepted = 1
                UNION 
                SELECT target_user_id as user_id FROM {$fr} WHERE request_user_id = {$uid} AND accepted = 1
            ) f ON f.user_id = a.user_id"
        ));
    }
    
    protected function suggest($course_id, $filters, $extra = array()) {
        $c = $this->get_one(array('id' => $course_id));
        
        $filters[] = "id <> {$c['id']}";
        
        return $this->get(array_merge($filters, array(
            "
                (
                    (offset_left <= {$c['center_x']} AND offset_right >= {$c['center_x']})
                    
                )
                    AND
                (
                    (offset_top <= {$c['center_y']} AND offset_bottom >= {$c['center_y']})
                )  
            "
        )), null, null, null, $extra);
    }
	
}
