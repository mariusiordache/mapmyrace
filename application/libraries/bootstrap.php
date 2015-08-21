<?php

class bootstrap {

    protected $_CI;

    public function __construct() {

        setlocale(LC_ALL, 'en_US');

        $this->_CI = get_instance();

        /* load helpers */
        $this->_CI->load->helper('kms_language');
        $this->_CI->load->helper('kms_array');
        $this->_CI->load->helper('kms_folder');
        $this->_CI->load->helper('kms_date');
        $this->_CI->load->helper('chrome_php');
        $this->_CI->load->helper('url_helper');
        $this->_CI->load->helper('generic_helper');
        /* load current user */
        $this->_CI->load->library('current_user');

       
        $this->_CI->current_user->set_if_empty('settings.language', $this->_CI->config->item('language'));
        $this->_CI->lang->load('user_interface', $this->_CI->current_user->get('settings.language'));

        setlocale(LC_TIME, kms_lang('locale'));


        $this->_CI->set_js_page_data('js_date_format', kms_lang('js_date_format'));
        $this->_CI->set_js_page_data('strftime_date_format', kms_lang('strftime_date_format'));

        $this->_CI->set_js_page_data('dayNames', kms_lang('dayNames'));
        $this->_CI->set_js_page_data('shortDayNames', kms_lang('dayNames'));
        $this->_CI->set_js_page_data('monthNames', kms_lang('monthNames'));
        $this->_CI->set_js_page_data('shortMonthNames', kms_lang('shortMonthNames'));

        $this->_CI->user_settings = $this->get_user_settings();

        $this->_CI->set_js_page_data('user_settings', $this->_CI->user_settings);


        if ($this->_CI->session->userdata('referrer') === false || $this->_CI->session->userdata('referrer') === '')
            $this->_CI->session->set_userdata('referrer', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

    }

    public function frontend() {

        $this->_CI->assets->add_js('js/loadingButton.js');
        $this->_CI->assets->add_js('js/common.js');
        
        $this->_CI->assets->addDependencies(array('jqueryui'));
        
        $this->_CI->set_js_page_data('date_format', 'D.M.YYYY, h:mm:ss');
    }

    public function setup_fileupload() {

        $this->_CI->assets->addDependencies(array(
            'jqueryui',
            'tmpl',
            'load-image',
            'jquery.fileupload',
            'jquery.fileupload-process',
            'jquery.fileupload-image',
            'jquery.fileupload-validate',
            'jquery.fileupload-ui',
        ));
    }

    public function get_user_settings() {

        if ($this->_CI->input->get('s') !== false && $this->_CI->input->get('s') != '') {
            $user_settings = $this->parse_user_settings_from_uri($this->_CI->input->get('s'));
        } else {
            $user_settings = $this->get_user_settings_from_cookie();
            if ($user_settings === false) {
                $user_settings = $this->get_default_user_settings();
            }
        }

        return $user_settings;
    }

    public function get_default_user_settings() {

        return array();
    }

    public function get_user_settings_from_cookie() {
        return $this->_CI->current_user->get('searchsettings');
    }

    public function parse_user_settings_from_uri($uri) {

        $settings = array();
        $arguments = array();
        $raw_arguments = explode('~', $uri);
        foreach ($raw_arguments as $a) {
            list($key, $value) = explode(':', $a);
            $arguments[$key] = $value;
        }
        unset($a);
        foreach ($arguments as $key => $value) {
            switch ($key) {
                case 'a':
                    $new_key = 'adults';
                    $new_value = (int) $value;
                    break;
                case 'ar':
                    $new_key = 'arrangements';
                    $new_value = array();
                    $raw_arrangements = explode('|', $value);
                    foreach ($raw_arrangements as $raw_arrangement) {
                        if ($raw_arrangement != '') {
                            $raw_rooms = explode(';', $raw_arrangement);
                            $rooms = array();
                            if ($raw_rooms != '') {
                                foreach ($raw_rooms as $raw_room) {
                                    list($adults, $kids) = explode(',', $raw_room);
                                    $rooms[] = array('selectedKids' => $kids, 'selectedAdults' => $adults);
                                }
                            }
                            $new_value[] = array('rooms' => $rooms);
                        }
                    }
                    break;
                case 'k':
                    $new_key = 'kids';
                    $new_value = array();
                    $raw_kids = explode('|', $value);
                    foreach ($raw_kids as $raw_kid) {
                        if ($raw_kid != '') {
                            list($ident, $birth_date) = explode(',', $raw_kid);
                            $new_value[] = array('ident' => $ident, 'birth_date' => $birth_date);
                        }
                    }
                    break;
                case 'c':
                    $new_key = 'currency';
                    $new_value = $value;
                    break;
                case 'd':
                    $new_key = 'departure';
                    list($tag_id, $label, $full_label) = explode('|', $value);
                    $new_value = array(
                        'tag_id' => $tag_id,
                        'label' => $label,
                        'full_label' => $full_label
                    );
                    break;
            }
            $settings[$new_key] = $new_value;
            if ($new_key == 'kids')
                $settings['kids_count'] = count($new_value);
        }

        return $settings;
    }

}
