<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('member_area.php');

class dashboard extends member_area {

    public function __construct() {
        parent::__construct();


        $this->load->library('crud');
    }

    public function ajax_back_to_attribution() {
        $theme_id = $this->input->post('theme_id');
        $theme = $this->theme_collection->new_instance($theme_id);
        if ($theme->info['status'] == 'draft') {
            $theme->save(array('status' => 'attribution'));
            $result = array(
                'success' => true
            );
        } else {
            $result = array(
                'success' => false,
                'error' => 'Theme status is not "draft". Cannot give it up!'
            );
        }
        $this->show_ajax($result);
    }

    public function ajax_change_status() {
        $theme_ids = array_map('intval', explode(',', $this->input->post('theme_ids')));
        $this->theme_collection->update_filtered(array(
            'status' => $this->input->post('status')
                ), array(
            'id' => $theme_ids
        ));

        $this->show_ajax(array(
            'success' => true,
        ));
    }

    public function ajax_request_admob($theme_id) {
        $this->theme_collection->save(array(
            'admob_banner_id' => 'pending',
            'admob_interstitial_id' => 'pending',
                ), (int) $theme_id);

        $this->show_ajax(array(
            'success' => true,
        ));
    }

    public function ajax_plusversion() {
        $theme_ids = array_map('intval', explode(',', $this->input->post('theme_ids')));
        $sql = 'UPDATE ' . $this->theme_collection->get_data_table() . ' SET version=version+1 WHERE id IN (' . implode(',', $theme_ids) . ')';
        $this->db->query($sql);
        $this->show_ajax(array('success' => true, 'reload' => false));
    }

    public function rebuild_batch_apk($batch_id, $theme_id) {

        $theme_id = explode(",", $theme_id);

        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        if (in_array($b->info['build_status'], array('completed', 'new', 'pending'))) {

            foreach ($theme_id as $tid) {
                $result = $b->make_theme_project($tid);
            }

            $b->save(array('build_status' => $b->info['build_status'] == 'new' ? 'new' : 'pending'));

            $this->download_batch_theme_collection->update_filtered(array(
                'errors' => null,
                    ), array(
                'theme_id' => $theme_id,
                'batch_id' => $b->id
            ));

            $this->show_ajax($result);
        } else {
            $this->show_ajax(array(
                'success' => false,
                'error' => 'Batch build in progress. Please wait until it is completed'
            ));
        }
    }

    public function uploadScreenShotsOnGooglePlay($theme_id) {
        $this->load->model('theme_collection');

        try {
            $theme = $this->theme_collection->new_instance($theme_id);

            $this->show_ajax(array(
                'success' => $theme->uploadScreenShotsOnGooglePlay()
            ));
        } catch (Exception $ex) {
            $this->show_ajax(array(
                'success' => false,
                'error' => $ex->getMessage()
            ));
        }
    }

    public function uploadTranslationsOnGooglePlay($theme_id, $type_id) {
        $this->load->model('theme_collection');

        try {
            $theme = $this->theme_collection->new_instance($theme_id);

            $this->show_ajax(array(
                'success' => $theme->uploadTranslationsOnGooglePlay($type_id)
            ));
        } catch (Exception $ex) {
            $this->show_ajax(array(
                'success' => false,
                'error' => $ex->getMessage()
            ));
        }
    }

    public function switch_upload_status($batch_id, $theme_id) {

        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $this->show_ajax($b->switch_theme_upload_status($theme_id));
    }

    public function ajax_delete_batch($batch_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        if ($b->info['build_status'] == 'completed' || $b->info['build_status'] == 'new') {
            $this->show_ajax($b->delete());
        } else {
            $this->show_ajax(array(
                'success' => false,
                'error' => 'Batch build in progress. Please wait until it is completed.'
            ));
        }
    }

    public function ajax_prioritize_batch($batch_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $b->save(array(
            'priority' => ++$b->info['priority']
        ));
        $this->show_ajax(array(
            'success' => true,
            'priority' => $b->info['priority']
        ));
    }

    public function ajax_archive_batch($batch_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        if ($b->info['build_status'] == 'completed' || $b->info['build_status'] == 'new') {
            $this->show_ajax($b->archive());
        } else {
            $this->show_ajax(array(
                'success' => false,
                'error' => 'Batch build in progress. Please wait until it is completed.'
            ));
        }
    }

    public function ajax_unarchive_batch($batch_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $result = $b->unarchive();
        $this->show_ajax($result);
    }

    public function ajax_reset_batch($batch_id, $rebuild = false) {
        if (has_access('admin')) {
            $this->load->model('download_batch_collection');
            $b = $this->download_batch_collection->new_instance($batch_id);
            if ($b->info['build_status'] == 'completed' || $b->info['build_status'] == 'new' || $b->info['build_status'] == 'makingprojects') {
                $result = $b->reset($rebuild);
                $this->show_ajax($result);
            } else {
                $this->show_ajax(array(
                    'success' => false,
                    'error' => 'Please wait until batch is completely built'
                ));
            }
        } else {
            $this->show_ajax(array('success' => false, 'error' => 'Access denied'));
        }
    }

    public function ajax_rebuild_batch($batch_id) {
        $this->ajax_reset_batch($batch_id, true);
    }

    public function ajax_remove_theme_from_batch($batch_id, $theme_id) {
        if (has_access('admin')) {
            $this->load->model('download_batch_collection');
            $b = $this->download_batch_collection->new_instance($batch_id);
            if ($b->info['build_status'] == 'completed' || $b->info['build_status'] == 'new') {
                $result = $b->remove_theme($theme_id);
                $this->show_ajax($result);
            } else {
                $this->show_ajax(array(
                    'success' => false,
                    'error' => 'Please wait until batch is completely built'
                ));
            }
        } else {
            $this->show_ajax(array('success' => false, 'error' => 'Access denied'));
        }
    }

    public function ajax_book_theme($batch_id, $theme_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $this->show_ajax($b->book_theme(
                        $theme_id, $this->current_user->get('login.id'), $this->current_user->has_access('admin')
        ));
    }

    public function ajax_unbook_theme($batch_id, $theme_id) {
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $this->show_ajax($b->unbook_theme(
                        $theme_id, $this->current_user->get('login.id'), $this->current_user->has_access('admin')
        ));
    }

    public function ajax_export_batch($exportTask = 'apk') {
        set_time_limit(900);
        error_reporting(E_ALL);
        $theme_ids = explode(',', $this->input->post('theme_ids'));

        if ($this->input->post('version') == 'on') {
            $this->load->model('theme_collection');
            $this->db->query("UPDATE {$this->theme_collection->get_data_table()} SET version = version + 1 WHERE id IN (" . join(",", $theme_ids) . ")");
        }

        $priority = (int) ($this->input->post('priority') == 'on');

        $batches = array_chunk($theme_ids, 50);
        $theme_ids = array();

        $this->load->model('download_batch_collection');

        foreach ($batches as $batch_index => $theme_ids) {
            $download_batch = $this->download_batch_collection->new_instance();
            $result = $download_batch->save(array(
                'description' => $this->input->post('batch_description'), // . ' -- Part #' . ($batch_index + 1),
                'launcher_template_id' => $this->input->post('template_id'),
                'build_status' => 'new',
                'priority' => $priority,
                'export_task' => $exportTask
            ));

            $result['url'] = '/dashboard/get_apk/' . $result['id'];

            $download_batch->set_themes($theme_ids, $this->input->post('trackCode'));

            $download_batch->load_info();
        }

        $this->show_ajax($result);
    }

    public function upload_apk() {
        $theme_id = $this->input->post('theme_id');
        $batch_id = $this->input->post('batch_id');

        $this->load->model('download_batch_collection');
        $this->load->model('theme_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        $theme = $this->theme_collection->new_instance($theme_id);

        $this->globals['force_subfolder'] = 'screenshots';
        $output_dir = $b->get_theme_path($theme_id) . '/output/';

        if (!is_dir($output_dir)) {
            mkdir($output_dir, 0775, true);
        }

        $this->load->library('UploadHandler', array('options' => array(
                'overwrite_upload_dir' => $output_dir
            ), 'initialize' => false, 'error_messages' => null));


        $upload_result = $this->uploadhandler->post(false);
        $final_files = array();

        foreach ($upload_result['files'] as $file) {
            $apk_final_name = $output_dir . $theme->info['package_name'] . '.apk';

            rename($file->path, $apk_final_name);
            $file->path = $apk_final_name;
            $file->url = str_replace(FCPATH, base_url(), $apk_final_name);
            $file->name = array_pop(explode('/', $apk_final_name));
            $final_files[] = $file;
        }

        $this->uploadhandler->generate_response(array('files' => $final_files), true);
        exit;
    }

    public function download_batch($batch_id, $filter = "apk") {
        $batch_id = (int) $batch_id;
        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);
        if ($b->id == $batch_id) {
            $b->download($filter);
        } else {
            die('Batch ID invalid');
        }
    }

    public function get_apk($batch_id = 0) {

        $this->set_js_page_data('user_id', $this->current_user->get('login.id'));

        if ($this->current_user->has_access('get_apk')) {

            $this->load->model('download_batch_collection');
            $this->load->model('launcher_template_collection');
            $this->load->model('launcher_template_version_collection');
            $this->load->model('theme_upload_collection');

            if (!$this->input->cookie('autoarchivebatch')) {
                // auto archive older batch once per day
                $this->download_batch_collection->update_filtered(array(
                    'archived' => '1'
                        ), array(
                    'archived' => '0',
                    'DATE_ADD(date_created, INTERVAL 21 DAY) < NOW()'
                ));

                $this->input->set_cookie('autoarchivebatch', 1, 3600 * 24);
            }

            $launcher_templates = kms_assoc_by_field($this->launcher_template_collection->get());

            $this->bootstrap->frontend();
            $this->assets->add_js('//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js', false);


            if (!intval($batch_id)) {
                switch ($batch_id) {
                    case '0':
                        $batches = $this->download_batch_collection->get(array('archived' => 0, 'test_only' => 0), 'id DESC');
                        break;
                    case 'archive':
                        $batches = $this->download_batch_collection->get(array('archived' => 1, 'test_only' => 0), 'id DESC');
                        break;
                    case 'test_only':
                        $batches = $this->download_batch_collection->get(array('archived' => 0, 'test_only' => 1), 'id DESC');
                        break;
                }

                $download_path = rtrim($this->config->item('download_path'), '/') . '/';
                $download_url = rtrim($this->config->item('base_url'), '/') . '/download/';

                $new_batches = array();

                if (!empty($batches)) {
                    // get upload items all at once
                    $batch_ids = array_map(function($item) {
                        return $item['id'];
                    }, $batches);

                    $uploads = kms_assoc_by_field($this->theme_upload_collection->get(array('download_batch_id' => $batch_ids), null, null, null, array(
                                'group_by' => 'download_batch_id',
                                'fields' => 'download_batch_id, COUNT(*) as uploaded'
                            )), 'download_batch_id');

                    $completed = kms_assoc_by_field($this->download_batch_theme_collection->get(array('batch_id' => $batch_ids), null, null, null, array(
                                'group_by' => 'batch_id',
                                'fields' => "batch_id, 
                            SUM(IF(build_status IN ('completed'), 1, 0)) as ready, 
                            SUM(IF(build_status IN ('running'), 1, 0)) as running,
                            SUM(IF(build_status IN ('errors'), 1, 0)) as errors "
                            )), 'batch_id');

                    foreach ($batches as $batch) {
                        if (!empty($batch['theme_ids'])) {
                            $batch['url'] = base_url() . 'dashboard/get_apk/' . $batch['id'];

                            $theme_ids = $batch['theme_ids'];
                            $batch['theme_count'] = count($theme_ids);

                            $batch['upload_count'] = !empty($uploads[$batch['id']]) ? $uploads[$batch['id']]['uploaded'] : 0;
                            $batch['running_count'] = !empty($completed[$batch['id']]) ? $completed[$batch['id']]['running'] : 0;
                            $batch['errors_count'] = !empty($completed[$batch['id']]) ? $completed[$batch['id']]['errors'] : 0;
                            $batch['complete_count'] = $batch['errors_count'] + !empty($completed[$batch['id']]) ? $completed[$batch['id']]['ready'] : 0;
                            $batch['progress'] = round(100 * $batch['upload_count'] / $batch['theme_count']);
                            $batch['complete_progress'] = round(100 * $batch['complete_count'] / $batch['theme_count']);

                            $batch['template'] = isset($launcher_templates[$batch['launcher_template_id']]) ? $launcher_templates[$batch['launcher_template_id']] : array('name' => '');

                            $new_batches[] = $batch;
                        }
                    }
                }

                $this->set_template_var('batches', $new_batches);
            } else {
                $batch = $this->download_batch_collection->new_instance((int) $batch_id);

                $batch->info['theme_count'] = count($batch->info['theme_ids']);
                $batch->info['template'] = isset($launcher_templates[$batch->info['launcher_template_id']]) ? $launcher_templates[$batch->info['launcher_template_id']] : array('name' => '');
                $batch->info['download_url'] = base_url() . 'dashboard/download_batch/' . $batch->id;

                $path = $this->download_batch_collection->get_path($batch->id);

                $batch->info['template']['version'] = $this->launcher_template_version_collection->get_one(array('id' => $batch->info['launcher_version']));
                $themes = $batch->get_themes();

                // get grab screenshot jobs
                $theme_ids = array_keys(kms_assoc_by_field($themes));

                $this->load->model('theme_test_job_collection');
                $this->load->model('autoplay_job_collection');
                $this->load->model('autoplay_test_collection');
                $this->load->model('theme_test_collection');

                $pending_theme_jobs = $this->autoplay_job_collection->get_list(array(
                    'tj.batch_id = ' . $batch_id,
                    't.theme_id IN(' . join(",", $theme_ids) . ') ',
                    'at.googleplay_screenshots = 1',
                    't.screenshots_only = 1',
                    'a.status < 2'
                        ), null, null, null, array(
                    'sql_join' => "
                        INNER JOIN {$this->autoplay_test_collection->get_data_table()} at ON at.id = a.test_id
                        INNER JOIN {$this->theme_test_job_collection->get_data_table()} tj ON tj.job_id = a.id
                        INNER JOIN {$this->theme_test_collection->get_data_table()} t ON t.id = tj.test_id
                    ",
                    'fields' => "t.theme_id"
                ));

                foreach ($themes as &$theme) {
                    $theme['grab_in_progress'] = in_array($theme['id'], $pending_theme_jobs);

                    $tmp_apk = str_replace(".apk", "-unsigned.apk", kmsUrlToPath($theme['apk']));
//                    if (file_exists($tmp_apk)) {
                    $theme['unsignedapk'] = kmsPathToUrl($tmp_apk);
//                    }
                }

                unset($theme);

                $this->set_template_var('batch', $batch->info);
                $this->set_template_var('themes', $themes);

                $this->bootstrap->setup_fileupload();
                $this->assets->add_js('app/js/themeupload.js');
            }

            $this->assets->add_css('css/get-apk.css');
            $this->assets->add_js('js/get-apk.js');

            $this->set_template('web/get_apks.tpl');
            $this->show_page('get_apk');
        } else {
            redirect('/dashboardv2');
        }
    }

    public function ajax_test_push_feature($batch_id, $theme_id) {

        $device_id = $this->input->get('device_id');
        $registration_id = $this->input->get('registration_id');

        if ($device_id) {
            $this->load->model('test_device_collection');
            $device = $this->test_device_collection->get_one(array('id' => $device_id));

            if (empty($device)) {
                throw new AjaxException("Unknown device");
            }

            $registration_id = $device['registration_id'];
        }

        if (!$registration_id) {
            throw new AjaxException("Unknown registration_id");
        }

        $this->load->model('developer_account_collection');
        $this->load->model('theme_collection');
        $this->load->model('download_batch_collection');
        $this->load->model('download_batch_theme_collection');
        $this->load->model('launcher_template_version_collection');


        $data = $this->download_batch_collection->get_one(array('a.id = ' . (int) $batch_id, 't.id = ' . (int) $theme_id), null, null, null, array(
            'sql_join' => "
                INNER JOIN {$this->download_batch_theme_collection->get_data_table()} db ON db.batch_id = a.id
                INNER JOIN {$this->theme_collection->get_data_table()} t ON db.theme_id = t.id
                INNER JOIN {$this->developer_account_collection->get_data_table()} d ON d.id = t.developer_account_id
                INNER JOIN {$this->launcher_template_version_collection->get_data_table()} lv ON lv.id = a.launcher_version
            ",
            "fields" => "a.id, t.developer_account_id, d.id as developer_account, d.gcm_api_key, lv.version as template_version"
        ));

        if (empty($data)) {
            throw new AjaxException("Unknown batch / theme pair!");
        }

        $this->load->library('push');

        $this->push->set_gcm_api_key($data['gcm_api_key']);

        $push = array(
            "type" => "update-tversion",
            "tversion" => $data['template_version'],
            "title" => "This is a push test title",
            "content" => "Custom description for your push test title!",
            "vibrate" => 1,
            "sound" => 1
        );

        $push['registration_ids'] = array($registration_id);
        $reply = $this->push->send($push);


        var_dump($reply);
        die;
    }

