<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "homepage";
$route['404_override'] = '';

$route['admin'] = 'admin/dashboard';
$route['admin\/(.*)'] = 'admin/$1';
$route['tools\/(.*)'] = 'tools/$1';
$route['deals\/([0-9]+)\/(.*)'] = 'deals/view/$1';
$route['user\/(.*)'] = 'user/$1';
$route['tags\/(.*)'] = 'tags/$1';
$route['crons\/(.*)'] = 'crons/$1';

$route['assets/store_theme_photos/([0-9]/[0-9]/[0-9])/([0-9]+)/([0-9]+_([a-z]+)_w([0-9]+)\.[a-z]+)'] = "aggregator_tools/resize/$2/$4/$5";
$route['user_data/profile/([0-9]+)/(w([0-9]+)h([0-9]+)|marker)/(([a-z0-9A-Z]+)\.(png|jpg))'] = "user/resize/$1/$2/$5";

/* End of file routes.php */
/* Location: ./application/config/routes.php */