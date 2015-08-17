<?php

require_once 'member_area.php';
require_once 'REST_Controller.php';
require_once(APPPATH . 'libraries/strategyProviders/ProviderBuilder.php');
require_once(APPPATH . 'libraries/strategyProviders/LoginProvider.php');

class ajax extends REST_Controller {

    protected $queryData = array();
    
    protected function getCollection($collection) {
        $collection_name = "{$collection}_collection";
        if (model_exists($collection_name)) {
            $this->load->model($collection_name);
            return $this->$collection_name;
        } else {
            $base_path = $this->config->item('webroot_path') . '/application/models/wrappers/';

            require_once ($base_path . 'AbstractCollectionWrapper.php');

            $wrapper_file = $base_path . $collection . '.php';

            if (!file_exists($wrapper_file)) {
                throw new AjaxException("Collection {$collection} does not exists. You probably want to define a custom wrapper for a existing collection?");
            }

            require_once($wrapper_file);
            if (!class_exists($collection)) {
                throw new AjaxException("class {$collection} is missing from the wrapper file {$wrapper_file}");
            }

            return new $collection();
            
        }
    }

    protected function buildQueryData($collection) {
        $this->queryData['filters'] = $this->input->get('filters');
        $this->queryData['sort'] = $this->input->get('sort');
        $this->queryData['offset'] = $this->input->get('offset');
        $this->queryData['limit'] = $this->input->get('limit');

        $this->queryData['filters'] = !empty($this->queryData['filters']) ? $this->queryData['filters'] : array();
        $this->queryData['offset'] = $this->queryData['offset'] != '' ? (int) $this->queryData['offset'] : null;
        $this->queryData['limit'] = $this->queryData['limit'] != '' ? (int) $this->queryData['limit'] : null;
        $this->queryData['extra'] = array();

        $c = $this->getCollection($collection);
        
        if ($c instanceOf AbstractCollectionWrapper) {
            $this->queryData['extra'] = $c->getExtra();
            $this->queryData['filters'] = array_merge($this->queryData['filters'], $c->getFilters());
        }
        
        return $c;
    }

    public function resource_get($collection) {

        $c = $this->buildQueryData($collection);

        $this->show_ajax(
            $c->get(
                $this->queryData['filters'],
                $this->queryData['sort'],
                $this->queryData['offset'],
                $this->queryData['limit'],
                $this->queryData['extra']
            )
        );
    }

    public function resource_post($collection) {
        $c = $this->getCollection($collection);

        $values = $this->input->put();

        $this->show_ajax($c->save($values, 0));
    }

    public function resource_put($collection, $id) {
        $c = $this->getCollection($collection);

        $values = $this->input->put();

        $this->show_ajax($c->save($values, $id));
    }
    
    public function resource_patch($collection, $id) {
        $c = $this->getCollection($collection);
        $values = $this->_patch_args;
        $this->show_ajax($c->save($values, $id));
    }

    public function resource_delete($collection, $id = null) {
        $c = $this->getCollection($collection);

        $this->show_ajax($c->delete($id));
    }


    public function count_get($collection) {
        $c = $this->buildQueryData($collection);
        
        $this->show_ajax(
            $c->get_count(
                '*',
                $this->queryData['filters'], 
                $this->queryData['extra']
            )
        );
    }

}
