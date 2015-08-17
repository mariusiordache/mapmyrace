<?php

require_once 'base_api.php';

class api extends base_api {

    protected $debug = false;

    public function __construct() {
        parent::__construct();

        $this->load->helper('kms_array');
        $this->launchers = kms_assoc_by_field($this->doWithCollection('launcher_collection', 'get'));
        $this->colors = kms_assoc_by_field($this->doWithCollection('theme_meta_collection', 'get', array(array('type' => 'color'), null, null, null, array('fields' => 'id,name'))));
        $this->styles = kms_assoc_by_field($this->doWithCollection('theme_meta_collection', 'get', array(array('type' => 'style'), null, null, null, array('fields' => 'id,name'))));
        $this->languages = kms_assoc_by_field($this->doWithCollection('language_collection', 'get'), 'iso_code');
    }

    protected function enableDebugMode() {
        $this->debug = true;
    }

    protected function isDebug() {
        return $this->debug === true;
    }

    protected function getCache() {
        if (!isset($this->cache)) {
            $this->load->driver('cache', array('adapter' => 'file'));
        }

        return $this->cache;
    }

    protected function doWithCollection($collection, $method, $params = array()) {
        $collection_name = $collection;

        if (is_object($collection) && $collection instanceOf kms_item_collection) {
            $collection_name = get_class($collection);
        }

        $cache_key = "mysql_" . md5(json_encode(array('collection' => $collection_name, 'method' => $method, 'params' => $params)));

        $cache = $this->getCache()->get($cache_key);

        if ($cache !== false) {
            return $cache;
        }

        if (!isset($this->db)) {
            $this->load->database();
        }

        if (is_string($collection)) {
            $this->load->model($collection);
            $collection = $this->$collection;
        }

        $resp = call_user_func_array(array($collection, $method), $params);

        $this->getCache()->save($cache_key, $resp, 300);

        return $resp;
    }

    //primary endpoints
    // ENDPOINT
    public function themeuser() {
        $this->primary_endpoint = 'themeuser';
        $this->load->model('theme_user_device_collection', 'device_collection');
        $this->set_args(func_get_args());
        try {
            echo $this->processAPI();
        } catch (Exception $e) {
            echo json_encode(Array('error' => $e->getMessage()));
        }
        exit;
    }

    // ENDPOINT
    public function themetester() {
        $this->primary_endpoint = 'themetester';
        $this->load->model('test_device_collection', 'device_collection');
        $this->set_args(func_get_args());

        try {
            echo $this->processAPI();
        } catch (Exception $e) {
            echo json_encode(Array('error' => $e->getMessage()));
        }
        exit;
    }

    // ENDPOINT
    public function mobile() {
        $this->primary_endpoint = 'mobile';
        $this->load->model('m_device_collection', 'device_collection');
        $this->set_args(func_get_args());
        try {
            echo $this->processAPI();
        } catch (Exception $e) {
            echo json_encode(Array('error' => $e->getMessage()));
        }
        exit;
    }

    //api functions

    public function get_settings() {
        $theme_id = isset($this->request['theme_id']) ? (int) $this->request['theme_id'] : 0;

        $language_code = isset($this->request['lang']) ? $this->request['lang'] : 'en';
        $language = array_key_exists($language_code, $this->languages) ? $this->languages[$language_code] : $this->languages['en'];

        $country_code = isset($this->request['c']) ? $this->request['c'] : '';
        //$country = array_key_exists($country_code, $this -> countries) ? $this -> countries[$country_code] : null;

        $download_batch_id = isset($this->request['b']) ? $this->request['b'] : 0;

        if ($theme_id == 0) {
            //	throw new Exception('Invalid theme ID (provided value: "'.$theme_id.'")');
        }

        return array(
            'othersettings' => array(
                'interstitialOnCreate' => 'mobilecore', // admob|mobilecore|custom
                'interstitialOrder' => array('mobilecore', 'admob'),
                'interstitialOnBack' => true
            ),
            'buttons' => array(
                array(
                    'id' => 'santaclaus',
                    'label' => 'Victor',
                    'url' => 'market://details?id=com.google.android.googlequicksearchbox'
                )
            ),
            'banner' => array(
                'id' => 'bannerx',
                'src' => 'http://androidmakeup.com/assets/img/largebanner.jpg',
                'url' => 'http://victorstuff.com'
            ),
            'newWallpapers' => array(
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_0.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_1.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_2.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_3.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_4.jpg"
            ),
            'customInterstitial' => array(
                'id' => 'bannerx',
                'src' => 'http://androidmakeup.com/assets/img/largebanner.jpg',
                'url' => 'http://victorstuff.com'
            )
        );
    }

