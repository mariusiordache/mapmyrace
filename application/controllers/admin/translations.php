<?php

require_once('CrudIgnitionManager.php');

class translate extends CrudIgnitionManager {

    public function __construct() {
        parent::__construct();

        $this->template_engine->assign('current_menu', 'translations');
        $this->set_model('template_string');

        $this->template_engine->assign('crud_ignition_url', $this->template_data['crud_ignition_url']);
        $this->load->model('language_collection');
        $this->languages = $this->language_collection->get();

        $this->list_url = $this->config->item('admin_url') . '/translate';
    }

    public function index() {

        $core->set_template_var('launchers', $this->launchers);
        $core->set_template('admin/translate/index.tpl');
    }

    public function launcher($launcher_id, $language_id) {

        $launcher_id = (int) $launcher_id;
        $language_id = (int) $language_id;

        $strings = $this->model->get(array('launcher_id' => $launcher_id, 'language_id' => $language_id));

        if (count($_POST)) {

            /* @todo make sure you can only add new strings with the English Language */

            if (isset($_POST['new_string_identifiers']) && is_array($_POST['new_string_identifiers'])) {
                foreach ($_POST['new_string_identifiers'] as $key => $new_string_identifier) {
                    $new_string_object = $this->model->new_instance();
                    $new_string_object->save(array(
                        'launcher_id' => $launcher_id,
                        'language_id' => $language_id,
                        'string_value' => $_POST['new_strings'][$key],
                        'string_identifier' => $new_string_identifier,
                        'details' => $_POST['new_string_details'][$key],
                        'synced' => 1
                    ));
                    foreach ($this->languages as $language) {
                        if ($language['id'] != $language_id) {
                            //check if string exists
                            $aux = $this->model->new_instance();
                            $data = array(
                                'launcher_id' => $launcher_id,
                                'language_id' => $language['id'],
                                'string_identifier' => $new_string_identifier
                            );
                            $exists = $aux->load_from_params();
                            if ($exists === false) {
                                $data['synced'] = 0;
                                $aux->save($data);
                            }
                        }
                    }
                }
            }
        }
    }

}
