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
| 	example.com/class/method/id/
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
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "auth";
$route['404_override'] = '';

/*
*	Auth Routes
*/
$route['login'] = 'auth/login_user';

/*
*	Admin Routes
*/
$route['dashboard'] = 'admin/dashboard';

/*
*	administration Routes
*/
$route['administration/sections'] = 'admin/sections/index';
$route['administration/sections/(:any)/(:any)/(:num)'] = 'admin/sections/index/$1/$2/$3';
$route['administration/add-section'] = 'admin/sections/add_section';
$route['administration/edit-section/(:num)'] = 'admin/sections/edit_section/$1';
$route['administration/edit-section/(:num)/(:num)'] = 'admin/sections/edit_section/$1/$2';
$route['administration/delete-section/(:num)'] = 'admin/sections/delete_section/$1';
$route['administration/delete-section/(:num)/(:num)'] = 'admin/sections/delete_section/$1/$2';
$route['administration/activate-section/(:num)'] = 'admin/sections/activate_section/$1';
$route['administration/activate-section/(:num)/(:num)'] = 'admin/sections/activate_section/$1/$2';
$route['administration/deactivate-section/(:num)'] = 'admin/sections/deactivate_section/$1';
$route['administration/deactivate-section/(:num)/(:num)'] = 'admin/sections/deactivate_section/$1/$2';

$route['administration/company-profile'] = 'admin/contacts/show_contacts';

/*
*	HR Routes
*/
$route['human-resource/personnel'] = 'hr/personnel/index';
$route['human-resource/personnel/(:any)/(:any)/(:num)'] = 'hr/personnel/index/$1/$2/$3';
$route['human-resource/add-personnel'] = 'hr/personnel/add_personnel';
$route['human-resource/edit-personnel/(:num)'] = 'hr/personnel/edit_personnel/$1';
$route['human-resource/edit-personnel/(:num)/(:num)'] = 'hr/personnel/edit_personnel/$1/$2';
$route['human-resource/delete-personnel/(:num)'] = 'hr/personnel/delete_personnel/$1';
$route['human-resource/delete-personnel/(:num)/(:num)'] = 'hr/personnel/delete_personnel/$1/$2';
$route['human-resource/activate-personnel/(:num)'] = 'hr/personnel/activate_personnel/$1';
$route['human-resource/activate-personnel/(:num)/(:num)'] = 'hr/personnel/activate_personnel/$1/$2';
$route['human-resource/deactivate-personnel/(:num)'] = 'hr/personnel/deactivate_personnel/$1';
$route['human-resource/deactivate-personnel/(:num)/(:num)'] = 'hr/personnel/deactivate_personnel/$1/$2';

/*
*	Inventory Routes
*/
$route['inventory/units-of-measurement'] = 'inventory/unit/index';
$route['inventory/units-of-measurement/(:any)/(:any)/(:num)'] = 'inventory/unit/index/$1/$2/$3';
$route['inventory/add-personnel'] = 'inventory/personnel/add_personnel';
$route['inventory/edit-personnel/(:num)'] = 'inventory/personnel/edit_personnel/$1';
$route['inventory/edit-personnel/(:num)/(:num)'] = 'inventory/personnel/edit_personnel/$1/$2';
$route['inventory/delete-personnel/(:num)'] = 'inventory/personnel/delete_personnel/$1';
$route['inventory/delete-personnel/(:num)/(:num)'] = 'inventory/personnel/delete_personnel/$1/$2';
$route['inventory/activate-personnel/(:num)'] = 'inventory/personnel/activate_personnel/$1';
$route['inventory/activate-personnel/(:num)/(:num)'] = 'inventory/personnel/activate_personnel/$1/$2';
$route['inventory/deactivate-personnel/(:num)'] = 'inventory/personnel/deactivate_personnel/$1';
$route['inventory/deactivate-personnel/(:num)/(:num)'] = 'inventory/personnel/deactivate_personnel/$1/$2';

/*
*	Microfinance Routes
*/
$route['microfinance/individual'] = 'microfinance/individual/index';
$route['microfinance/individual/(:any)/(:any)/(:num)'] = 'microfinance/individual/index/$1/$2/$3';
$route['microfinance/add-individual'] = 'microfinance/individual/add_individual';
$route['microfinance/edit-individual/(:num)'] = 'microfinance/individual/edit_individual/$1';
$route['microfinance/edit-individual/(:num)/(:num)'] = 'microfinance/individual/edit_individual/$1/$2';
$route['microfinance/delete-individual/(:num)'] = 'microfinance/individual/delete_individual/$1';
$route['microfinance/delete-individual/(:num)/(:num)'] = 'microfinance/individual/delete_individual/$1/$2';
$route['microfinance/activate-individual/(:num)'] = 'microfinance/individual/activate_individual/$1';
$route['microfinance/activate-individual/(:num)/(:num)'] = 'microfinance/individual/activate_individual/$1/$2';
$route['microfinance/deactivate-individual/(:num)'] = 'microfinance/individual/deactivate_individual/$1';
$route['microfinance/deactivate-individual/(:num)/(:num)'] = 'microfinance/individual/deactivate_individual/$1/$2';

$route['microfinance/savings-plan'] = 'microfinance/savings_plan/index';
$route['microfinance/savings-plan/(:any)/(:any)/(:num)'] = 'microfinance/savings_plan/index/$1/$2/$3';
$route['microfinance/add-savings-plan'] = 'microfinance/savings_plan/add_savings_plan';
$route['microfinance/edit-savings-plan/(:num)'] = 'microfinance/savings_plan/edit_savings_plan/$1';
$route['microfinance/edit-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/edit_savings_plan/$1/$2';
$route['microfinance/delete-savings-plan/(:num)'] = 'microfinance/savings_plan/delete_savings_plan/$1';
$route['microfinance/delete-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/delete_savings_plan/$1/$2';
$route['microfinance/activate-savings-plan/(:num)'] = 'microfinance/savings_plan/activate_savings_plan/$1';
$route['microfinance/activate-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/activate_savings_plan/$1/$2';
$route['microfinance/deactivate-savings-plan/(:num)'] = 'microfinance/savings_plan/deactivate_savings_plan/$1';
$route['microfinance/deactivate-savings-plan/(:num)/(:num)'] = 'microfinance/savings_plan/deactivate_savings_plan/$1/$2';

$route['microfinance/add-individual-plan/(:num)'] = 'microfinance/individual/add_individual_plan/$1';
$route['microfinance/activate-individual-plan/(:num)/(:num)'] = 'microfinance/individual/activate_individual_plan/$1/$2';
$route['microfinance/deactivate-individual-plan/(:num)/(:num)'] = 'microfinance/individual/deactivate_individual_plan/$1/$2';


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */