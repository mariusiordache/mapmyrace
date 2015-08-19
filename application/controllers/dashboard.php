<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('member_area.php');

class dashboard extends member_area {

    public function index() {
        $this->assets->addDependencies(array(
            'jqueryslimscroll'
        ));
        $this->bootstrap->frontend();
        $this->bootstrap->setup_fileupload();

        $this->assets->add_css('css/dashboard.css', false);
        $this->assets->add_js('js/dashboard/courses.js', false);
        $this->assets->add_js('js/common.js');
        
        $this->load->model('course_collection');
        $courses = $this->course_collection->get(array('uid' => $this->current_user->get('login.id')));
        $this->set_template_var('courses', $courses);
    }

    public function upload() {
        if (file_exists($_FILES['file']['tmp_name'])) {

            $dir = $this->config->item('private_data_dir');
            $uid = $this->current_user->get('login.id');


            $profile_pic = substr(md5(time() . $_FILES['file']['tmp_name']), 0, rand(5, 8));
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);


            $filename = kms_build_folder_for_string($profile_pic) . '/' . $profile_pic . '.' . $ext;

            $path = $dir . '/' . $filename;

            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0775, true);
            }

            move_uploaded_file($_FILES['file']['tmp_name'], $path);


            require_once $this->config->item('webroot_path') . '/application/libraries/gpx/GpxFile.php';

            $fileObj = new GpxFile($path);
            $data = $fileObj->getData();
            $fileObj->resetT0();
            
            $last = array_pop($data);
            $name = $fileObj->getName();
            $name = !empty($name) ? $name : $_FILES['file']['name'];
            
            $fp = array_shift($data);
            
            $this->load->model('course_collection');
            
            $course = $this->course_collection->save(array(
                'name' => $name,
                'date_created' => date('Y-m-d H:i:s', $fp->time),
                'file_id' => $filename,
                'length' => $last->distance,
                'duration' => $last->timediff,
                'user_id' => $uid
            ));
            
            
            $course['data']['id'] = $course['id'];
            $this->show_ajax($course);
        } else {
            throw new AjaxException("An error ocurred (100). Please try again later!");
        }
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
    
    public function map() {
        
        $this->bootstrap->frontend();
        
        $this->assets->addDependency('moment');
        $this->assets->add_css('css/map.css', false);
        $this->assets->add_js('https://maps.googleapis.com/maps/api/js?key=AIzaSyAmDfFBHTVc_gDC4imtGtMJsveLgxI5N_A&libraries=geometry', false);
        $this->assets->add_js('js/dashboard/mymap.js', false);
    }

    public function courses() {
        $this->assets->add_css('css/dashboard.css', false);
    }

    public function friends() {
        $this->assets->add_css('css/dashboard.css', false);
        $this->assets->add_js('js/dashboard/friends.js', false);
        $this->assets->add_js('bower_components/moment/moment.js', false);

        $this->load->model('friendship_collection');
        $this->load->model('user_collection');
        $this->load->decorator('UserDataDecorator');

        $uid = $this->current_user->get('login.id');

        $friendship_collection = new UserDataDecorator($this->friendship_collection, array('thumb' => 30));
//        $friendship_collection = $this->friendship_collection;
        $extra = array(
            'fields' => "id, timestamp, IF (request_user_id <> {$uid}, request_user_id, target_user_id) user_id"
        );

        $friends = $friendship_collection->get(array("accepted = 1 AND (request_user_id = {$uid} OR target_user_id = {$uid})"), 'a.timestamp DESC', null, null, $extra);
        $request_pending = $friendship_collection->get(array("accepted IS NULL AND target_user_id = {$uid}"), 'a.timestamp DESC', null, null, $extra);
        $request_sent = $friendship_collection->get(array("accepted IS NULL AND request_user_id = {$uid}"), 'a.timestamp DESC', null, null, $extra);


        $this->set_template_var('friendship', array(
            'friends' => $friends,
            'pending' => $request_pending,
            'sent' => $request_sent
        ));
    }

}
