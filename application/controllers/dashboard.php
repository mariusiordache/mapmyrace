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
        $courses = $this->course_collection->get(array('user_id' => $this->current_user->get('login.id')));
        $this->set_template_var('courses', $courses);
    }

    public function events() {
        $this->assets->addDependencies(array(
            'jqueryslimscroll'
        ));
        $this->bootstrap->frontend();
        $this->assets->add_css('css/dashboard.css', false);
        $this->assets->add_js('js/common.js');
        
        $this->load->model('event_collection');
        $this->load->model('friendship_collection');
        $this->load->decorator('EventDataDecorator');
        $this->load->decorator('UserDataDecorator');
        
        $ft = $this->friendship_collection->get_data_table();
        
        
        $coll = new UserDataDecorator(new EventDataDecorator($this->event_collection), array('thumb' => 30, 'primary_key' => 'owner_id', 'store_key' => 'user'));
        $user_id = $this->current_user->get('login.id');
        
        $my_events = $coll->get(array('owner_id' => $user_id, 'public' => '0'));
        $public_events = $coll->get(array('public' => '1'));
        
        $friends_events = $coll->get(array("ft1.id IS NOT NULL OR ft2.id IS NOT NULl"), null, null, null, array(
            'sql_join' => "
                LEFT JOIN {$ft} ft1 ON ft1.request_user_id = a.owner_id AND ft1.accepted = 1 AND ft1.target_user_id = {$user_id}
                LEFT JOIN {$ft} ft2 ON ft2.target_user_id = a.owner_id AND ft2.accepted = 1 AND ft2.request_user_id = {$user_id}
            "
        ));
                
        $this->set_template_var('events', array(
            'mine' => array(
                'label' => 'My Events',
                'data' => $my_events
            ),
            'friends' => array(
                'label' => 'Friends Events',
                'data' => $friends_events
            ),
            'public' => array(
                'label' => 'Public Events',
                'data' => $public_events
            ),
        ));
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
    
    
    
    public function map() {
        
        $this->bootstrap->frontend();
        
        $this->assets->addDependency('moment');
        $this->assets->add_css('css/map.css', false);
        $this->assets->add_js('https://maps.googleapis.com/maps/api/js?key=AIzaSyAmDfFBHTVc_gDC4imtGtMJsveLgxI5N_A&libraries=geometry', false);
        $this->assets->add_js('js/dashboard/mymap.js', false);
        
        $this->set_template_var('couse_ids', $this->input->get('course_ids'));
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
