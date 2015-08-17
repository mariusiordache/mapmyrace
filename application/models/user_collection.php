<?php

class user_collection extends kms_item_collection {

    public function __construct() {
        parent::__construct();
        $this->_load_crud_data('user');
    }

    public function getAutoBotId() {
        $user = $this->getAutoBot();
        return $user['id'];
    }
    
    public function getByRole($role) {

        $this->load->model('user_role_collection');
        $this->load->model('role_collection');
        
        return $this->get(array(
            "r.name = '{$role}'"
        ), 'id asc', null, null, array(
            'sql_join' => "
                INNER JOIN {$this->user_role_collection->get_data_table()} ur ON ur.user_id = a.id
                INNER JOIN {$this->role_collection->get_data_table()} r ON r.id = ur.role_id
                "
        ));
    }
    
    public function getAutoBot() {
        $user = $this->user_collection->get_one(array('username' => 'auto-uploader'));
        if (empty($user)) {
            throw new Exception("User auto-uploader does not exists!");
        }
        
        return $user;
    }
    
    public function save($data, $id = 0, $action = null) {
        $return = parent::save($data, $id, $action);
        
        if ($return['success'] == true && $id == $this->current_user->get('login.id')) {
            // we should update the current logged in user now
            foreach($data as $k => $v) {
                $this->current_user->set("login.{$k}", $v);
            }
            
        }
        
        return $return;
    }
}
