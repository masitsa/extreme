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

$route['default_controller'] = "site";
$route['404_override'] = '';

/*
*	Site Routes
*/
$route['home'] = 'site/home_page';
$route['about'] = 'site/about';
$route['contact'] = 'site/contacts';
$route['sell'] = 'site/sell_parts';
$route['terms'] = 'site/terms';
$route['privacy'] = 'site/privacy';
$route['search'] = 'site/search_parts';
$route['add-autopart'] = 'site/validate_product';
$route['view-autopart/(:num)'] = 'site/single_product/$1';

$route['mini-search'] = 'site/mini_search';

/*
*	Admin Blog Routes
*/
$route['posts'] = 'admin/blog';
$route['all-posts'] = 'admin/blog';
$route['blog-categories'] = 'admin/blog/categories';
$route['add-post'] = 'admin/blog/add_post';
$route['edit-post/(:num)'] = 'admin/blog/edit_post/$1';
$route['delete-post/(:num)'] = 'admin/blog/delete_post/$1';
$route['activate-post/(:num)'] = 'admin/blog/activate_post/$1';
$route['deactivate-post/(:num)'] = 'admin/blog/deactivate_post/$1';
$route['post-comments/(:num)'] = 'admin/blog/post_comments/$1';
$route['comments/(:num)'] = 'admin/blog/comments/$1';
$route['comments'] = 'admin/blog/comments';
$route['add-comment/(:num)'] = 'admin/blog/add_comment/$1';
$route['delete-comment/(:num)/(:num)'] = 'admin/blog/delete_comment/$1/$2';
$route['activate-comment/(:num)/(:num)'] = 'admin/blog/activate_comment/$1/$2';
$route['deactivate-comment/(:num)/(:num)'] = 'admin/blog/deactivate_comment/$1/$2';
$route['add-blog-category'] = 'admin/blog/add_blog_category';
$route['edit-blog-category/(:num)'] = 'admin/blog/edit_blog_category/$1';
$route['delete-blog-category/(:num)'] = 'admin/blog/delete_blog_category/$1';
$route['activate-blog-category/(:num)'] = 'admin/blog/activate_blog_category/$1';
$route['deactivate-blog-category/(:num)'] = 'admin/blog/deactivate_blog_category/$1';
$route['delete-comment/(:num)'] = 'admin/blog/delete_comment/$1';
$route['activate-comment/(:num)'] = 'admin/blog/activate_comment/$1';
$route['deactivate-comment/(:num)'] = 'admin/blog/deactivate_comment/$1';

/*
*	Login Routes
*/
$route['login-admin'] = 'admin/auth/login_admin';
$route['logout-admin'] = 'login/logout_admin';

/*
*	Settings Routes
*/
$route['settings'] = 'admin/settings';
$route['dashboard'] = 'admin/dashboard';

/*
*	Users Routes
*/
$route['admin/administrators'] = 'admin/users';
$route['admin/administrators/(:any)/(:any)/(:num)'] = 'admin/users/index/$1/$2/$3';
$route['add-user'] = 'admin/users/add_user';
$route['edit-user/(:num)'] = 'admin/users/edit_user/$1';
$route['delete-user/(:num)'] = 'admin/users/delete_user/$1';
$route['activate-user/(:num)'] = 'admin/users/activate_user/$1';
$route['deactivate-user/(:num)'] = 'admin/users/deactivate_user/$1';
$route['reset-user-password/(:num)'] = 'admin/users/reset_password/$1';
$route['admin-profile/(:num)'] = 'admin/users/admin_profile/$1';

/*
*	Customers Routes
*/
$route['all-customers'] = 'admin/customers';
$route['all-customers/(:num)'] = 'admin/customers/index/$1';
$route['add-customer'] = 'admin/customers/add_customer';
$route['edit-customer/(:num)'] = 'admin/customers/edit_customer/$1';
$route['delete-customer/(:num)'] = 'admin/customers/delete_customer/$1';
$route['activate-customer/(:num)'] = 'admin/customers/activate_customer/$1';
$route['deactivate-customer/(:num)'] = 'admin/customers/deactivate_customer/$1';