    public function get_updates() {
        $theme_id = isset($this->request['theme_id']) ? (int) $this->request['theme_id'] : 0;
        if ($theme_id == 0) {
            //throw new Exception('Invalid theme ID (provided value: "'.$theme_id.'")');
        }

        return array(
            'othersettings' => array(
                'interstitialOnCreate' => 'mobilecore', // admob|mobilecore|custom
                'interstitialOrder' => array('mobilecore', 'admob'),
                'interstitialOnBack' => true
            ),
            'buttons' => array(
                array(
                    'id' => 'santaclaus',
                    'label' => 'Victor',
                    'url' => 'market://details?id=com.google.android.googlequicksearchbox'
                )
            ),
            'banner' => array(
                'id' => 'bannerx',
                'src' => 'http://androidmakeup.com/assets/img/largebanner.jpg',
                'url' => 'http://victorstuff.com'
            ),
            'newWallpapers' => array(
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_0.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_1.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_2.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_3.jpg",
                "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_4.jpg"
            ),
            'customInterstitial' => array(
                'id' => 'bannerx',
                'src' => 'http://androidmakeup.com/assets/img/largebanner.jpg',
                'url' => 'http://victorstuff.com'
            )
        );

        /*
          return array(
          'banner_img_url'       => 'http://androidmakeup.com/assets/temp/banner.jpg',
          'banner_link'          => 'http://google.com',
          'interstitial_img_url' => 'http://timmystudios.com/assets/store_theme_photos/3/1/4/31426/31426_main.jpg',
          'interstitial_link'    => 'http://google.com',
          'interstitial' => 'custom',
          'new_wallpapers' => array(
          "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_0.jpg",
          "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_1.jpg",
          "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_2.jpg",
          "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_3.jpg",
          "http://timmystudios.com/themes/0/0/0/40/homescreen/wallpaper_4.jpg"
          ),
          'buttons' => array(
          array('id'=>'santaclaus', 'label'=>'Santa claus', 'url'=>'market://details?id=com.google.android.googlequicksearchbox'),
          array('id'=>'grinch', 'label'=>'The Grinch', 'url'=>'http://www.google.com/?q=grinch')
          )
          );
         */
    }
    
    public function test_register() {
        $this->request = array_merge($this->request, array(
            'reg_id' => 'APA91bELebwHpgKj15Ty6kCEU4pOSfG_w9TvJ5iZ6Tx2OoyS4rQEqIJX7tth5rLRaKyYqodcHTrVSzt8AwPfEjCRqhsJsxEUb1SaDjGjspfFxZsSGRBp67M4dpkpS_HlAsm673nzsTXuwqVqztO2-XjUpgH8tR9RPA',
            'client_id' => '64ed00497d2d98ed',
            'lang' => 'ro',
            'device_name' => 'Marius Xperia Z3',
            'device_serial_number' => 'CB5A240PXN',
            'google_user_id' => 0
            
        ));
        
        return $this->register();
    }

