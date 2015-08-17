<?php

/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

/*
 * ---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 * ---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = '/www/CIKMS/system';
$composer_autoload = dirname(__FILE__) . '/vendor/autoload.php';

if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    if (!file_exists(dirname(__FILE__) . '/config.yml')) {
        throw new Exception("There is no config file config.yml in base folder");
    }

    preg_match("@([0-9]\.[0-9]+)@", phpversion(), $version_match);

    if ($version_match[1] >= 5.3) {
        // fixed bug where php version was prior 5.3 and didn't support namespaces

        eval('$yaml = new Symfony\Component\Yaml\Parser();');

        try {
            $GLOBALS['config_file'] = $yaml->parse(file_get_contents(dirname(__FILE__) . '/config.yml'));
            if (php_sapi_name() === 'cli' && !empty($config_file['cli'])) {
                // overwrite config for cli
                foreach ($config_file['cli'] as $k => $v) {
                    $config_file[$k] = array_merge($config_file[$k], $v);
                }
            }

            if (isset($config_file['constants'])) {
                foreach ($config_file['constants'] as $c => $v) {
                    define(strtoupper($c), $v);
                }
            }

            if (isset($config_file['globals'])) {
                foreach ($config_file['globals'] as $c => $v) {
                    $$c = $v;
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die;
        }
    }
}

if (!defined('ENVIRONMENT')) {
    // fallback where there is no config.yml file

    if (strpos(__FILE__, 'www.timmystudios.com') === false) {
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', 'production');
    }
}


/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
        case 'testing':
        case 'remote':
        case 'api':
        case 'aws':
            error_reporting(E_ALL ^ E_DEPRECATED);
            break;

        case 'production':
            if (isset($_GET['errorreporting'])) {
                error_reporting(E_ALL ^ E_DEPRECATED);
            } else {
                error_reporting(0);
            }

            break;

        default:
            exit('The application environment is not set correctly.');
    }
}


$h = opendir($system_path);

/*
 * ---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 * ---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */

$application_folder = dirname(__FILE__) . '/application';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
// The directory name, relative to the "controllers" folder.  Leave blank
// if your controller is not in a sub-folder within the "controllers" folder
// $routing['directory'] = '';
// The controller class file name.  Example:  Mycontroller
// $routing['controller'] = '';
// The controller function you wish to be called.
// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';
// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}
if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path) . '/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/') . '/';

// Is the system path correct?
if (!is_dir($system_path)) {
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: " . pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder)) {
    define('APPPATH', $application_folder . '/');
} else {
    if (!is_dir(BASEPATH . $application_folder . '/')) {
        exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF);
    }

    define('APPPATH', BASEPATH . $application_folder . '/');
}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */

class AjaxException extends Exception {

    private $response = array();
    private $is_custom = false;

    public function __construct($message, $code = null, $previous = null) {
        if (is_array($message)) {
            $this->setResponse($message);
            $this->is_custom = true;
            $message = '';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getMyMessage() {
        if ($this->is_custom) {
            return $this->getResponse(true);
        }

        return $this->getMessage();
    }

    function getResponse($force = false) {
        if (!$this->is_custom || $force) {
            return $this->response;
        }
        return array();
    }

    function setResponse($response) {
        $this->response = $response;

        return $this;
    }

}

try {

    require_once BASEPATH . 'core/CodeIgniter.php';
} catch (AjaxException $ex) {
    echo json_encode(array_merge(array(
        'success' => false,
        'errors' => (array) $ex->getMyMessage()
                    ), $ex->getResponse()));
} catch (Exception $ex) {
    show_error($ex->getMessage());
}

/* End of file index.php */
/* Location: ./index.php */
