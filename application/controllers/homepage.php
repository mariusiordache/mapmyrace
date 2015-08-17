<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('main_controller.php');

class homepage extends main_controller {
    /* main_controller loads library bootstrap.php that does most of the initializations */

    public function index() {
        if (get_instance()->current_user->get('login.id') > 0) {
            redirect('/dashboard');
        }

        redirect('/landingpage');
    }

}
