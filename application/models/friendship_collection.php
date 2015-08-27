<?php

class friendship_collection extends kms_item_collection {

    public function __construct() {
        parent::__construct();
        $this->_load_crud_data('friendship');

        $this->_on('change', 'notify');
    }

    public function notify($data) {
        $this->load->library('client_notifier');

        if ($data['type'] == 'save' && empty($data['data']['id'])) {
            // was an insert, new friendship
            $this->load->decorator('UserDataDecorator');

            $friendship_collection = new UserDataDecorator($this->friendship_collection, array('thumb' => 30));
            $extra = array(
                'fields' => "id, timestamp, target_user_id, request_user_id as user_id"
            );

            $friendships = $friendship_collection->get(array("id" => $data['id']), null, null, null, $extra);
            if (!empty($friendships)) {
                $data['data']['friendship'] = array_shift($friendships);
            }
        }
        
        $this->client_notifier->sendMessageToChannel("friendship", $data);
        
        if ($data['type'] == 'delete') {
            $this->notifyPendingRequestChange($this->current_user->get('login.id'));
        }
        
        if (in_array($data['type'], array('update', 'save'))) {
            $this->notifyPendingRequestChange($data['data']['target_user_id']);
        }
    }
    
    protected function notifyPendingRequestChange($uid) {
        $this->client_notifier->sendMessageToChannel("friendship{$uid}", array(
            'type' => 'pending_count', 
            'count' => $this->getPendingRequestsCount($uid)
        ));
    }
    
    public function getPendingRequestsCount($uid) {
        return $this->get_count("*", array("accepted IS NULL AND target_user_id = {$uid}"));
    }

}
