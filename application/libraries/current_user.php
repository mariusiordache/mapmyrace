<?php

class current_user extends kms_current_user {

    protected static $defaultMailObj = null;
    private $admin_user_ids = null;
    private $general_settings = null;

    public function logout() {

        $this->set('login', null);
    }

    public function login($data, $encript = true) {
        $this->_CI->load->model('user_collection');

        $userfield_type = 'username';
        $userfield = isset($data['email']) ? $data['email'] : (isset($data['username']) ? $data['username'] : '');
        if (filter_var($userfield, FILTER_VALIDATE_EMAIL)) {
            $userfield_type = 'email';
        }

        $user_exists = $this->_CI->user_collection->get_one(array(
            $userfield_type => $userfield
        ));

        if ($user_exists === false) {
            return array('success' => false, 'errors' => array('username' => 'Bad username!'));
        }
        if ($encript) {
            $user_found = $this->_CI->user_collection->get_one(array(
                $userfield_type => $userfield,
                'password' => sha1($data['password'])
            ));
        } else {
            $user_found = $this->_CI->user_collection->get_one(array(
                $userfield_type => $userfield,
                'password' => $data['password']
            ));
        }
        if ($user_found !== false) {

            $this->_CI->load->model('user_role_collection');
            $this->_CI->load->model('role_collection');
            $this->_CI->load->model('permission_collection');
            $this->_CI->load->model('role_permission_collection');
            $this->_CI->load->model('user_permission_collection');

            $user_found['permissions'] = $this->_CI->permission_collection->get_list(array(), null, null, null, array(
                'sql_join' => "INNER JOIN (
                        SELECT 
                                permission_id FROM {$this->_CI->role_permission_collection->get_data_table()} r 
                            INNER JOIN {$this->_CI->user_role_collection->get_data_table()} ur ON ur.role_id = r.role_id WHERE ur.user_id = {$user_found['id']}
                        UNION 
                        SELECT permission_id FROM {$this->_CI->user_permission_collection->get_data_table()} up WHERE up.user_id = {$user_found['id']}
                    ) foo ON foo.permission_id = a.id",
                'fields' => 'permission'
            ));


            $user_found['roles'] = $this->_CI->user_role_collection->get_list(array('user_id' => $user_found['id']), null, null, null, array(
                'sql_join' => "INNER JOIN {$this->_CI->role_collection->get_data_table()} r ON r.id = a.role_id",
                'fields' => 'name'
            ));


            // register user last login
            // register this domain
            $this->_CI->user_collection->save(array(
                'last_login' => 'NOW()'
            ), $user_found['id']);

            
            $user_found['profile_pic_url'] = get_profile_pic_url($user_found);

            foreach ($user_found as $key => $value) {
                $this->set('login.' . $key, $value);
            }


            return array('success' => true, 'user' => $user_found);
        } else {
            return array('success' => false, 'errors' => array('password' => 'Bad password!'));
        }
    }

    public $events = array(
        'settings.kids' => 'kidsUpdated'
    );

    public function getTmpDir() {
        $CI = get_instance();
        $dir = $CI->config->item('webroot_path') . '/tmp/' . $this->get('login.username') . '/';

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir;
    }

    public function getGlobalSettingsByKey($key) {
        $settings = $this->getGlobalSettings();
        return isset($settings[$key]) ? $settings[$key] : null;
    }

    public function getGlobalSettings() {
        if ($this->general_settings === null) {
            $this->_CI->load->model('global_settings_collection');
            $config = $this->_CI->global_settings_collection->get();
            $this->general_settings = kms_array_to_html_options($config, 'identifier', 'value');
        }

        return $this->general_settings;
    }

    public function set($key, $value) {

        parent::set($key, $value);

        foreach ($this->events as $event => $callback) {
            if (strpos($key, $event) === 0 && ($key == $event || substr($key, strlen($event), 1) == '.')) {
                call_user_func(array($this, $callback));
            }
        }

        unset($event, $callback);
    }

    public function hasRole($role) {
        return is_array($this->get('login.roles')) && in_array($role, $this->get('login.roles'));
    }

    public function hasPermission($permission) {
        return is_array($this->get('login.permissions')) && in_array($permission, $this->get('login.permissions'));
    }

    public function is_admin() {
        return ( $this->get('login.is_admin') );
    }

    public function is_logged_in() {
        return ($this->get('login.id') > 0);
    }

    public function has_access($feature) {
        switch ($feature) {
            case 'admin':
                return $this->is_admin();
            case 'get_apk':
                return $this->is_admin() || ($this->get('login.is_admin') == 3) || $this->hasRole('uploader');
            case 'gcm':
            case 'reporting':
                return $this->is_admin() || ($this->get('login.is_admin') == 3);
            case 'aggregator':
                return $this->is_admin() || ($this->get('login.is_admin') == 2);
            default:
                return $this->hasPermission($feature) || $this->hasRole($feature) || $this->is_admin();
        }
        return false;
    }

    public function triggerAdminNotification($subject, $message, $action = '/') {
        if ($this->admin_user_ids === null) {
            $this->_CI->load->model('user_collection');
            $this->admin_user_ids = $this->_CI->user_collection->get_list(array('is_admin' => 1), null, null, null, array('fields' => 'id'));
        }

        return $this->triggerNotification($this->admin_user_ids, $subject, $message, $action);
    }

    public function triggerNotification($user_ids, $subject, $message, $action = '/') {
        $action = "/" . trim(str_replace($this->_CI->config->item('base_url'), "", $action), "/");
        $this->_CI->load->model('user_notification_collection');
        $this->_CI->load->model('user_collection');
        $this->_CI->load->helper('kms_array_helper');
        $notifications = array();
        $user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
        $users = kms_assoc_by_field($this->_CI->user_collection->get(array('id' => $user_ids)));

        foreach ($user_ids as $uid) {
            $this->get('login.id') ? $sender_id = $this->get('login.id') : $sender_id = null;

            if (!empty($users[$uid])) {

                $notification = $this->_CI->user_notification_collection->save(array(
                    'user_id' => $uid,
                    'sender_id' => $sender_id,
                    'subject' => $subject,
                    'message' => $message,
                    'action' => $action,
                    'status' => 0
                ));

                $this->triggerEmailNotification($users[$uid], $subject, $message, '/notifications/read/' . $notification['id']);
            }
        }
    }

    protected static function getNewMailMessage() {
        if (self::$defaultMailObj === null) {
            $CI = get_instance();

            $mailConfig = $CI->config->item('mail');

            if (empty($mailConfig['noreply'])) {
                throw new Exception("You must configure NoReply email.");
            }

            $noreply = $mailConfig['noreply'];

            $mail = new PHPMailer();

            $mail->IsSMTP();                    // send via SMTP
            $mail->Host = $noreply['host'];

            $mail->SMTPAuth = true;
            $mail->CharSet = "UTF-8";
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;

            // turn on SMTP authentication
            $mail->Username = $noreply['username'];
            $mail->Password = $noreply['password'];

            $mail->From = $noreply['from'];
            $mail->FromName = $noreply['fromName'];

            self::$defaultMailObj = $mail;
        }

        return self::$defaultMailObj;
    }

    public function triggerEmailNotification($user, $subject, $message, $url = '/dashboard', $tpl = "app/notification.tpl") {

        try {

            $CI = get_instance();
            $socketio = $CI->config->item('socketio');
            $domain = !empty($socketio['domain']) ? $socketio['domain'] : trim(preg_replace("@https?://@", "", $CI->config->item('base_url')), "/");
            $port = !empty($socketio['udpport']) ? $socketio['udpport'] : 7659;

            $fp = fsockopen('udp://' . $domain, $port, $errno, $errstr, 2);

            if (!empty($fp) && isset($user['password'])) {
                $sockmessage = array();
                $sockmessage['user_id'] = $user['id'];
                $sockmessage['password'] = $user['password'];
                $sockmessage['body'] = $message;
                $sockmessage['title'] = $subject;
                $sockmessage['url'] = rtrim($CI->config->item('base_url'), "/") . "/" . ltrim($url, "/");

                fwrite($fp, json_encode($sockmessage));
                fclose($fp);
            }

            if (empty($user['email']) || !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $mail = clone self::getNewMailMessage();

            $toName = !empty($user['name']) ? $user['name'] : null;
            $to = $user['email'];

            $mail->AddAddress($to, $toName);

            $mail->Priority = 1;
            $mail->IsHTML(true);

            $CI = get_instance();

            $CI->template_engine->assign('base_url', $CI->config->item('base_url'));
            $CI->template_engine->assign('name', @$user['name']);
            $CI->template_engine->assign('url', $url);
            $CI->template_engine->assign('message', $message);

            $mail->Subject = $subject;
            $mail->Body = $CI->template_engine->fetch($tpl);

            if (!$mail->send()) {
                throw new Exception($mail->ErrorInfo);
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage() . " on triggerEmailNotification: {$message}");
        }
    }

}