    public function index() {
        $this->assets->add_css('css/dashboard.css', false);
        $this->set_template('web/dashboard/index.tpl');
    }

    public function trasee() {
        $this->assets->add_css('css/dashboard.css', false);
        $this->set_template('web/dashboard/index.tpl');
    }

    public function prieteni() {
        $this->assets->add_css('css/dashboard.css', false);
        $this->set_template('web/dashboard/prieteni.tpl');
    }

    public function profil() {
        $this->assets->add_css('css/dashboard.css', false);
        $this->set_template('web/dashboard/profil.tpl');
    }

    public function ajax_autoupload($type) {
        set_time_limit(900);
        error_reporting(E_ALL);

        try {

            $this->load->model('launcher_template_collection');
            $template = $this->launcher_template_collection->new_instance($this->input->post('template_id'));

            if (!$template->tests_passed()) {
                throw new Exception("Launcher template must first be tested before you can use it to auto upload!");
            }

            $autobot = $this->user_collection->getAutoBot();

            $theme_ids = array_map(function($item) {
                return (int) $item;
            }, explode(',', $this->input->post('theme_ids')));

            # auto increase version
            $this->load->model('theme_collection');
            $this->db->query("UPDATE {$this->theme_collection->get_data_table()} SET version = version + 1 WHERE id IN (" . join(",", $theme_ids) . ")");

            $this->load->model('download_batch_collection');

            $changes = $this->input->post('batch_description');
            $batch_ids = array();

            $part = 0;

            do {

                $download_batch = $this->download_batch_collection->new_instance();

                $batch_themes = array_slice($theme_ids, 0, 50);
                $theme_ids = array_slice($theme_ids, 50);

                $part ++;

                $download_batch->save(array(
                    'description' => $changes . " #{$part}",
                    'recent_changes' => $changes,
                    'priority' => 1,
                    'launcher_template_id' => $this->input->post('template_id'),
                    'build_status' => 'new',
                    'export_task' => 'apk'
                ));

                $download_batch->set_themes($batch_themes);

                $download_batch->load_info();
                $download_batch->book_theme_by_autobot();

                $batch_ids[] = $download_batch->id;
            } while (!empty($theme_ids));

            // only redirect to first batch
            $this->show_ajax(array(
                'success' => true,
                'url' => '/dashboard/auto_upload_progress/' . join(",", $batch_ids)
            ));
        } catch (Exception $ex) {
            $this->show_ajax(array(
                'success' => false,
                'error' => $ex->getMessage()
            ));
        }
    }

    public function ajax_get_upload_status($batch_id) {
        $this->load->model('download_batch_collection');
        $batch_ids = explode(",", $batch_id);

        $this->show_ajax($this->download_batch_collection->auto_upload_progress($batch_ids));
    }

    public function auto_upload_progress($batch_id) {
        $this->load->model('download_batch_collection');
        $this->load->model('launcher_template_collection');

        $batch_ids = explode(",", $batch_id);

        $status = $this->download_batch_collection->auto_upload_progress($batch_ids);

        $batch = $this->download_batch_collection->get_one(array('id' => current($batch_ids)));

        $launcher = $this->launcher_template_collection->get_one(array('id' => $batch['launcher_template_id']));

        $this->set_template_var('batch', $batch);
        $this->set_template_var('batch_ids', $batch_id);
        $this->set_template_var('launcher', $launcher);
        $this->set_template_var('uploadStatus', $status);

        $this->bootstrap->frontend();
        $this->assets->add_js('app/js/auto_upload_progress.js', false);

        $this->set_template('web/auto_upload_progress.tpl');
        $this->show_page('dashboard');
    }

