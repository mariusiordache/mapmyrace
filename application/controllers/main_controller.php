<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class main_controller extends KMS_Web_Controller {

    public function __construct() {
        parent::__construct();

        /* bootstrap library does stuff for both front-end and back-end */
        $this->load->library('bootstrap');
        $this->set_js_page_data('base_url', $this->config->item('base_url'));
        $this->set_js_page_data('img_url', $this->config->item('img_url', 'assets'));
        
        $socketio = $this->config->item('socketio');
        $socketio['domain'] = empty($socketio['domain']) ? @$_SERVER['HTTP_HOST'] : $socketio['domain'];
        $socketio['port'] = empty($socketio['webport']) ? 8080 : $socketio['webport'];
        
        $this->set_js_page_data('socketio', $socketio);
    }

    public function show_page($template = null) {
        
        $this->assets->addDependencies(array(
            'jquery', 'bootstrap'
        ));
        
        if ($template) {
            $this->_template = $template;
        } else if (!$this->_template) {
            $RTR = $this->router;
            
            $file = ltrim("{$RTR->fetch_directory()}/{$RTR->fetch_class()}/{$RTR->fetch_method()}.tpl", '/');
            $this->_template = $file;
        }
        
        if (!$this->template_engine->templateExists($this->_template)) {
            $this->_template = 'default.tpl';
        }
        
        parent::show_page();
    }

}