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
        $base_url = rtrim($this->config->item('base_url'), '/');

        $this->topnav = array(
            'dashboard' => array('url' => $base_url .'/dashboardv2', 'label' => 'My themes', 'active' => false),
             // 'attribution' => array('url' => $base_url.'/attribution', 'label' => 'Theme attribution', 'active' => false),
            'editor' => array('url' => $base_url.'/app#new-theme', 'label' => 'Create theme', 'active' => false)
        );

        if (has_any_role(array('qa.user', 'qa.superuser'))) {
            $this->topnav['autoplay'] = array('url' => $base_url . '/autoplay', 'label' => 'Auto Play', 'active' => false);
        }

        if (has_role('developer')) {
            $this->topnav['launcher_templates'] = array('url' => $base_url . '/launcher_templates', 'label' => 'Project templates', 'active' => false);
            $this->topnav['translate'] = array('url' => $base_url . '/translator', 'label' => 'Translations', 'active' => false);
        }
        
        if (has_role('reviewer')) {
            $this->topnav['get_apk'] = array('url' => $base_url . '/reviews', 'label' => 'My Reviews', 'active' => false);
        }
        
        if (has_role('uploader')) {
            $this->topnav['get_apk'] = array('url' => $base_url . '/dashboardv2/get_apk', 'label' => 'Upload', 'active' => false);
        }

        if (has_any_role(array('bi.user', 'bi.superuser'))) {
            $this->topnav['reporting'] = array('url' => $base_url . '/statsv2', 'label' => 'Statistics', 'active' => false);
        }
        
    }

    public function show_page($page_id = '') {
        
        if (isset($this->topnav[$page_id])) {
            $this->topnav[$page_id]['active'] = true;
        }
        
        $this->assets->addDependencies(array(
            'jquery', 'underscore', 'bootstrap', 'backbone'
        ));

        $uid = $this->current_user->get('login.id');
        
        $this->load->model('user_notification_collection');
        $notifications = $this->user_notification_collection->get(array('user_id' => $uid), 'status ASC, timestamp DESC', 0, 20);
        
//        var_dump($notifications); die;
        $unread = $this->user_notification_collection->get_count("*", array('user_id' => $uid, 'status' => 0));
        
        $this->set_template_var('notifications', $notifications);
        $this->set_template_var('unread_notifications', $unread);
        
        $this->set_template_var('topnav', $this->topnav);
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