    public function register() {
        error_log($this->request['reg_id'] . ' IS TRYING TO REGISTER');
        $registration_id = isset($this->request['reg_id']) ? $this->request['reg_id'] : null;
        
        if (is_null($registration_id) || empty($registration_id)) {
            throw new Exception('Invalid registration ID (provided value: "' . $registration_id . '")');
        }
        
        $language_code = isset($this->request['lang']) ? $this->request['lang'] : 'en';
        $language = array_key_exists($language_code, $this->languages) ? $this->languages[$language_code] : $this->languages['en'];

        $google_user_id = isset($this->request['google_user_id']) ? $this->request['google_user_id'] : 0;
        $user_id = !empty($this->request['user_id']) ? $this->request['user_id'] : null;

        $device_name = isset($this->request['device_name']) ? $this->request['device_name'] : '';
        $serial_number = isset($this->request['device_serial_number']) ? $this->request['device_serial_number'] : '';
        $client_id = isset($this->request['client_id']) ? $this->request['client_id'] : '';
        $resolution = isset($this->request['resolution']) ? $this->request['resolution'] : null;
        $os_version = isset($this->request['os_version']) ? $this->request['os_version'] : null;
        $dpi = isset($this->request['dpi']) ? $this->request['dpi'] : null;

        if ($this->primary_endpoint == 'themeuser') {
            $source_theme_id = isset($this->request['theme_id']) ? (int) $this->request['theme_id'] : 0;
            if ($source_theme_id == 0) {
                throw new Exception('Invalid theme id (provided value: "' . $source_theme_id . '")');
            }

            $developer_account_id = isset($this->request['dev_id']) ? (int) $this->request['dev_id'] : 0;
            if ($developer_account_id == 0) {
                throw new Exception('Invalid developer account id (provided value: "' . $developer_account_id . '")');
            }
        } else {
            $source_theme_id = null;
            $developer_account_id = null;
        }

        $device = $this->device_collection->new_instance();
        
        $device_exists = $device->load_from_params(array(
            'registration_id_hash' => md5($registration_id),
            'user_id' => $user_id
        ));
        
        if ($device_exists) {
            $updates = array();
            if ($device->info['language_id'] != $language['id'])
                $updates['language_id'] = $language['id'];
            if (isset($device->info['device_name']) && $device->info['device_name'] != $device_name)
                $updates['device_name'] = $device_name;
            if (isset($device->info['serial_number']) && $device->info['serial_number'] != $serial_number)
                $updates['serial_number'] = $serial_number;
            if (isset($device->info['source_theme_id']) && $device->info['source_theme_id'] != $source_theme_id)
                $updates['source_theme_id'] = $source_theme_id;
            if (isset($device->info['developer_account_id']) && $device->info['developer_account_id'] != $developer_account_id)
                $updates['developer_account_id'] = $developer_account_id;
            if ($device->info['client_id'] != $client_id)
                $updates['client_id'] = $client_id;
            
            if (count($updates) > 0)
                $device->save($updates);
        } else {
            $device->save(array(
                'registration_id' => $registration_id,
                'language_id' => $language['id'],
                'google_user_id' => $google_user_id,
                'device_name' => $device_name,
                'source_theme_id' => $source_theme_id,
                'developer_account_id' => $developer_account_id,
                'serial_number' => $serial_number,
                'client_id' => $client_id,
                'user_id' => $user_id
            ));
        }
        
        $device_data = $this->device_collection->get_one(array('id' => $device->id));
        
        
        if ($user_id) {
            $this->load->helper('generic');
            sendBrowserChannelMessage("devices_u_{$user_id}", array(
                'type' => 'new_device',
                'device' => $device_data
            ));
        }
        
        return array('new_device' => !$device_exists, 'reg_id' => $registration_id, 'lang' => $language['iso_code']);
    }

