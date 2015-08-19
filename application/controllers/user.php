<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('main_controller.php');

class user extends main_controller {

    public function dashboard() {
        
    }

    public function logout() {
        $this->current_user->logout();
        $this->current_user->set("closed_confirmation_pop_up", null);
        redirect('/');
    }

    public function login() {

        $this->bootstrap->frontend();
        $this->assets->add_css('css/simplepage.css');
        $this->assets->add_css('css/login-register.css');
        $this->assets->add_js('js/login.js');

        $this->load->helper('form');

        if ($this->input->get('goback')) {
            $this->set_template_var('goback', urldecode($this->input->get('goback')));
        }

        $this->set_template('web/login.tpl');
        $this->show_page();
    }

    public function register() {

        $this->bootstrap->frontend();
        $this->assets->add_css('css/simplepage.css');
        $this->assets->add_css('css/login-register.css');
        $this->load->helper('form');
        $this->assets->add_js('js/register.js');
        if ($this->input->get('goback')) {
            $this->set_template_var('goback', urldecode($this->input->get('goback')));
        }
        $this->set_template('web/register.tpl');
        $this->show_page();
    }

    public function post_login() {

        $data = $this->input->post();
        $login_result = $this->current_user->login($data);

        $login_result['goback'] = ( isset($data['goback']) && strlen($data['goback']) > 0 ) ? $data['goback'] : ( $this->config->item('base_url') . '/dashboard' );

        $this->show_ajax($login_result);
    }

    public function post_register() {

        $this->load->model('user_collection');
        $data = $this->input->post(NULL, TRUE);
        $data['hash'] = sha1(time());

        $existingEmail = $this->user_collection->get_one(array('email' => $data['email']));


        if (empty($data['username'])) {
            throw new AjaxException(array("username" => "The username is mandatory!"));
        }
        if (empty($data['email'])) {
            throw new AjaxException(array("email" => "The e-mail is mandatory!"));
        }
        if (!empty($existingEmail)) {
            throw new AjaxException(array("email" => "The e-mail already exist in our database!"));
        }

        if (!filter_var("{$data['email']}", FILTER_VALIDATE_EMAIL)) {
            throw new AjaxException(array("email" => $data['email'] . " is an invalid email."));
        }

        if (empty($data['password'])) {
            throw new AjaxException(array("password" => "The password is mandatory!"));
        }


        do {
            $unique_id = substr(md5(time() . rand()), 0, rand(5, 8));


            $insert_data = [
                "username" => $data['username'],
                "password" => $data['password'],
                "email" => $data['email'],
                "hash" => sha1($unique_id)
            ];

            $user_data = $this->user_collection->save($insert_data);
        } while (!$user_data['success']);

        $login_result = $this->current_user->login($data);
        $user_data['data']['id'] = $login_result['user']['id'];

//        $result_email = $this->current_user->triggerEmailNotification(
//                $user_data['data'], "Redraw.io welcomes you!", "Hi there! <br/> Thank you for taking an interest into redraw.io.  We've worked very hard to be the world's first web-based platform for theme creation across multiple apps and devices. Hope you'll enjoy customizing your apps! Drop us a line anytime or just swing by the office, we're fun to hang out - free drinks, cool artwork on the walls and we do have a gaming area filled with PS and XBox games! <br/><br/>
//
//Just click the link below to confirm your account and you'll be well on your way to creating awesome native and custom themes!", "user/confirm_user/" . $user_data['data']['hash']
//        );
        $this->show_ajax($login_result);
    }

    public function create_random_user() {
        $this->load->model('user_collection');

        do {
            $unique_id = substr(md5(time() . rand()), 0, rand(5, 8));

            $data = array(
                "email" => "username" . $unique_id . "@timmystudios.com",
                "name" => 'demo',
                "username" => "demo_" . $unique_id,
                "password" => "demo" . $unique_id,
                "hash" => sha1($unique_id)
            );

            $added = $this->user_collection->save($data);
        } while (!$added['success']);

        $login_result = $this->current_user->login($data);
        $this->show_ajax($login_result);
    }

    public function confirm_user($hash) {
        $this->load->model('user_collection');
        $user = $this->user_collection->get_one(array('hash' => $hash));
        $confirmed = $this->user_collection->save(array('email' => $user['email'], 'confirmed' => '1'), $user['id']);
        if ($confirmed) {
            $this->current_user->login($user, false);

            if ($confirmed) {
                redirect("/dashboard");
            }
        }
    }

    public function check_password() {
        $data = $this->input->post(null, true);
        $username = $data['username'];
        $password = $data['password'];
        $this->load->model('user_collection');
        $this->show_ajax($this->user_collection->get_one(array('username' => $username, 'password' => sha1($password))));
    }

    public function postpone_confirmation() {
        return $this->current_user->set("closed_confirmation_pop_up", "dismiss");
    }

