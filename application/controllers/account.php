<?php

include('member_area.php');

class account extends member_area {
    
    public function __construct() {
        parent::__construct();
        
        $this->lang->load('header', 'en');
        $this->lang->load('app_main', 'en');
        $this->lang->load('tutorial', 'en');
        $this->set_template_var('languages', $this->lang->language);
    }
    
    public function index() {
        redirect("/account/profile");
    }

    public function profile() {

        $this->assets->addDependencies(array(
            'jqueryslimscroll'
        ));
        $this->bootstrap->frontend();
        $this->bootstrap->setup_fileupload();
        $this->set_template_var('success', $this->input->get('success', true));
        $this->assets->add_css('css/dashboard.css');
        $this->assets->add_css('css/user-profile.css');
        $this->assets->add_js('js/user-profile.js');
        $this->assets->add_js('js/common.js');
    }

    public function upload_profile_pic() {
        if (file_exists($_FILES['profile']['tmp_name'])) {
            $uid = $this->current_user->get('login.id');
            $dir = get_profile_pic_path($uid);
            $profile_pic = substr(md5(time()), 0, rand(5, 8));

            $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
            $filename = $profile_pic . '.' . $ext;

            $path = $dir . '/' . $filename;

            $old_filename = $this->current_user->get('login.profile_pic');
            if (!empty($old_filename)) {
                $old_path = $dir . "/" . $old_filename;
                @unlink($old_path);
            }

            move_uploaded_file($_FILES['profile']['tmp_name'], $path);

            resize_image($path, 200, 200);


            $this->load->model('user_collection');

            $this->user_collection->save(array(
                'profile_pic' => $filename
                    ), $uid);

            $url = kmsPathToUrl($path);

            $this->show_ajax(array(
                'url' => $url
            ));

            $this->current_user->set('login.profile_pic', $filename);
            $this->current_user->set('login.profile_pic_url', $url);
        } else {
            throw new AjaxException("An error ocurred (100). Please try again later!");
        }
    }

    public function changepassword() {

        $this->assets->addDependencies(array(
            'jqueryslimscroll'
        ));
        $this->bootstrap->frontend();
        $this->assets->add_css('css/user-profile.css');
        $this->assets->add_css('css/header.css');
        $this->assets->add_css('css/user-change-password.css');
        $this->assets->add_js('app/js/user-change-password.js');
        $this->assets->add_js('js/common.js');
    }

}
