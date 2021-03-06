<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// routes api
$route['login'] = 'login_controller/login';

// routes edit perfil
$route['profile/edit'] = 'perfil_controller/profile_edit';

//routes users
$route['user'] = 'users_controller/user';
$route['users'] = 'users_controller/users';
$route['users/(:any)'] = 'users_controller/users/$1';

//routes groups
$route['group'] = 'groups_controller/group';
$route['groups'] = 'groups_controller/groups';
$route['groups/(:any)'] = 'groups_controller/groups/$1';

//routes categories
$route['fills'] = 'categories_controller/fills';
$route['category'] = 'categories_controller/category';
$route['categories'] = 'categories_controller/categories';
$route['categories/(:any)'] = 'categories_controller/categories/$1';

//routes tags
$route['tag'] = 'tags_controller/tag';
$route['tags'] = 'tags_controller/tags';
$route['tags/(:any)'] = 'tags_controller/tags/$1';

//routes recursos
$route['recurs'] = 'recursos_controller/recurs';
$route['recursos'] = 'recursos_controller/recursos';
$route['recursos/(:any)'] = 'recursos_controller/recursos/$1';
$route['recursosCat'] = 'recursos_controller/recursosCat';
$route['recursosProfe'] = 'recursos_controller/recursosProfe';

// routes favoritos
$route['favoritos'] = 'favoritos_controller/favoritos';

// routes files
$route['files/(:any)'] = 'files_controller/getFile/$1';