<?php

abstract class tme_library {
    
    protected $_CI = null;
    
    public function __construct() {
        $this->_CI = get_instance();
        
        $models = $this->get_models();
        
        if (is_array($models)) {
            foreach($models as $model) {
                if (is_string($model)) {
                    $this->_CI->load->model($model);
                }
            }
        }
        
        $this->configure();
    }
    
    abstract protected function configure();
    abstract protected function get_models();
}
