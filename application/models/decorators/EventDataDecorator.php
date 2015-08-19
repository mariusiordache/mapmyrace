<?php

class EventDataDecorator extends kms_collection_decorator {

    protected $thumb = '80';
    protected $store_key = null;
    protected $primary_key = 'id';
    
    protected function configure() {
        parent::configure();
        
        $this->isUniqueData();
        
        $this->getLoader()->model('course_collection');
        
        $this->extras['sql_join'] = "INNER JOIN {$this->_CI->course_collection->get_data_table()} c ON c.id = a.course_id";
        $this->extras['group_by'] = "a.event_id";
        $this->extras['fields'] = "a.event_id, COUNT(*) as num_courses, MIN(duration) as best_time, ROUND(AVG(length)/ 1000, 2) as avg_length";
    }
    
    protected function getCollection() {
        return "event_course_collection";
    }

    protected function getDataKey() {
        return "event_id";
    }

}
