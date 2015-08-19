<?php

if (!function_exists("has_access")) {

    function has_access($feature) {
        return get_instance()->current_user->has_access($feature);
    }

}

if (!function_exists("has_permission")) {

    function has_permission($permission) {
        return get_instance()->current_user->hasPermission($permission);
    }

}

if (!function_exists("has_any_permission")) {

    function has_any_permission($permissions) {

        if (is_array($permissions)) {
            foreach ($permissions as $perm) {

                $has = get_instance()->current_user->hasPermission($perm);
                if ($has) {
                    return true;
                }
            }
        } else {
            // is regex
            $user_permissions = get_instance()->current_user->get('login.permissions');
            foreach ($user_permissions as $perm) {
                if (preg_match($permissions, $perm)) {
                    return true;
                }
            }
        }

        return false;
    }

}


if (!function_exists("get_settings")) {

    function get_settings($key) {
        return get_instance()->current_user->getGlobalSettingsByKey($key);
    }

}


if (!function_exists("getAppDomain")) {

    function getAppDomain() {
        $base_url = get_instance()->config->item('base_url');
        preg_match("@https?://([^/]+)/@", $base_url, $m);

        return $m[1];
    }

}

if (!function_exists("has_role")) {

    function has_role($role) {
        return get_instance()->current_user->hasRole($role);
    }

}

if (!function_exists("has_any_role")) {

    function has_any_role($roles) {
        foreach ($roles as $role) {

            $has = get_instance()->current_user->hasRole($role);
            if ($has) {
                return true;
            }
        }

        return false;
    }

}


if (!function_exists("get_user_id")) {

    function get_user_id() {
        return is_object(get_instance()->current_user) ? get_instance()->current_user->get('login.id') : null;
    }

}

function array_intersect_keys_recursive($array1, $array2) {
    $new_keys = array_keys(array_diff_key($array1, $array2));

    $return = array();

    foreach ($array1 as $key => $val) {
        if (!in_array($key, $new_keys)) {
            if (is_array($val)) {
                $ret = array_intersect_keys_recursive($array1[$key], $array2[$key]);
                if (!empty($ret)) {
                    $return[$key] = $ret;
                }
            } else {
                $return[$key] = $val;
            }
        }
    }

    return $return;
}

function array_diff_keys_recursive($array1, $array2) {
    $new_keys = array_keys(array_diff_key($array1, $array2));

    $return = array();

    foreach ($array1 as $key => $val) {
        if (!in_array($key, $new_keys)) {
            if (is_array($val)) {
                $ret = array_diff_keys_recursive($array1[$key], $array2[$key]);
                if (!empty($ret)) {
                    $return[$key] = $ret;
                }
            }
        } else {
            $return[$key] = $val;
        }
    }

    return $return;
}

if (!function_exists('model_exists')) {

    function model_exists($name) {
        $CI = &get_instance();
        foreach ($CI->config->_config_paths as $config_path) {
            if (file_exists($config_path . 'models/' . $name . '.php')) {
                return true;
            }
        }
        return false;
    }

}

if (!function_exists("formatBytes")) {

    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}

if (!function_exists('force_login')) {

    function force_login($request_mode = 'ajax') {

        if (!get_instance()->current_user->is_logged_in()) {
            if (php_sapi_name() === 'cli') {
                return true;
            }

            switch ($request_mode) {
                case 'ajax':
                    echo 'login_timeout';
                    break;
                default:
                    redirect('/?goback=' . urlencode(current_url()));
                    break;
            }

            die();
        }
    }

}

if (!function_exists('get_country_time_offset')) {

    function get_country_time_offset($remote_country, $origin_country = null) {
        $remote_tz = array_shift(DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, strtoupper($remote_country)));
        $origin_tz = null;

        if ($origin_country) {
            $origin_tz = array_shift(DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, strtoupper($origin_country)));
        }

        return get_timezone_offset($remote_tz, $origin_tz);
    }

}

if (!function_exists('get_timezone_offset')) {

    function get_timezone_offset($remote_tz, $origin_tz = null) {
        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }

}

if (!function_exists('getTimeSince')) {

    function getTimeSince($time) {
        if ($time == null) {
            return 'never';
        }
        if (preg_match("@[^0-9]@", $time)) {
            $time = strtotime($time);
        }

        $time = time() - $time;

        if (!$time) {
            return 'now';
        }

        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hr',
            60 => 'min',
            1 => 'sec'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
        }
    }

}

if (!function_exists('force_admin')) {

    function force_admin($request_mode = 'ajax') {

        if (!get_instance()->current_user->is_admin()) {

            switch ($request_mode) {
                case 'ajax':
                    echo 'login_timeout';
                    break;
                default:
                    redirect('user/login?goback=' . urlencode(current_url()));
                    break;
            }

            die();
        }
    }

}

if (!function_exists('db_string_to_words')) {

    function db_string_to_words($key) {
        // build nice alias
        $key = preg_replace("@^id_@", "", $key);
        $new_key = preg_replace("@[^0-9A-Za-z/\\\]@", " ", $key);
        $keys = explode(" ", $new_key);
        foreach ($keys as &$k) {
            $k = ucfirst($k);
        }
        $key = implode(" ", $keys);
        $key = str_replace("Table", " Table", $key);
        return $key;
    }

}

if (!function_exists('array_column')) {

    function array_column($array, $column) {
        $new_array = array();
        foreach ($array as $row) {
            if (isset($row[$column])) {
                $new_array[] = $row[$column];
            }
        }

        return $new_array;
    }

}

function get_resource_for_file($filename) {
    return get_resource_by_identifier(get_resource_id_for_file($filename));
}

function get_resource_id_for_file($filename) {
    return preg_replace("@(\.(jpeg|jpg|gif|png|9\.png|ttf|otf|wav|mp3))@i", "", basename($filename));
}

