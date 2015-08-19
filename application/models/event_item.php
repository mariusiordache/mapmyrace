<?php

class event_item extends KMS_Item {
    
    public function is_allowed() {
    
        $CI = get_instance();
        
        if (!empty($this->info['public'])) {
            return true;
        }
        
        
        $uid = $CI->current_user->get('login.id');
        if (!$uid) {
            return false;
        }
        
        if ($this->info['owner_id'] == $uid) { // my event
            return true;
        }
        
        $CI->load->model('friendship_collection');
        
        // get friends list
        $friends = $CI->friendship_collection->get_list(array("(request_user_id = {$uid} OR target_user_id = {$uid}) AND accepted = 1"), null, null, null, array(
            "fields" => "IF (request_user_id = {$uid}, target_user_id, request_user_id) as user_id"
        ));
        
        return in_array($this->info['owner_id'], $friends);
    }
    
    public function getCourseIds() {
        $CI = get_instance();
        $CI->load->model('event_course_collection');
        
        return $CI->event_course_collection->get_list(array('event_id' => $this->id), null, null, null, array('fields' => 'course_id'));
    }
    
}
