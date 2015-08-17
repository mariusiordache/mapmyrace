<?php

class UserDataDecorator extends kms_collection_decorator {

    protected $thumb = '80';
    protected $store_key = null;
    protected $primary_key = 'user_id';
    
    protected function configure() {
        parent::configure();
        
        $this->isUniqueData();
    }
    
    public function getData() {
        $data = parent::getData();
        $new_data = array();
        
        foreach($data as &$row) {
            $new_data[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'username' => $row['username'],
                'signup_date' => $row['date_created'],
                'email' => $row['email'],
                'profile_pic_url' => get_profile_pic_url(array('id' => $row['id'], 'profile_pic' => $row['profile_pic']), $this->thumb)
            );
        }
        
        return $new_data;
    }
    
    protected function getCollection() {
        return "user_collection";
    }

    protected function getDataKey() {
        return "id";
    }

}
