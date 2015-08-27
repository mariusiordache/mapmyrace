<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once('main_controller.php');

class member_area extends main_controller {

    private $breadcrumbs = array();
    
    protected function getBreadcrumbs() {
        return $this->breadcrumbs;
    }
    
    public function addBreadcrumb($label, $url = null) {
        $this->breadcrumbs[] = array(
            'label' => $label,
            'url' => $url
        );
    }

    public function __construct() {
        parent::__construct();

        force_login('');
    }

    public function show_page($page_id = '') {
        
        $this->load->model('friendship_collection');
        
        $request_pending = $this->friendship_collection->getPendingRequestsCount($this->current_user->get('login.id'));
        
        if (isset($this->topnav[$page_id])) {
            $this->topnav[$page_id]['active'] = true;
        }
        
        $this->assets->addDependencies(array(
            'jquery', 'underscore', 'bootstrap', 'backbone'
        ));

        $uid = $this->current_user->get('login.id');
        
        $this->set_template_var('pending_requests_count', $request_pending);
        $this->set_template_var('user', $this->current_user->get('login'));
        $this->set_template_var('breadcrumbs', $this->getBreadcrumbs());
        
        if (isset($GLOBALS['config_file']['db'])) {
            $db = current($GLOBALS['config_file']['db']);
            if (isset($db['hostname']) && !in_array($db['hostname'], array('127.0.0.1', 'localhost')) && defined('DEVELMODE') && DEVELMODE) {
                $this->set_template_var('is_remote_db', $db['hostname']);
            }
        }
        
        $this->load->library('bootstrap');
        $this->bootstrap->frontend();
        
        parent::show_page();
    }

}