    public function upload_csv() {
        force_admin('');

        $this->load->model('csvtype_collection');
        $csvtypes = $this->csvtype_collection->get();
        $this->set_template_var('csvtypes', $csvtypes);

        if ($this->input->post('csvtype')) {

            $not_found = array();

            foreach ($csvtypes as $csvtype) {
                if ($csvtype['id'] == $this->input->post('csvtype'))
                    $selected_csv_type = $csvtype;
            }

            $filename = $this->config->item('reports_path') . '/' . $selected_csv_type['id'] . '-' . sha1(time()) . '.csv';
            $ok = move_uploaded_file($_FILES['csv']['tmp_name'], $filename);
            if ($ok) {

                $h = fopen($filename, 'r');
                $firstline = fgets($h);
                $delimiter = ',';
                if (substr_count($firstline, ';') > substr_count($firstline, ','))
                    $delimiter = ';';
                if (false !== $h) {
                    while (false !== ($line = fgetcsv($h, 4096, $delimiter))) {

                        /* APP ID */
                        $app_id = $line[$selected_csv_type['app_id_col_index']];
                        if (!is_numeric($app_id)) {
                            $app_id = substr($app_id, 0, strpos($app_id, ' '));
                            $app_id = trim($app_id);
                            $app_id = preg_replace('/[^0-9]+/', '', $app_id);
                        }

                        /* REVENUE */
                        $revenue = preg_replace('/[^0-9,\.]+/', '', $line[$selected_csv_type['revenue_col_index']]);
                        $dot = strpos($revenue, '.');
                        $coma = strpos($revenue, ',');
                        if ($dot === false) {
                            $revenue = str_replace(',', '.', $revenue);
                        } else {
                            if ($coma !== false) {
                                if ($dot < $coma) {
                                    $revenue = str_replace('.', '', $revenue);
                                    $revenue = str_replace(',', '.', $revenue);
                                } else {
                                    $revenue = str_replace(',', '', $revenue);
                                }
                            }
                        }
                        $revenue = floatval($revenue);

                        /* APP ID TYPE */
                        $app_id_type = $selected_csv_type['app_id_type'];

                        /* DATE FORMAT */
                        $date_parts['year'] = strpos($selected_csv_type['date_format'], 'Y');
                        $date_parts['month'] = strpos($selected_csv_type['date_format'], 'm');
                        $date_parts['day'] = strpos($selected_csv_type['date_format'], 'd');
                        asort($date_parts);

                        /* START DATE */
                        $start_date = $line[$selected_csv_type['date_start_col_index']];
                        $start_date_parts = preg_split('/[^0-9]+/', $start_date);
                        $start_time_elements = array();
                        foreach ($date_parts as $part_type => $aux) {
                            $start_time_elements[$part_type] = intval(array_shift($start_date_parts));
                        }
                        $start_date = date('Y-m-d', mktime(0, 0, 0, $start_time_elements['month'], $start_time_elements['day'], $start_time_elements['year']));

                        /* END DATE */
                        $end_date = $line[$selected_csv_type['date_end_col_index']];
                        $end_date_parts = preg_split('/[^0-9]+/', $end_date);
                        $end_time_elements = array();
                        foreach ($date_parts as $part_type => $aux) {
                            $end_time_elements[$part_type] = intval(array_shift($end_date_parts));
                        }
                        $end_date = date('Y-m-d', mktime(0, 0, 0, $end_time_elements['month'], $end_time_elements['day'], $end_time_elements['year']));

                        $theme = $this->theme_collection->get_one(array($app_id_type => $app_id));

                        if (is_array($theme)) {
                            /* DELETE PREVIOUS DATA */
                            $this->daily_theme_stats_collection->delete_multiple(array(
                                'theme_id' => $theme['id'],
                                'report_date BETWEEN "' . $start_date . '" AND "' . $end_date . '"',
                                'csvtype_id' => $selected_csv_type['id']
                            ));

                            /* ADD NEW DATA */
                            $data = array(
                                'theme_id' => $theme['id'],
                                'report_date' => $end_date,
                                'revenue' => $revenue,
                                'csvtype_id' => $selected_csv_type['id']
                            );

                            $stats_entry = $this->daily_theme_stats_collection->new_instance();
                            $stats_entry->save($data);
                        } else {
                            $not_found[$app_id] = 1;
                        }
                        $this->set_template_var('not_found', array_keys($not_found));
                    }
                }
                $this->theme_stats_collection->rebuild_stats();
            } else {
                die('File upload failed.');
            }
        }

        $this->bootstrap->frontend();
        $this->set_template('web/upload_csv.tpl');
        $this->show_page('upload_csv');
    }

