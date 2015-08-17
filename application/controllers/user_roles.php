<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include('member_area.php');

class user_roles extends member_area {

    public function __construct() {
        parent::__construct();

        $this->load->model('role_collection');
        $this->load->model('user_collection');
        $this->load->model('permission_collection'); 
        $this->load->model('user_role_collection');
        $this->load->model('role_permission_collection');
        $this->load->model('user_permission_collection');
    }

    protected function frontend() {
        $this->bootstrap->frontend();
        
        $this->assets->add_js('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', false);
        
        $this->assets->add_js('//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js', false);
        $this->assets->add_js('//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js', false);
        
        $this->assets->add_js('bootstrap-select/bootstrap-select.min.js', false);
        $this->assets->add_css('bootstrap-select/bootstrap-select.min.css', false);
        $this->assets->add_css('//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css', false);
        
    }
    
    public function index() {
        $this->frontend();

        $users = $this -> user_collection -> get(array(),'username',null,null, array('fields'=>'username,id'));
        $roles = $this -> role_collection -> get(array(), 'name');
        $permissions = $this -> permission_collection -> get();
        
        $this->set_template_var('users', $users);
        $this->set_template_var('roles', $roles);
        $this->set_template_var('permissions', $permissions);
        
        $this->set_template('web/user-management/roles.tpl');

        $this->assets->add_js('js/user-role-management.js', false);
        $this->assets->add_css('css/user-role-management.css', false);

        $this->show_page();
    }
    
    public function load_for_role($role_id) {
        $role_id = (int)$role_id;
        $permissions      = $this -> permission_collection -> get(array(), 'permission');
        $role_permissions = $this -> role_permission_collection -> get(array('role_id'=>$role_id));
        foreach($permissions as &$p) {
            $p['selected'] = false;
            foreach($role_permissions as $rp) {
                if($rp['permission_id'] == $p['id']) {
                    $p['selected'] = true;
                }
            }            
        }
        $this -> show_ajax($permissions);
    }
    
    public function save_role_permission() {
        $role_id = (int) $this -> input -> post('role_id');
        $permission_id = (int) $this -> input -> post('permission_id');
        
        $old_rp = $this -> role_permission_collection -> new_instance();
        $exists = $old_rp -> load_from_params(array('role_id'=>$role_id, 'permission_id'=>$permission_id));
        if(!$exists) {
            $rp = $this -> role_permission_collection -> new_instance();
            $result = $rp -> save(array('role_id'=>$role_id, 'permission_id'=>$permission_id));
            if($result['success']) {
                $on = true;
            } else {
                $on = false;
            }
        } else {
            $old_rp -> delete();
            $on = false;
        }
        $this -> show_ajax(array('on'=>$on));
    }
    
    public function load_for_user($user_id) {        
        $user_id = (int)$user_id;
        $roles      = $this -> role_collection -> get(array(), 'name');
        $user_roles = $this -> user_role_collection -> get(array('user_id'=>$user_id));
        foreach($roles as &$r) {
            $r['selected'] = false;
            foreach($user_roles as $ur) {
                if($ur['role_id'] == $r['id']) {
                    $r['selected'] = true;
                }
            }            
        }
        $this -> show_ajax($roles);
    }
    
    public function save_user_role() {
        $user_id = (int) $this -> input -> post('user_id');
        $role_id = (int) $this -> input -> post('role_id');
        
        $old_ur = $this -> user_role_collection -> new_instance();
        $exists = $old_ur -> load_from_params(array('user_id'=>$user_id, 'role_id'=>$role_id));
        if(!$exists) {
            $ur = $this -> user_role_collection -> new_instance();
            $result = $ur -> save(array('user_id'=>$user_id, 'role_id'=>$role_id));
            if($result['success']) {
                $on = true;
            } else {
                $on = false;
            }
        } else {
            $old_ur -> delete();
            $on = false;
        }
        $this -> show_ajax(array('on'=>$on));
    }
    
    public function save_new_role() {
        $role_name = $this -> input -> post('role_name');
        $role = $this -> role_collection -> new_instance();
        $result = $role -> save(array('name'=>$role_name));
        $role -> load_info();
        $result['role'] = $role -> info;
        $this -> show_ajax($result);
    }
    
    public function save_new_permission() {
        $permission = $this -> input -> post('permission');
        $p = $this -> permission_collection -> new_instance();
        $result = $p -> save(array('permission'=>$permission));
        $p -> load_info();
        $result['permission'] = $p -> info;
        $this -> show_ajax($result);
    }
    
    public function sync_role($id) {
        
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        
        $role = $this -> role_collection -> new_instance((int)$id);
        
        switch($_SERVER['REQUEST_METHOD']) {
            case 'PUT':
                $result = $role -> save(array('name'=>$data -> name));
                break;
            case 'DELETE':
                $this -> role_permission_collection -> delete_all(array('role_id'=>$role->id));
                $this -> user_role_collection -> delete_all(array('role_id'=>$role->id));
                $role -> delete();                
                $result = array('success'=>true);
                break;
        }
                
        $this -> show_ajax($result);
    }
    
    public function sync_permission($id) {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        
        $permission = $this -> permission_collection -> new_instance((int)$id);
        
        switch($_SERVER['REQUEST_METHOD']) {
            case 'PUT':
                $result = $permission -> save(array('permission'=>$data -> permission));
                break;
            case 'DELETE':
                $this -> role_permission_collection -> delete_all(array('role_id'=>$permission->id));
                $this -> user_permission_collection -> delete_all(array('role_id'=>$permission->id));
                $permission -> delete();                
                $result = array('success'=>true);
                break;
        }
        
        $this -> show_ajax($result);
    }
}
?>