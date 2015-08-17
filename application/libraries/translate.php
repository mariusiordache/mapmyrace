<?php

class translate {

    private $_CI;
    private $language_codes = array();
    private $translation_base_url = '';
    private $language_primary_id = null;

    public function __construct() {
        $this->_CI = get_instance();
        $this->_CI->load->model('template_string_collection');
        $this->_CI->load->model('language_collection');
    }

    public function getGoogleTranslateBaseUrl() {
        if (empty($this->translation_base_url)) {

            $this->_CI->config->load('google');
            $api_key = $this->_CI->config->item('translate_api_key', 'google');
            $this->translation_base_url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&source=%source%&format=html&q=%query%&target=%target%';
        }

        return $this->translation_base_url;
    }
    
    public function import_from_xml($launcher_id, $folder, $xml_path) {
        
        $primary_language_strings = $this->_CI->template_string_collection->get_list(array(
            'language_id' => $this->getPrimaryLanguageId(),
            'launcher_id' => $launcher_id,
            'folder' => $folder
        ), null, null, null, array(
            'fields' => 'string_identifier'
        ));
        
        if (file_exists($xml_path)) {

            $xml_content = file_get_contents($xml_path);
            preg_match_all('@<string name="([^"]+)">(.*)</string>@', $xml_content, $strings);
            $new_strings = array();
            
            foreach($strings[1] as $i => $identifier) {
                $value = $strings[2][$i];
                
                $value = htmlentities($value, ENT_QUOTES);
                $value = addslashes(strip_slashes($value));
                $value = str_replace(array('/', '\\'), '', $value);
                
                $value = str_replace('&amp;', '&', $value);
                $new_strings[$identifier] = $value;
                
            }
            
            
            $to_add_keys = array_diff(array_keys($new_strings), $primary_language_strings);



            if (!empty($to_add_keys)) {
                $to_insert = array();

                $primary_language_id = $this->getPrimaryLanguageId();

                // search for strings that contains variables
                $existing_with_variables = kms_array_to_html_options($this->_CI->template_string_collection->get(array(
                    "`string_value` LIKE  '%<%>%'",
                    'language_id' => $primary_language_id,
                        ), null, null, null, array(
                    'group_by' => 'string_identifier',
                    'fields' => 'string_identifier, string_value'
                )), 'string_identifier', 'string_value');


                foreach($to_add_keys as $key) {
                    $v = !empty($existing_with_variables[$k]) ? $existing_with_variables[$k] : $new_strings[$key];
                    
                    $to_insert[] = array(
                        'launcher_id' => $launcher_id,
                        'language_id' => $primary_language_id,
                        'string_value' => $v,
                        'string_identifier' => $key,
                        'do_not_translate' => '1',
                        'folder' => $folder
                    );
                }
                
                $this->_CI->template_string_collection->add_multiple($to_insert);
                $this->push_to_xml($launcher_id, $folder);
            }
        }
    }

    public function push_to_xml($launcher_id, $folder) {

        $launcher_identifier = $this->_CI->launchers[$launcher_id]['identifier'];

        $launchers_path = $this->_CI->config->item('launcher_config_path');
        $template_path = $launcher_identifier . '/' . $folder . '/res';

        $folder_paths = array(
            $launchers_path . "/" . $template_path,
            $launchers_path . "/beta/" . $template_path,
            $launchers_path . "/alpha/" . $template_path,
        );

        //header("Content-type: text/plain;charset=utf-8");
        $languages = $this->_CI->language_collection->get();
        foreach ($languages as $language) {
            
            $language['locale'] = !empty($language['locale']) ? $language['locale'] : $language['iso_code'];
            
            $xml_addon = '';
            $strings = $this->_CI->template_string_collection->get(array('launcher_id' => $launcher_id, 'folder' => $folder, 'language_id' => $language['id']));
            foreach ($strings as $string) {
                if (strlen($string['string_value']) > 0) {
                    $value = html_entity_decode($string['string_value'], ENT_QUOTES);
                    $value = addslashes(strip_slashes($value));
                    $value = str_replace('&', '&amp;', $value);
                    $value = preg_replace('@^(\?)@', '\\?', $value);

                    $xml_addon .= "\n\t" . '<string name="' . $string['string_identifier'] . '">' . $value . '</string>';
                }
            }
            if (strlen($xml_addon) > 0) {
                $xml = '<?xml version="1.0" encoding="utf-8" standalone="no"?>' . "\n" . '<resources>';
                $xml .= $xml_addon;
                $xml .= "\n" . '</resources>';

                foreach ($folder_paths as $folder_path) {
                    $file_location = $folder_path . '/values' . ( $language['is_primary'] ? '' : '-' . $language['locale'] );

                    if (!is_dir($file_location)) {
                        mkdir($file_location, 0755, true);
                    }

                    $file = $file_location . '/strings.xml';
                    file_put_contents($file, $xml);
                    @chmod($file, 0664);
                }
            }
        }
    }