    public function list_users($query) {
        if ($this->current_user->is_admin()) {
            $this->load->model('user_collection');
            $users = $this->user_collection->get(array('username LIKE ' . $this->db->escape($query . '%') . ' OR email LIKE ' . $this->db->escape($query . '%') . ' '));

            $result = array();
            foreach ($users as $user) {
                $result[] = array(
                    'value' => $user['username'],
                    'tokens' => array($user['username'], (string) $user['email'])
                );
            }
            $this->show_ajax($result);
        }
    }

    public function ajax_send_apk_to_device($theme_id, $batch_id = 0) {

        $batch_id = (int) $batch_id;
        $theme_id = (int) $theme_id;
        $device_id = (int) $this->input->post('test_device_id');

        $this->load->model('download_batch_collection');
        $b = $this->download_batch_collection->new_instance($batch_id);

        if ($b->id > 0) {
            $this->show_ajax($b->send_to_device($theme_id, $device_id));
        } else {

            $theme = $this->theme_collection->new_instance($theme_id);

            $this->load->model('launcher_template_collection');
            $launcher_template = $this->launcher_template_collection->get_one(array(
                'folder' => $this->launchers[$theme->info['launcher_id']]['identifier']
                    ), 'is_default DESC');

            $this->load->model('developer_account_collection');
            $dev_account = $this->developer_account_collection->get_one();

            $updates = array();
            if ($theme->info['package_name'] == '' || $theme->info['admob_interstitial_id'] == '' || $theme->info['admob_banner_id'] == '' || $theme->info['version'] == 0 || $theme->info['developer_account_id'] == 0) {
                $updates['package_name'] = $theme->info['package_name'] != '' ? $theme->info['package_name'] : str_replace("%theme%", preg_replace('/[^a-z_]+/i', '', $theme->info['name']), $launcher_template['default_package_name']);
                $updates['admob_interstitial_id'] = $theme->info['admob_interstitial_id'] != '' ? $theme->info['admob_interstitial_id'] : 'interstitial';
                $updates['admob_banner_id'] = $theme->info['admob_banner_id'] != '' ? $theme->info['admob_banner_id'] : 'banner';
                $updates['version'] = $theme->info['version'] > 0 ? $theme->info['version'] : 1;
                $updates['developer_account_id'] = $theme->info['developer_account_id'] > 0 ? $theme->info['developer_account_id'] : $dev_account['id'];

                $theme->save($updates);
                $theme->load_info();
            }

            $result = $b->save(array(
                'description' => 'Test theme #' . $theme_id,
                'launcher_template_id' => $launcher_template['id'],
                'build_status' => 'new',
                'test_only' => 1,
                'test_device_id' => $device_id
            ));

            $b->set_themes(array($theme_id));

            $b->load_info();
            //$result = $b -> make_projects();
            //$b -> save(array('build_status'=>'pending'));
            $this->show_ajax($result);
        }
    }

    public function ajax_flag_theme_for_update($theme_id) {
        $theme = $this->theme_collection->new_instance((int) $theme_id);
        $flag = $this->input->post('flag');
        $theme->save(array(
            $flag => date("Y-m-d H:i:s")
        ));
        $this->show_ajax(array('success' => true, 'flag' => $flag, 'value' => date("Y-m-d H:i:s")));
    }

    public function ajax_theme_version_increase($theme_id) {
        $theme = $this->theme_collection->new_instance((int) $theme_id);
        $theme->save(array(
            'version' => ($new_version = $theme->info['version'] + 1)
        ));
        $this->show_ajax(array('success' => true, 'version' => $new_version));
    }

    public function ajax_theme_version_decrease($theme_id) {
        $theme = $this->theme_collection->new_instance((int) $theme_id);
        $theme->save(array(
            'version' => ($new_version = $theme->info['version'] - 1)
        ));
        $this->show_ajax(array('success' => true, 'version' => $new_version));
    }

    public function ajax_get_batch_theme_error_log($batch_id, $theme_id) {
        $this->load->model('download_batch_collection');

        $b = $this->download_batch_collection->new_instance($batch_id);
        if ($b->id == $batch_id) {
            $error_log_txt = $b->get_error_log($theme_id);
            $lines = explode("\n", @file_get_contents($error_log_txt));

            if (count($lines) <= 3) {
                $this->show_ajax(array(
                    'lines' => $lines
                ));
            } else {
                $this->show_ajax(array(
                    'file' => $error_log_txt
                ));
            }
        }
    }

}
