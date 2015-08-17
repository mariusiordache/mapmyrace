<?php

class user_permission_collection extends kms_item_collection {
	
    public function __construct() {
        parent::__construct();
        $this -> _load_crud_data('user_permission');
    }
	
}
