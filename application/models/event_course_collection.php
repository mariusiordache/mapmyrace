<?php

class event_course_collection extends kms_item_collection {
	
    public function __construct() {
        parent::__construct();
        $this -> _load_crud_data('event_course');
        $this->_on('change', 'notify');
    }
    
    public function notify($data) {
        
        $channel_name = 'event';
        if (!empty($data['event_id'])) {
            $channel_name = 'event' . $data['event_id'];
        }
        if (!empty($data['data']['event_id'])) {
            $channel_name = 'event' . $data['data']['event_id'];
        }
        
        $this->load->library('client_notifier');

        if ($data['type'] == 'save' && empty($data['data']['id'])) {
            // was an insert, new course
            $this->load->model('course_collection');
            $this->load->decorator('UserDataDecorator');
            $collection = new UserDataDecorator(
                $this->course_collection, 
                array(
                    'thumb' => '20', 
                    'store_key' => 'user'
                ));
            $data['course'] = $collection->get(array('id' => $data['data']['course_id']));
            $data['course'] = array_shift($data['course']);
        }
        
        
        $this->client_notifier->sendMessageToChannel($channel_name, $data);
    }
	
}
