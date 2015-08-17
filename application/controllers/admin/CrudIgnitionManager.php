<?php
	include('admin_controller.php');
	class CrudIgnitionManager extends admin_controller {
		
		protected $_views_path = '';
		protected $_url = '';
		
		public function __construct() {
		
			parent::__construct();
			$this -> load -> helper('url');
			$this -> load -> helper('form');	
			$this -> load -> config('crud_ignition');	
			
			$this -> _url        = $this -> config -> item('url', 'crud_ignition');
			$this -> _views_path = $this -> config -> item('views_path', 'crud_ignition');	
			
			$this -> template_data = array(
				'scripts'  => array(
					base_url() . 'assets/bootstrap/js/bootstrap-datepicker.js', 
					base_url() . 'assets/js/bootstrap-timepicker.js',
					base_url() . 'assets/js/CrudIgnitionEditItem.js',
					base_url() . 'assets/js/CrudIgnitionManager.js',
				),
				'crud_ignition_url' => base_url() . $this -> _url . 'CrudIgnition',
				'crud_ignition_views_path' => APPPATH . 'views/' . $this -> _views_path
			);
						
			if($report = $this -> input -> get('report')) {
				$this -> template_data['report'] = $report;
			}
			
			$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
			$this->output->set_header("Pragma: no-cache"); 
		}
		
		public function set_model($model) {			
			$this -> load -> model($model.'_collection', 'model');
			
			$this -> list_url = base_url() . $this -> _url . $this -> model -> data_model . '_collection_manager/';
			
			$urls = array(			
				'list_url'      => $this -> list_url,
				'edit_url'      => $this -> list_url . 'edit/',
				'duplicate_url' => $this -> list_url . 'duplicate/',
				'add_url'       => $this -> list_url . 'add/',
				'fields'        => $this -> model -> data_fields,
				'model'         => $this -> model -> data_model,
				'photo_fields'  => $this -> model -> photos
			);
			
			$this -> template_data = array_merge($this -> template_data, $urls);			
		}
		
		public function index() {
			
			$items_per_page = 100;
			
			//GET SEARCH PARAMETERS AND SORTING SETTIGNS
			$query_params = array();
			$search_filters = array();	
			
			$search_field = $this -> input -> get('search_field');
			if($search_field !== false) {
				$search_value = $this -> input -> get('search_value');
				if(!empty($search_value))
					$search_filters[$search_field] = $search_value;
			} else {
				$search_value = '';
			}
			
			if(false === ($current_page = $this -> input -> get('page'))) {
				$current_page = 1;
			} else {
				$current_page = max(1, (int)trim($current_page, '/'));
			}
			
			$sort = '';
			if(false === ($sort_field = $this -> input -> get('sort_field'))) {
				$sort_field = '';
			}
			if(false !== ($sort_dir = $this -> input -> get('sort_dir'))) {
				$sort = !empty($sort_field)>0 ? $sort_field.' '.$sort_dir : '';
			}
			
				
			/* SEARCH / GET ITEMS */			
			if(method_exists($this, 'add_default_search_filters')) {
				$this -> add_default_search_filters($search_filters);
			}
			$list = $this -> model -> get($search_filters, $sort, ($current_page-1)*$items_per_page, $items_per_page);
			
			/* SETUP PAGINATION */
			$query_params = array('search[field]='.$search_field, 'search[value]='.$search_value);
			$query_params[] = 'sort_field='.$sort_field;
			$query_params[] = 'sort_dir='.$sort_dir;		
			
			$paging_config = array(
				'url_template' => $this -> list_url.'?'.implode('&', $query_params).'&page=%page_no%',
				'per_page'     => $items_per_page,
				'total_pages'  => ceil($this -> model -> last_get_count / $items_per_page),
				'total_items'  => $this -> model -> last_get_count,
				'cur_page'     => $current_page
			);			
			
			/* SET TEMPLATE VARIABLES */
			$data = array_merge(array(
				'list'          => $list, 
				'paging'        => $paging_config,
				'sort_field'    => $sort_field,
				'sort_dir'      => $sort_dir,
				'search_field'  => $search_field,
				'search_value'  => $search_value,
				'js_vars' => array('CRUD_MANAGER_URL' => base_url() . $this -> _url . $this -> model -> data_model.'_collection_manager/')
			), $this -> template_data);			
			
			
			/* LOAD VIEW */
			if(isset($this -> list_view)) {
				$this -> load -> view($this -> list_view, $data);
			} else {
				$this -> load -> view($this -> _views_path . 'CrudIgnition/default_list.php', $data);			
			}
		}
		
		protected function _item_not_found($id) {
			redirect($this -> template_data['list_url'] . '?report=' . urlencode('Item #'.$id.' not found'));			
		}
		
		public function duplicate($id=0) {
			$item = $this -> _get_item_data($id);			
			if($item === false)
				$this -> _item_not_found($id);
			
			$item['id'] = '';
			
			$data = array_merge(array('item'=>$item), $this -> template_data);		
			
			if(count($_POST)) {
				$this -> edit(0);
			}
			
			if(file_exists(APPPATH.'views/'. $this -> _views_path . $this -> model -> data_model.'Manager_edit.php')) {
				$this -> load -> view($this -> _views_path . $this -> model -> data_model.'Manager_edit.php', $data);
			} else {
				$this -> load -> view($this -> _views_path . 'CrudIgnition/default_edit.php', $data);			
			}	
		}
		
		public function delete() {
			$ok = $this -> model -> delete($this -> input -> post('id'));
			echo json_encode(array('success'=>$ok, 'id'=>$this -> input -> post('id')));
			exit;
		}
		
		public function add() {		
			$this -> edit(0);		
		}
		
		protected function _get_item_data($id=0) {			
			if($id > 0) {
				$data = $this -> model -> get_one(array('id'=>$id));
			} else {
				$data = $this -> model -> get_blank();
			}
			return $data;
		}
		
		protected function _save($data, $id=0) {
			
			if($id == 0) 
				$ok = $this -> model -> add($data);
			else	
				$ok = $this -> model -> update($id, $data);			
			
			return ($ok === false ? false : true);
		}		
		
		public function edit($id=0) {

			$item = $this -> model -> new_instance($id);
			$item -> load_extended_info();
				
			if($item -> id != $id)
				$this -> _item_not_found($id);
									
			$data = array_merge(array('item'=>$item -> info), $this -> template_data);		
			
			if(count($_POST)) {				
				if(isset($_FILES['photos'])) {
					$_POST['photos'] = $_FILES['photos'];
				}
				
				$result = $item -> save($_POST);
				
				if($result['success']==true) 
				{
					redirect($this -> template_data['list_url'] . '?report=' . urlencode('Item successfully ' . ($id > 0 ? 'updated' : 'added')));
				} 
				else 
				{
					$data['errors'] = $result['errors'];
				}								
			}
			
			if(isset($this -> edit_view)) {
				$this -> load -> view($this -> edit_view, $data);
			} else {
				$this -> load -> view($this -> _views_path . 'CrudIgnition/default_edit.php', $data);			
			}				
		}
		
		public function blank($value) {
			return $value;
		}
		
		public function current_date($value) {
			return date('Y-m-d H:i:s');
		}
		
		public function encrypt_password($value) {
			return sha1($value);
		}
		
		public function current_ip($value) {
			return ip2long($_SERVER['REMOTE_ADDR']);
		}
	}

?>