function get_similar_resources($filepath) {
    $return = array();
    $extensions = array('.jpg', '.jpeg', '.9.png', '.png', '.otf', '.ttf', '.wav', '.mp3');

    $ext = '.' . array_pop(explode('.', $filepath));
    $filepath = str_replace($ext, '', $filepath);
    if (substr($filepath, -1) == '9') {
        $ext = '.9.png';
        $filepath = substr($filepath, 0, strlen($filepath) - 2);
    }

    foreach ($extensions as $e) {
        if (file_exists($filepath . $e)) {
            $return[] = $filepath . $e;
        }
    }

    return $return;
}

function remove_similar_resources($filepath) {
    foreach (get_similar_resources($filepath) as $file) {
        if ($file != $filepath) {
            unlink($file);
        }
    }
}

function get_resource_by_identifier($resource_id, $file = true, $launcher_id = null) {

    if ($launcher_id !== null) {
        $theme = get_instance()->theme_collection->new_instance();
        $theme->info['launcher_id'] = $launcher_id;

        $resources = $theme->get_editor_config(false);
    } else {
        $resources = get_instance()->globals['current_theme']->get_editor_config();
    }

    foreach ($resources['assets'] as $folder => $list) {
        // when requesting resource by identifier for files, 
        // sometimes an object can have an image attached with 
        // the same identifier as the parent, and we really need the image resource, not the parent
        // if file is false, we need the parent
        if (isset($list[$resource_id]) && (!isset($list[$resource_id]['object']) || !$file)) {
            return array(
                'id' => $resource_id,
                'resource_id' => $resource_id,
                'folder' => $folder,
                'resource' => $list[$resource_id]
            );
        }

        foreach ($list as $identifier => $resource) {
            if (isset($resource['object']) && preg_match("@{$identifier}@", $resource_id)) {
                foreach ($resource['object'] as $k => $prop) {
                    // this property is an image
                    // if image has an identifier, check if the resource_id matches that,
                    // otherwise check the pattern
                    // for array of objects must be {$identifier}_{$index}_{$k}
                    // for object only must be {$identifier}_{$k}

                    $prop['id'] = $k;

                    if (isset($prop['identifier'])) {
                        if ($prop['identifier'] == $resource_id) {
                            return array(
                                'id' => $identifier,
                                'resource_id' => "{$identifier}_{$k}",
                                'folder' => $folder,
                                'resource' => $prop
                            );
                        }
                    } else {
                        if ($resource['type'] == 'array' && preg_match("@{$identifier}_([a-z0-9]+)_{$k}@", $resource_id, $m)) {
                            return array(
                                'id' => $identifier,
                                'resource_id' => "{$identifier}_{$m[1]}_{$k}",
                                'folder' => $folder,
                                'resource' => $prop,
                                'index' => $m[1]
                            );
                        }

                        if ($resource['type'] == 'object' && "{$identifier}_{$k}" == $resource_id) {
                            return array(
                                'id' => $identifier,
                                'resource_id' => "{$identifier}_{$k}",
                                'folder' => $folder,
                                'resource' => $prop
                            );
                        }
                    }
                }
            }
        }
    }
}

function get_resource_folder($file_name, $launcher_id = null) {
    $resource_id = preg_replace("@\.(9\.png|png|jpe?g|gif|ttf|otf|wav|mp3)@i", "", $file_name);
    $exists = get_resource_by_identifier($resource_id, true, $launcher_id);

    return isset($exists['folder']) ? $exists['folder'] : 'temp';
}

function download_zip($zipfile) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . basename($zipfile) . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($zipfile));
    @ob_end_flush();
    readfile($zipfile);
    die();
}

function cli_log($msg) {
    if (php_sapi_name() === 'cli') {
        echo date("Y-m-d H:i:s") . " - {$msg}\n";
    }
}

function sendBrowserMessage($message) {
    $CI = get_instance();
    $CI->load->library('client_notifier');
    return $CI->client_notifier->sendMessage($message);
}

function sendBrowserChannelMessage($channel, $message) {
    $CI = get_instance();
    $CI->load->library('client_notifier');
    return $CI->client_notifier->sendMessageToChannel($channel, $message);
}

function getDomain() {
    return trim(preg_replace("@https?://@", "", get_instance()->config->item('base_url')), "/");
}

function get_profile_pic_url($user, $thumb = false) {
    if (empty($user['profile_pic'])) {
        return '/assets/dashboardv2/user_profile.svg';
    } else {
        return kmsPathToUrl(get_profile_pic_path($user['id']) . ($thumb ? (is_numeric($thumb) ? "w{$thumb}h{$thumb}/" : $thumb . '/') : '') . $user['profile_pic']);
    }
}

function get_profile_pic_path($user_id) {
    $path = get_instance()->config->item('webroot_path') . "/user_data/profile/{$user_id}/";

    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }

    return $path;
}

function resize_image($file, $w, $h, $crop = FALSE) {
    list($width, $height) = getimagesize($file);

    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }

    $extension = pathinfo($file, PATHINFO_EXTENSION);

    $qq = 1;

    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $image_save_func = 'imagejpeg';
            $src = @imagecreatefromjpeg($file);
            break;
        case 'gif':
            $image_save_func = 'imagegif';
            $src = @imagecreatefromgif($file);
            break;
        case 'png':
            $qq = 9 / 100;
            $image_save_func = 'imagepng';
            $src = @imagecreatefrompng($file);
            break;
        default:
            $img = false;
            break;
    }

    $quality = 100 * $qq;
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    call_user_func_array($image_save_func, array($dst, $file, $quality));
    
    imagedestroy($dst);
    imagedestroy($src);
}
