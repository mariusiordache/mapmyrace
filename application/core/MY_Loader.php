<?php

class MY_Loader extends CI_Loader {

    public function decorator($model) {

        foreach ($this->_ci_model_paths as $mod_path) {
            if (!file_exists($mod_path . 'models/decorators/' . $model . '.php')) {
                continue;
            }

            require_once($mod_path . 'models/decorators/' . $model . '.php');
        }
    }

    public function dbview($view_name) {
        if (!class_exists('kms_view')) {
            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'models/kms_view.php')) {
                    continue;
                }

                require_once($mod_path . 'models/kms_view.php');
            }
        }

        get_instance()->$view_name = new kms_view($view_name);
        return get_instance()->$view_name;
    }
}