    public function check_old_password() {
        $get_data = $this->input->get(null, true);
        $post_data = $this->input->post(null, true);

        $this->load->model('user_collection');
        $existingUser = $this->user_collection->get_one(array('hash' => $get_data['hash'], 'password' => sha1($post_data['password'])));
        if (!$existingUser) {
            throw new AjaxException(array("old_password" => "Wrong password!"));
        }
        $this->show_ajax(["success" => true]);
    }

    public function update_user_password() {
        $get_data = $this->input->get(null, true);
        $post_data = $this->input->post(null, true);
        $this->load->model('user_collection');

        $existingUser = $this->user_collection->get_one(array('hash' => $get_data['hash'], 'password' => sha1($post_data['old_password'])));
        $updated = $this->user_collection->save(["password" => $post_data['new_password']], $existingUser['id']);
        $this->show_ajax($updated);
    }

    public function send_reset_password_email() {
        $this->load->model('user_collection');
        $data = $this->input->post(null, true);
        $existingUser = $this->user_collection->get_one(array('username' => $data['email']));
        $existingEmail = $this->user_collection->get_one(array('email' => $data['email']));
        if (empty($data['email'])) {
            throw new AjaxException(array("email" => "The e-mail is mandatory!"));
        }
        $user_data = [];
        if (!$existingUser && !$existingEmail) {
            throw new AjaxException(array("email" => "There is no user with this email/username!"));
        } else {

            if ($existingUser) {
                $user_data = $existingEmail;
            }
            if ($existingEmail) {
                $user_data = $existingEmail;
            }
        }
        if (isset($existingUser) && !empty($existingUser)) {
            $data['email'] = $existingUser['email'];
        }
        $data['name'] = $data['email'];
        $result_email = $this->current_user->triggerEmailNotification(
                $data, "Email confirmation from {$this->config->item('base_url')}", "sdksdjfh ksjdh sjdh fksjhf ksjfh ksjdfh ", "landingpagev2#reset-forgot-password?hash=" . $user_data['hash']
        );
        $this->show_ajax(["success" => true]);
    }

    public function search() {
        $keyword = $this->db->escape_str($this->input->get('q'));

        $uid = $this->current_user->get('login.id');
        $this->load->decorator('UserDataDecorator');
        $this->load->model('user_collection');
        $this->load->model('friendship_collection');
        
        $ft = $this->friendship_collection->get_data_table();
        
        $collection = new UserDataDecorator($this->user_collection, array('thumb' => 30));

        $this->show_ajax(
                $collection->get(
                    array("
                        (
                            username LIKE '%{$keyword}%' OR 
                            name LIKE '%{$keyword}%' OR 
                            email LIKE '%{$keyword}%'
                        ) 
                        AND 
                        foo.user_id IS NULL"
                    ), 
                    null, null, null, array(
                    'fields' => 'id as user_id',
                    'sql_join' => "LEFT JOIN (
                        SELECT request_user_id as user_id FROM {$ft} WHERE target_user_id = {$uid}
                        UNION 
                        SELECT target_user_id as user_id FROM {$ft} WHERE request_user_id = {$uid}
                    ) foo ON foo.user_id = a.id"
            )
        ));
    }

    public function resize($user_id, $format, $filename) {

        
        if (preg_match("@w([0-9]+)h([0-9]+)@", $format, $m)) {
            $w = $m[1];
            $h = $m[2];
        } else {
            $w = 260;
            $h = 260;
        }

        $user_path = get_profile_pic_path($user_id) . '/';
        $thumb_dir = $user_path . "{$format}/";

        if (!is_dir($thumb_dir)) {
            mkdir($thumb_dir, 0777, true);
        }

        $imagick = new \Imagick(realpath($user_path . $filename));
        $imagick->setImageFormat("jpg");
        $imagick->resizeImage($w, $h, Imagick::FILTER_LANCZOS, 1);

        if ($format == 'marker') {
            $marker_path = get_instance()->config->item('webroot_path') . '/assets/marker_overlay.png';
            $marker = new \Imagick(realpath($marker_path));
            $marker->cropImage(320, 512, 96, 0);

            $new = new \Imagick();
            $new->newImage(320, 512, "none");
            $new->setImageFormat('png');
            $new->compositeImage($imagick, Imagick::COMPOSITE_DEFAULT, 32, 30);
            $new->compositeImage($marker, Imagick::COMPOSITE_DEFAULT, 0, 0);

            // Add overlay
            $new->resizeImage(80, 120, Imagick::FILTER_LANCZOS, 1);

            $new->writeImage($thumb_dir . $filename);
            
            header("Content-Type: image/jpeg");
            echo $new->getImageBlob();
            
            $new->destroy();
        } else {
            $imagick->writeImage($thumb_dir . $filename);
            header("Content-Type: image/jpeg");
            echo $imagick->getImageBlob();
            $imagick->destroy();
        }
    }

}
