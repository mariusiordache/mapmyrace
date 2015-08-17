<?php

/**
 * Description of post_controller
 *
 * @author marius
 */
class post_controller {
    
    public function main() {
        
        if (php_sapi_name() == 'cli' or defined('STDIN')) {
            return;
        }
        
        $CI = get_instance();
        $OUT = $CI->output;
        
        if (!$OUT->getType()) {
            call_user_func(array($CI, 'show_page'));
        }
    }
}