    public function filters() {
        $launchers = $this->launchers;
        foreach ($launchers as &$launcher)
            $launcher = kms_remove_keys_except($launcher, 'id', 'name');

        $price_types = array();
        $this->load->library('crud');
        $aux = $this->crud->_process_data_source($this->store_theme_collection->data_fields['price_type']['data_source']);
        foreach ($aux as $key => $value) {
            $price_types[] = array('id' => $key, 'name' => $value);
        }

        return array(
            'ads' => array(5, 10, 15),
            'multiselect' => array(
                array('key' => 'launchers', 'label' => 'Select launchers', 'defaultLabel' => 'Any launcher', 'values' => array_values($launchers)),
                array('key' => 'styles', 'label' => 'Select styles', 'defaultLabel' => 'Any style', 'values' => array_values($this->styles)),
                array('key' => 'colors', 'label' => 'Select colors', 'defaultLabel' => 'Any color', 'values' => array_values($this->colors))
            ),
            'prices' => $price_types
        );
    }

    public function theme() {

        $theme_id = array_key_exists(0, $this->args) ? (int) array_shift($this->args) : null;
        if (is_null($theme_id) || $theme_id == 0)
            throw new Exception('Invalid theme ID (expected: /theme/<integer>)');

        $result = $this->search_themes(array('id' => $theme_id), true);
        if ($result['count'] == 0)
            throw new Exception('Theme ID not found (received: ' . $theme_id . ')');
        else
            return $result['list'][0];
    }

    /* public function themes() {			
      switch($this -> method) {
      case 'GET':
      switch($this -> verb) {
      case 'featured':
      return $this -> get_featured_themes();
      break;
      case 'latest':
      return $this -> get_latest_themes();
      case 'search':
      default:
      return $this -> search_themes($this -> request);
      break;
      }
      default:
      return $this -> method;
      break;
      }

      } */

    private function get_featured_themes() {
        return $this->search_themes();
    }

    private function get_latest_themes() {
        return $this->search_themes();
    }

    private function search_themes($query = array(), $single = false) {

        $this->load->library('search_hound');

        $params = array();

        if (isset($this->request['styles']) && strlen($this->request['styles']) > 0)
            $params['styles'] = explode(',', $this->request['styles']);
        if (isset($this->request['colors']) && strlen($this->request['colors']) > 0)
            $params['colors'] = explode(',', $this->request['colors']);
        if (isset($this->request['launchers']) && strlen($this->request['launchers']) > 0)
            $params['launchers'] = explode(',', $this->request['launchers']);
        if (isset($this->request['price_type']) && strlen($this->request['price_type']) > 0)
            $params['price_type'] = explode(',', $this->request['price_type']);
        if (isset($query['id']))
            $params['theme_id'] = (int) $query['id'];

        $offset = isset($this->args[0]) ? $this->args[0] : 0;
        $limit = isset($this->args[1]) ? $this->args[1] : 10;

        $result = $this->search_hound->search($params, $offset, $limit);
        foreach ($result['list'] as &$item) {
            $item['price'] = '$' . $item['price_in_usd'];
            $item['screenshots'] = array();
            $item['max_width'] = 0;
            $item['max_height'] = 0;
            foreach ($item['mobilescreenshots'] as $screenshot) {
                $item['screenshots'][] = array(
                    'width' => (int) $screenshot['width'],
                    'height' => (int) $screenshot['height'],
                    'original_url' => $screenshot['url'],
                    'url' => str_replace(base_url(), str_replace('http://', 'http://img.', base_url()), $screenshot['url'])
                );
                $item['max_width'] = max($item['max_width'], (int) $screenshot['width']);
                $item['max_height'] = max($item['max_height'], (int) $screenshot['height']);
            }
            $item['rating'] = $item['average_rating'];
            $item['review_count'] = $item['number_of_reviews'];
            $item['launcher'] = isset($item['launchers']) ? implode(', ', $item['launchers']) : '';
            if ($single)
                $item = kms_remove_keys_except($item, 'id', 'launcher', 'name', 'download_url', 'price', 'price_type', 'screenshots', 'max_width', 'max_height', 'description', 'rating', 'reviews', 'requirements');
            else
                $item = kms_remove_keys_except($item, 'id', 'launcher', 'name', 'download_url', 'price', 'price_type', 'screenshots', 'max_width', 'max_height', 'rating');
        }

        return $result;
    }

}
