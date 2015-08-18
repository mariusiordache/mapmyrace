<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('member_area.php');

class dashboard extends member_area {


    public function index() {
        $this->assets->add_css('css/dashboard.css', false);
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
