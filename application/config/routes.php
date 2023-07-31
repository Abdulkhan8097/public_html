<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] 	= DEFAULT_CONTROLLER;
$route['404_override'] 			= '';
$route['translate_uri_dashes'] 	= FALSE;


// $route['vfs-subcategorymenus-loan/(:any)(:any)/(:any)'] = 'loan/getSub_category_menu/$1/$2/$3';

$route['vfs-loans/(:any)/(:any)'] 	= 'loan/Property_Loan_Details/$1/$2';

$route['vfs-loan/(:any)'] 			= 'loan/Unsecured_Loan_Details/$1';



// $route['vfs-insurance'] = 'loan/Insurance_Details';


$route['about-vfs'] 			= 'about_us/about_us_details';
$route['vfs-team'] 				= 'team/team_details';
$route['vfs-privacy-policy'] 	= 'Privacy/index';
$route['vfs-terms-of-use'] 		= 'Terms/index';
$route['vfs-blogs'] 			= 'blog/blogs_details';
$route['vfs-blog'] 				= 'blog/blog_details';
$route['contact-vfs'] 			= 'contact_us/contact_us_details';
$route['vfs-faq'] 				= 'faq/faq_details';
$route['vfs-testimonial'] 		= 'testimonial/testimonial_details';
$route['vfs-gallery'] 			= 'gallery/gallery_details';
$route['vfs-loan-calculator'] 	= 'loan_calculator/loan_calculator_details';