    public function string($string, $language_code, $source_language = 'en') {
        if ($source_language == $language_code) {
            return $string;
        }

        $this->_CI->config->load('google');
        $api_key = $this->_CI->config->item('translate_api_key', 'google');
        $translate_url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&source=' . $source_language . '&format=html&q=' . urlencode($string) . '&target=' . $language_code;
        $cache = $this->_CI->config->item('webroot_path') . '/tmp/tgs_' . md5($translate_url);

        if (file_exists($cache)) {
            return file_get_contents($cache);
        }

        $content = file_get_contents($translate_url);

        $result = json_decode($content, true);
        if (!empty($result['data']['translations'])) {
            file_put_contents($cache, $result['data']['translations'][0]['translatedText']);

            return $result['data']['translations'][0]['translatedText'];
        }

        return false;
    }

    protected function getLanguages() {
        if (empty($this->language_codes)) {
            $languages = $this->_CI->language_collection->get();
            $this->language_codes = kms_assoc_by_field($languages);
            foreach ($this->language_codes as $lang_id => $lang) {
                if ($lang['is_primary']) {
                    $this->language_primary_id = $lang_id;
                }
            }
        }

        return $this->language_codes;
    }

    protected function getPrimaryLanguageId() {
        if (empty($this->language_primary_id)) {
            $this->getLanguages();
        }

        return $this->language_primary_id;
    }

    public function google_translate_blanks($launcher_id, $folder) {

        if (!is_numeric($launcher_id) || $launcher_id == 0 || !preg_match('/[a-z_]+/', $folder)) {
            return array('success' => false, 'folder' => $folder, 'launcher_id' => $launcher_id);
        }

        $primary_language_strings = $this->_CI->template_string_collection->get(array(
            'language_id' => $this->getPrimaryLanguageId(),
            'launcher_id' => $launcher_id,
            'folder' => $folder
        ));


        $empty_strings = $this->_CI->template_string_collection->get(array('launcher_id' => $launcher_id, 'folder' => $folder, 'string_value=""'));

        foreach ($empty_strings as $string) {
            /* identifty corresponding string in the primary language */
            $source_string = '';
            foreach ($primary_language_strings as $primary_string) {
                if ($primary_string['launcher_id'] == $string['launcher_id'] && $primary_string['string_identifier'] == $string['string_identifier']) {
                    $source_string = $primary_string['string_value'];
                }
            }

            $translated = $this->_CI->template_string_collection->get_one(array(
                "a.string_value = '" . $this->_CI->db->escape_str($source_string) . "'",
                "a.language_id = {$this->getPrimaryLanguageId()}",
                "a.launcher_id <> {$launcher_id}"
                    ), null, null, null, array(
                'sql_join' => "INNER JOIN {$this->_CI->template_string_collection->get_data_table()} b ON 
                        b.launcher_id = a.launcher_id AND 
                        b.language_id = {$string['language_id']} AND 
                        b.string_identifier = a.string_identifier AND
                        b.string_value <> ''",
                'fields' => 'b.string_value'
                    )
            );

            if (!empty($translated['string_value'])) {
                $translated_string = $translated['string_value'];
            } else {
                $translated_string = $this->translate_string($source_string, $string['language_id']);
            }


            $this->_CI->template_string_collection->update_filtered(
                    array('string_value' => $translated_string), array('id' => $string['id'])
            );
        }

        return array('success' => true, 'folder' => $folder, 'launcher_id' => $launcher_id);
    }

    protected function language_id_to_iso($language) {
        if (is_numeric($language)) {
            $languages = $this->getLanguages();
            if (isset($languages[$language])) {
                return $languages[$language]['iso_code'];
            }
        }

        return $language;
    }

    public function translate_string($source_string, $to_language, $from_language = null) {

        if (is_null($from_language)) {
            $from_language = $this->getPrimaryLanguageId();
        }

        $translate_url = $this->getGoogleTranslateBaseUrl();
        $from_language = $this->language_id_to_iso($from_language);
        $to_language = $this->language_id_to_iso($to_language);
        $url = str_replace(array('%query%', '%target%', '%source%'), array(urlencode($source_string), $to_language, $from_language), $translate_url);

        $result = @json_decode(file_get_contents($url), true);
        $translated_string = $result['data']['translations'][0]['translatedText'];

        return $translated_string;
    }

}
