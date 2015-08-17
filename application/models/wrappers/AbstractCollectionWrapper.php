<?php

abstract class AbstractCollectionWrapper {

    protected $_CI = null;
    protected $filters = array();
    protected $extra = array();

    public function __construct() {

        $this->_CI = get_instance();

        $this->configure();
    }

    function setFilters($filters) {
        $this->filters = $filters;
    }

    public function getFilters() {
        return $this->filters;
    }

    function getExtra() {
        return $this->extra;
    }

    function setExtra($extra) {
        $this->extra = $extra;
    }
    
    public function getCollectionObject() {
        $c = $this->getCollectionString();
        $this->_CI->load->model($c);
        
        return $this->_CI->$c;
    }
    
    protected function preProcessFilters(&$filters) {
        
    }

    abstract protected function configure();
    abstract public function getCollection();
    abstract public function getCollectionString();
    
    /**
     * Forward all unknown methods to the collection object
     */
    public function __call($name, $arguments) {
        
        $c = $this->getCollectionObject();
        
        switch($name) {
            case 'get':
            case 'get_one':
            case 'new_instance':
                $c = $this->getCollection();
                break;
        }
        
        return call_user_func_array(array($c, $name), $arguments);
    }
    
    public function get($filters, $sort, $offset, $limit, $extra) {
        
        $this->preProcessFilters($filters);
        $c = $this->getCollection();
        
        return $c->get($filters, $sort, $offset, $limit, $extra);
    }
    
}
