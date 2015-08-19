<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('main_controller.php');

class events extends main_controller {
    
    protected function getEvent($id) {
         // check if is public
        $this->load->model('event_collection');
        $event = $this->event_collection->new_instance((int) $id);
        
        if (!$event->id || !$event->is_allowed()) {
            redirect('/dashboard/events');
        }
        
        
        return $event;
    }
    
    public function view($id) {
     
        $event = $this->getEvent($id);
        
        $this->assets->addDependencies(array(
            'jqueryslimscroll'
        ));
        $this->bootstrap->frontend();
        $this->bootstrap->setup_fileupload();

        $this->assets->add_css('css/dashboard.css', false);
        $this->assets->add_js('js/dashboard/courses.js', false);
        $this->assets->add_js('js/common.js');
        
    }
    
    public function map($id) {
        $event = $this->getEvent($id);
        
        $this->bootstrap->frontend();
        
        $this->assets->addDependency('moment');
        $this->assets->add_css('css/map.css', false);
        $this->assets->add_js('https://maps.googleapis.com/maps/api/js?key=AIzaSyAmDfFBHTVc_gDC4imtGtMJsveLgxI5N_A&libraries=geometry', false);
        $this->assets->add_js('js/dashboard/mymap.js', false);
        
        $this->set_template_var('couse_ids', join(',', $event->getCourseIds()));
        
        $this->set_template('dashboard/map.tpl');
    }
    
    public function get_compare_data() {
        $course_ids = array_map(function($item) {
            return (int) $item;
        }, explode(",", $this->input->get('course_ids')));
        
        $this->load->decorator('UserDataDecorator');
        $this->load->decorator('UserMapMarkerDecorator');
        $this->load->model('course_collection');
        
        $collection = new UserDataDecorator($this->course_collection, array('thumb' => 'marker', 'store_key' => 'user'));
        $courses = $collection->get(array('id' => $course_ids));
        
        $lib_path = $this->config->item('webroot_path') . '/application/libraries/gpx/';
        require_once $lib_path . 'GpxFile.php';
        require_once $lib_path . 'FileAlign.php';

        $align = new FileAlign();
        $dir = $this->config->item('private_data_dir') . '/';
        
        foreach ($courses as $course) {
            $file = new GpxFile($dir . $course['file_id']);
            $file->setName($course['name']);
            $align->addFile($file, $course['user']['profile_pic_url']);
        }

        $this->show_ajax($align->getData());
        
    }

}