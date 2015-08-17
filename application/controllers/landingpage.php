<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include('main_controller.php');

class landingpage extends main_controller {
    /* main_controller loads library bootstrap.php that does most of the initializations */

    public function index() {
        $this->assets->addDependencies(array(
            'bootstrap',
            'moment',
            'jquery-validation'
        ));
        $this->assets->add_js('js/main.js', false);
        $this->assets->add_js('js/login.js', false);
        $this->assets->add_js('https://maps.googleapis.com/maps/api/js?key=AIzaSyAmDfFBHTVc_gDC4imtGtMJsveLgxI5N_A&libraries=geometry', false);
        $this->assets->add_css('css/landingpage.css', false);
        $this->set_template('web/landingpage.tpl');
    }

}