/*
*	Categories Routes
*/
$route['admin/categories'] = 'admin/categories/index';
$route['admin/categories/(:any)/(:any)/(:num)'] = 'admin/categories/index/$1/$2/$3';
$route['admin/add-category'] = 'admin/categories/add_category';
$route['admin/edit-category/(:num)'] = 'admin/categories/edit_category/$1';
$route['admin/edit-category/(:num)/(:num)'] = 'admin/categories/edit_category/$1/$2';
$route['admin/delete-category/(:num)'] = 'admin/categories/delete_category/$1';
$route['admin/delete-category/(:num)/(:num)'] = 'admin/categories/delete_category/$1/$2';
$route['admin/activate-category/(:num)'] = 'admin/categories/activate_category/$1';
$route['admin/activate-category/(:num)/(:num)'] = 'admin/categories/activate_category/$1/$2';
$route['admin/deactivate-category/(:num)'] = 'admin/categories/deactivate_category/$1';
$route['admin/deactivate-category/(:num)/(:num)'] = 'admin/categories/deactivate_category/$1/$2';

/*
*	Brands Routes
*/
$route['admin/all-brands'] = 'admin/brands/index';
$route['admin/all-brands/(:num)'] = 'admin/brands/index/$1';
$route['admin/add-brand'] = 'admin/brands/add_brand';
$route['admin/edit-brand/(:num)'] = 'admin/brands/edit_brand/$1';
$route['admin/edit-brand/(:num)/(:num)'] = 'admin/brands/edit_brand/$1/$2';
$route['admin/delete-brand/(:num)'] = 'admin/brands/delete_brand/$1';
$route['admin/delete-brand/(:num)/(:num)'] = 'admin/brands/delete_brand/$1/$2';
$route['admin/activate-brand/(:num)'] = 'admin/brands/activate_brand/$1';
$route['admin/activate-brand/(:num)/(:num)'] = 'admin/brands/activate_brand/$1/$2';
$route['admin/deactivate-brand/(:num)'] = 'admin/brands/deactivate_brand/$1';
$route['admin/deactivate-brand/(:num)/(:num)'] = 'admin/brands/deactivate_brand/$1/$2';

/*
*	Models Routes
*/
$route['admin/all-models'] = 'admin/brand_models/index';
$route['admin/all-models/(:num)'] = 'admin/brand_models/index/$1';
$route['admin/add-model'] = 'admin/brand_models/add_brand_model';
$route['admin/edit-model/(:num)'] = 'admin/brand_models/edit_brand_model/$1';
$route['admin/edit-model/(:num)/(:num)'] = 'admin/brand_models/edit_brand_model/$1/$2';
$route['admin/delete-model/(:num)'] = 'admin/brand_models/delete_brand_model/$1';
$route['admin/delete-model/(:num)/(:num)'] = 'admin/brand_models/delete_brand_model/$1/$2';
$route['admin/activate-model/(:num)'] = 'admin/brand_models/activate_brand_model/$1';
$route['admin/activate-model/(:num)/(:num)'] = 'admin/brand_models/activate_brand_model/$1/$2';
$route['admin/deactivate-model/(:num)'] = 'admin/brand_models/deactivate_brand_model/$1';
$route['admin/deactivate-model/(:num)/(:num)'] = 'admin/brand_models/deactivate_brand_model/$1/$2';

/*
*	other admin Routes
*/
$route['admin/company-profile'] = 'admin/contacts/show_contacts';

/*
*	Customer Routes
*/
$route['customer-registration'] = 'customer/auth/register_customer';
$route['facebook-registration'] = 'customer/auth/facebook_registration';
$route['customer-login'] = 'customer/auth/login_customer';

/*
*	Dobi Routes
*/
$route['dobi-registration'] = 'dobi/auth/dobi_details';
$route['register-location/(:any)'] = 'dobi/auth/dobi_location/$1';
$route['payment-details/(:any)'] = 'dobi/auth/payment_details/$1';
$route['dobi-login'] = 'dobi/auth/login_dobi';

/*
*	Blog Routes
*/
$route['blog'] = 'blog';
$route['blog/(:num)'] = 'blog/index/__/__/$1';//going to different page without any filters
$route['blog/category/(:any)'] = 'blog/index/$1';//category present
$route['blog/category/(:any)/(:num)'] = 'blog/index/$1/$2';//category present going to next page
$route['blog/search'] = 'blog/search';//search present
$route['blog/search/(:any)'] = 'blog/index/__/$1';//search present
$route['blog/search/(:any)/(:num)'] = 'blog/index/__/$1/$2';//search present going to next page
$route['blog/(:any)'] = 'blog/view_post/$1';//going to single post page

/* 404 */
//$route['(:any)'] = "site/not_found";
$route['category/(:any)'] = "site/not_found";
$route['brand/(:any)'] = "site/not_found";
$route['model/(:any)'] = "site/not_found";
$route['site/changes/create_tiny_url'] = "site/changes/create_tiny_url";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */