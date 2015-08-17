<?php

class CrudIgnition extends KMS_Admin_Controller {

    private $_views_path = '';
    private $_url = '';
    private $_migrations = array();
    private $_migration_status = '';

    public function __construct() {

        parent::__construct();

        $this->load->library('bootstrap');

        if (!$this->current_user->is_admin()) {
            redirect();
        }

        $this->db->save_queries = true;


        $this->load->config('crud_ignition');
        $this->_url = $this->config->item('url', 'crud_ignition');
        $this->_views_path = $this->config->item('views_path', 'crud_ignition');

        $this->load->library('crud_lib');
        $this->load->helper('url');

        $this->template_data = array(
            'crud_ignition_url' => base_url() . $this->_url . 'CrudIgnition/',
            'crud_ignition_views_path' => APPPATH . 'views/' . $this->_views_path,
            'create_url' => base_url() . $this->_url . 'CrudIgnition/add',
            'scripts' => array(base_url() . 'assets/js/CrudIgnition.js')
        );

        if ($report = $this->input->get('report')) {
            $this->template_data['report'] = $report;
        }

        $this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");
        
        $this->output->setPage();
    }

    public function createFromTable($table) {

        $data = array(
            'table' => $table,
            'model' => '',
            'fields' => $this->crud_lib->get_fields_from_table($table),
            'quick_fields' => $this->config->item('quick_fields', 'crud_ignition'),
            'quick_rules' => $this->config->item('quick_rules', 'crud_ignition')
        );

        $this->template_data['scripts'][] = base_url() . 'assets/js/CrudIgnitionEditModel.js';
        $data = array_merge($data, $this->template_data);
        $this->load->view($this->_views_path . 'CrudIgnition/edit.php', $data);
    }

    public function add() {

        $data = array(
            'table' => '',
            'model' => '',
            'fields' => array(),
            'quick_fields' => $this->config->item('quick_fields', 'crud_ignition'),
            'quick_rules' => $this->config->item('quick_rules', 'crud_ignition')
        );

        $this->template_data['scripts'][] = base_url() . 'assets/js/CrudIgnitionEditModel.js';

        $data = array_merge($data, $this->template_data);
        $this->load->view($this->_views_path . 'CrudIgnition/edit.php', $data);
    }

    public function index() {
        $models = $this->crud_lib->get_existing_models();
        $tables = $this->crud_lib->get_tables();

        foreach ($tables as &$table) {
            foreach ($models as $model) {
                if ($model['prefixed_table'] == $table['name']) {
                    $table['model'] = $model;
                }
            }
        }
        $data = array('tables' => $tables, 'new_migration_count' => $this->count_new_migrations(), 'run_migrations_url' => $this->template_data['crud_ignition_url'] . 'run_new_migrations');
        $data = array_merge($data, $this->template_data);
        $this->load->view($this->_views_path . 'CrudIgnition/index.php', $data);
    }

    public function edit($model) {

        $this->crud_lib->load_model($model);
        $data = array(
            'table' => $this->crud_lib->get_table(),
            'model' => $this->crud_lib->get_model(),
            'fields' => $this->crud_lib->get_fields(),
            'quick_fields' => $this->config->item('quick_fields', 'crud_ignition'),
            'quick_rules' => $this->config->item('quick_rules', 'crud_ignition')
        );

        foreach ($data['fields'] as &$field) {
            unset($field['forge']);
            if (isset($field['options']))
                unset($field['options']);
        }

        $this->template_data['scripts'][] = base_url() . 'assets/js/CrudIgnitionEditModel.js';

        $data = array_merge($data, $this->template_data);
        $this->load->view($this->_views_path . 'CrudIgnition/edit.php', $data);
    }

    public function check_sync() {

        $result = $this->crud_lib->check_sync($this->input->post('model'));
        echo json_encode(array('sync' => $result));
        exit;
    }

    public function delete_model() {
        $result = $this->crud_lib->delete_model($this->input->post('model'), $this->input->post('drop_table'));
        $this->save_migration_data();
        echo json_encode($result);
        exit;
    }

    public function save() {

        $messages = array();

        /*
          1. setup database
          2. create model and model_item files
          3. create admin controller
          4. create view / edit templates
         */
        $this->load->library('form_validation');

        $this->form_validation->set_rules('model', 'Model', 'required|callback_alpha_underscore');
        $this->form_validation->set_rules('table', 'Table', 'required|callback_alpha_underscore');

        $success = $this->form_validation->run();

        if (!$success) {
            echo json_encode(array(
                'success' => false,
                'errors' => array(
                    $this->form_validation->error('model', ' ', ' '),
                    $this->form_validation->error('table', ' ', ' ')
                ),
                'messages' => $messages
            ));
            exit;
        } else {
            $messages[] = 'Model and table name are OK.';
        }

        $data = array(
            'model' => $this->input->post('model'),
            'table' => $this->input->post('table'),
            'fields' => $this->crud_lib->process_post_fields($this->input->post('fields'))
        );

        # DATABASE 
        $this->crud_lib->set_table($data['table']);
        $this->crud_lib->set_model($data['model']);
        $this->crud_lib->set_fields($data['fields']);

        if ($this->crud_lib->table_exists()) {
            $this->crud_lib->sync_fields_with_database();
            $messages[] = 'Table and model synced successfully.';
        } else {
            $this->crud_lib->create_table();
            $messages[] = 'Table created successfully.';
        }


        #MODEL CONFIG

        $result = $this->crud_lib->save_config();
        if ($result !== true) {
            echo json_encode(array('success' => false, 'errors' => $result, 'messages' => $messages));
            exit;
        } else {
            $messages[] = 'Model definition saved to config file.';
        }

        # MODELS		

        $result = $this->crud_lib->sync_templates($this->input->post('overwrite'));
        if ($result !== true) {
            echo json_encode(array('success' => false, 'errors' => $result, 'messages' => $messages));
            exit;
        } else {
            $messages[] = 'Templates synced successfully';
        }

        $this->save_migration_data();

        echo json_encode(array('success' => true));
    }

    /* form validation callback */

    public function alpha_underscore($value) {

        if (preg_match('/[^a-z0-9_]/', $value)) {
            $this->form_validation->set_message('alpha_underscore', 'The %s field can only contain A-Z, 0-9 and _');
            return false;
        }

        return true;
    }

    /* form validation callback */

    public function unique_except_current($param, $value) {
        /* TODO */
    }

    public function save_migration_data() {
        if (count($this->db->queries) > 0) {

            $migration_id = 'migration_' . time();
            $migration_file = APPPATH . 'migrations/CrudIgnition/' . $migration_id . '.json';

            $queries = $this->db->queries;
            foreach ($queries as &$query) {
                $query = str_replace('`' . $this->db->dbprefix, '`%%dbprefix%%', $query);
                $query = str_replace(' ' . $this->db->dbprefix, ' %%dbprefix%%', $query);
                $query = str_replace('"' . $this->db->dbprefix, '"%%dbprefix%%', $query);
                $query = str_replace('\'' . $this->db->dbprefix, '\'%%dbprefix%%', $query);
            }

            file_put_contents($migration_file, json_encode($queries));
            chmod($migration_file, 0664);

            $migration_status_file = APPPATH . 'migrations/CrudIgnition/migration_status.json';
            $migrations = file_exists($migration_status_file) ? json_decode(file_get_contents($migration_status_file), true) : array();
            $migrations[$migration_id] = 'applied';
            file_put_contents($migration_status_file, json_encode($migrations));
        }
    }

    public function load_migrations() {

        $this->load->helper('kms_folder');
        $migration_files = kms_get_files_filtered(APPPATH . 'migrations/CrudIgnition', '/.*\.json$/');

        foreach ($migration_files as $file) {
            $contents = json_decode(file_get_contents($file), true);
            $filename = array_pop(explode('/', $file));
            if ($filename != 'migration_status.json') {
                $migration_id = str_replace(array('.json', 'migration_'), '', $filename);
                $this->_migrations[$migration_id] = $contents;
            } else {
                $this->_migration_status = $contents;
            }
        }

        ksort($this->_migrations, SORT_NUMERIC);
    }

    public function count_new_migrations() {
        $this->load_migrations();
        $count = 0;
        foreach ($this->_migrations as $migration_id => $queries) {
            if (!isset($this->_migration_status['migration_' . $migration_id])) {
                $count++;
            }
        }

        return $count;
    }

    public function run_new_migrations() {
        $this->load_migrations();
        $this->db->db_debug = false;
        foreach ($this->_migrations as $migration_id => $queries) {
            if (!isset($this->_migration_status['migration_' . $migration_id])) {
                //run queries and set status to "applied"
                $this->_migration_status['migration_' . $migration_id] = 'applied';
                foreach ($queries as $query) {
                    $query = str_replace('%%dbprefix%%', $this->db->dbprefix, $query);
                    $query = str_replace('%%database%%', $this->db->database, $query);
                    $this->db->query($query);
                }
            }
        }

        file_put_contents(APPPATH . 'migrations/CrudIgnition/migration_status.json', json_encode($this->_migration_status));

        $base_path = $this->config->item('webroot_path') . '/application/models/sql/';
        $lookup_dirs = array('functions', 'procedures', 'triggers', 'views');

        $result = array();
        // enable debug mode
        $this->db->db_debug = TRUE;

        foreach ($lookup_dirs as $lookup_dir) {
            $lookup_dir_path = $base_path . $lookup_dir;

            $sql_files = kms_folder_files($lookup_dir_path);

            foreach ($sql_files as $sql_file) {
                $query = file_get_contents($lookup_dir_path . '/' . $sql_file);
                $query = str_replace('ts__', $this->db->dbprefix, $query);
                $query = str_replace('%%dbprefix%%', $this->db->dbprefix, $query);
                $query = str_replace('%%database%%', $this->db->database, $query);


                $command = "mysql -h {$this->db->hostname} -u {$this->db->username} ";
                if (strlen($this->db->password)) {
                    $command .= " -p'" . str_replace("'", "\'", $this->db->password) . "' ";
                }

                $tmpfile = "/tmp/" . md5(microtime());
                $command .= "{$this->db->database} < {$lookup_dir_path}/{$sql_file} 2>&1 > {$tmpfile}; cat {$tmpfile}; rm -rf {$tmpfile}";
                $output = array();
                exec($command, $output);

                $result[$lookup_dir][$sql_file] = $output;
            }
        }

        $this->load->library('bootstrap');
        $this->bootstrap->frontend();
        $this->set_template('admin/run_migrations.tpl');
        $this->set_template_var('mysql_triggers', $result);
        $this->set_template_var('crud_ignition_url',  $this->template_data['crud_ignition_url']);
        
        
        $this->show_page();
    }

}
