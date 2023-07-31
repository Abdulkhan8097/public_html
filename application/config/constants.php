<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/**--------------CUSTOM CONSTANTS STARTS---------------**/
$root  = "https://" . $_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
define('BASEURL', $root);
define('UPLOADPATH', BASEURL . "uploads/");
define('UPLOADPATH_ADMIN', BASEURL . "uploads/admin/");
define('ASSETSPATH', BASEURL . "assets/");
define('ADMIN_ASSETS', ASSETSPATH . "admin/");
define('ADMIN_LOGIN_ASSETS', ASSETSPATH . "login/");
define('FRONTEND_ASSETS', ASSETSPATH . "frontend/");
define('DEFAULT_CONTROLLER', "home"); //home page
//DATE TIME
date_default_timezone_set('Asia/Kolkata'); //SET TIMEZONE
$date_time_ymd = date('Y-m-d H:i:s');
$date_ymd = date('Y-m-d');
define('CURRENT_DATE_TIME_YMD', $date_time_ymd);
define('CURRENT_DATE_YMD', $date_ymd);


define('SITENAME', "Everest");
define('SITE_FULL_NAME', "Everest");
define('ADMIN_SITE_FULL_NAME', "Everest");
define('SITEURL', BASEURL.'index.php/');
define('FRONT_HOME_URL', SITEURL . "home/"); //Frontend home page
define('BLANK_IMG', BASEURL . "uploads/frontend/images/blank_user.png");
//ADMIN PROFILE
define('PROFILE_UPLOAD_PATH_NAME', FCPATH . "uploads/admin/images/user/"); //upload path
define('PROFILE_DISPLAY_PATH_NAME', BASEURL . "uploads/admin/images/user/"); // display path
//ADMIN SLIDER
define('CUSTOMER_UPLOAD_PATH_NAME', FCPATH . "uploads/admin/images/customers/"); //upload path
define('CUSTOMER_DISPLAY_PATH_NAME', BASEURL . "uploads/admin/images/customers/"); // display path


define('ADMIN_PARAMETER_LIST', SITEURL . "admin/ParameterDetails/index/"); //Frontend home page


/*SAM*/

define('ADMIN_CATEGORY_URL', SITEURL . "admin/category/index/"); //Frontend home page
define('ADMIN_SAVE_CATEGORY_URL', SITEURL . "admin/category/save_category/"); //Frontend home page
define('ADMIN_DELETE_CATEGORY_URL', SITEURL . "admin/category/delete_category/");
define('CATEGORY_STATUS_URL', SITEURL . "admin/category/updateStatus/");

define('ADMIN_DETAILS_CATEGORY_URL', SITEURL . "admin/category/getParameter/");
define('ADMIN_DETAILS_PRODUCT_URL', SITEURL . "admin/category/getProduct/");
define('ADMIN_DETAILS_MODEL_URL', SITEURL . "admin/model/getModelProduct/");
define('ADMIN_DETAILS_COMPTETOR_URL', SITEURL . "admin/CompPart/getCompProduct/");




/******ADMIN START*******/

// User Management
define('ADMIN_USER_URL', SITEURL . "admin/user/index/"); //Admin User page url
define('ADMIN_CREATE_USER_URL', SITEURL . "admin/user/userlist/"); //Admin User page url
define('ADMIN_SAVE_USER_URL', SITEURL . "admin/user/saveuser/"); //Admin User save url
define('ADMIN_USER_UPDATE_STATUS_URL', SITEURL . "admin/user/updatestatus/"); //Status


define('ADMIN_USER_EXPORT_STATUS_URL', SITEURL . "admin/user/createXLS/"); //Statu
define('USER_LOGIN', SITEURL . "admin/Login/checkuserlogin/"); //Statu


//Product Management

define('PRODUCT_STATUS_URL', SITEURL . "admin/product/updateStatus");
define('ADMIN_PRODUCT_URL', SITEURL . "admin/product/index"); //Admin User page url
define('ADMIN_ADD_PRODUCT_URL', SITEURL . "admin/product/addproduct/"); //Admin User page url
define('ADMIN_SAVE_PRODUCT_URL', SITEURL . "admin/product/saveProduct"); //Admin User page url

define('ADMIN_CHANGE_PRODUCT_URL', SITEURL . "admin/product/change"); //Admin User page url

//Add Model
define('ADMIN_ADD_MODEL_URL', SITEURL . "admin/addModel/index/"); //Admin User page url
define('ADMIN_SAVE_MODEL_URL', SITEURL . "admin/addModel/saveModel/"); //Admin User page url
define('ADMIN_CUSTOMER_GET_CITY_DLL_URL', SITEURL . "admin/addModel/getCityDLL/"); //Admin User page url
define('ADMIN_DELETE_MODAL_URL', SITEURL . "admin/addModel/delete_model/"); //Admin User page url


//Add Series
define('ADMIN_ADD_SERIES_URL', SITEURL . "admin/Add_competitor/index/"); //Admin User page url
define('ADMIN_SAVE_SERIES_URL', SITEURL . "admin/addSeries/saveSeries/"); //Admin User page url
define('ADMIN_CUSTOMER_GET_CITY_DL_URL', SITEURL . "admin/addSeries/getCityDL/"); //Admin User page url
define('ADMIN_DELETE_ASERIES_URL', SITEURL . "admin/Add_competitor/delete_series/");


//Add Competitor
define('ADMIN_ADD_COMPTITOR_URL', SITEURL . "admin/addComp/index/"); //Admin User page url
define('ADMIN_SAVE_COMPTITOR_URL', SITEURL . "admin/addComp/saveComp/"); //Admin User page url
define('ADMIN_CUSTOMER_GET_CITY_URL', SITEURL . "admin/addComp/getCity/"); //Admin User page url
define('ADMIN_CUSTOMER_GET_URL', SITEURL . "admin/addComp/delete_comp/"); //Admin User page url

// Comp Part Numbre
define('ADMIN_ADD_COMPTITOR_PART_URL', SITEURL . "admin/CompPart/index/");
define('ADMIN_ADD_SAVE_PART_URL', SITEURL . "admin/CompPart/saveCompPart/");
define('ADMIN_DELETE_PART_URL', SITEURL . "admin/CompPart/deletepart/");


//Add Vechicle
define('ADMIN_ADD_VEHICLE_URL', SITEURL . "admin/vechicle/index/"); //Admin User page url
define('ADMIN_SAVE_VECHICLE_URL', SITEURL . "admin/vechicle/saveVechicle/"); //Admin User page url
define('ADMIN_SAVE_DELETE_URL', SITEURL . "admin/vechicle/delete_vechicle/"); //Admin User page url

//banner
define('ADMIN_USER_UPDATE_STTUS_URL', SITEURL . "admin/banner/updatebanner/"); //Status

//ratnesh
//series//

define('ADMIN_SERIES_URL', SITEURL . "admin/series/index/"); //Frontend home page 7th aug
define('ADMIN_SAVES_SERIES_URL', SITEURL . "admin/series/saveSeries/"); //Frontend home page
define('ADMIN_DELETE_SERIES_URL', SITEURL . "admin/series/delete_series/"); //Frontend home page


//competitor discount//



define('ADMIN_COMP_URL', SITEURL . "admin/comp/index/"); //Frontend home page 7th aug
define('ADMIN_SAVE_COMP_URL', SITEURL . "admin/comp/saveComp/"); //Frontend home page
define('ADMIN_DELETE_COMP_URL', SITEURL . "admin/comp/delete_comp/"); //Frontend home page

define('ADMIN_ADD_COMP_URL', SITEURL . "admin/CompPart/index/"); //Frontend home page
define('ADMIN_SAVE_COMPS_URL', SITEURL . "admin/CompPart/saveComp/"); //Admin User page url




//retail list


define('ADMIN_RETAIL_URL', SITEURL . "admin/retail/index/");
define('ADMIN_SAVE_RETAIL_URL', SITEURL . "admin/retail/saveRetail/");
define('ADMIN_DELETE_RETAIL_URL', SITEURL . "admin/retail/delete_retail/");

//download
define('ADMIN_DOWNLOAD_URL', SITEURL . "admin/download/index/");
define('ADMIN_SAVE_DOWNLOAD_URL', SITEURL . "admin/download/saveDownload/");
define('ADMIN_DELETE_DOWNLOAD_URL', SITEURL . "admin/download/delete_download/");
define('ADMIN_DOWNLOADS_STATUS_URL', SITEURL . "admin/download/updateStatus/"); //Status

//parameter search
define('ADMIN_PARAMETER_URL', SITEURL . "admin/parameter/index/");
define('ADMIN_SAVE_PARAMETER_URL', SITEURL . "admin/parameter/saveParameter/");
define('ADMIN_DELETE_PARAMETER_URL', SITEURL . "admin/parameter/delete_parameter/");

//End ratnesh

//akash yadav

define('ADMIN_SAVE_MODAL_URL', SITEURL . "admin/model/saveModel/"); //Frontend home page

define('ADMIN_DELETE_MODEL_URL', SITEURL . "admin/model/deleteModel/"); //Frontend home page

// define('ADMIN_SERIES_URL', SITEURL . "admin/model/index/"); //Frontend home pag

define('ADMIN_MODEL_URL', SITEURL . "admin/model/index/"); //Frontend home page


define('ADMIN_DELETE_COMPETITOR_URL', SITEURL . "admin/competitor/deleteCompetitor/"); //Frontend home page
define('ADMIN_SAVE_COMPETITOR_URL', SITEURL . "admin/competitor/saveCompetitor/"); //Frontend home page
define('ADMIN_COMPETITOR_URL', SITEURL . "admin/competitor/index/"); //Frontend home page



define('ADMIN_DISCOUNT_URL', SITEURL . "admin/discount/index/"); //Frontend home page
define('ADMIN_DELETE_DISCOUNT_URL', SITEURL . "admin/discount/deleteDiscount/"); //Frontend home page
define('ADMIN_SAVE_DISCOUNT_URL', SITEURL . "admin/discount/saveDiscount/"); //Frontend home page


define('ADMIN_SALE_URL', SITEURL . "admin/sale/index/"); //Frontend home page
define('ADMIN_DELETE_SALE_URL', SITEURL . "admin/sale/deletesale/"); //Frontend home page
define('ADMIN_SAVE_SALE_URL', SITEURL . "admin/sale/save_sale/"); //Frontend home page

define('ADMIN_BANNER_URL', SITEURL . "admin/banner/index/"); //Frontend home page
define('ADMIN_DELETE_BANNER_URL', SITEURL . "admin/banner/deletebanner/"); //Frontend home page
define('ADMIN_SAVE_BANNER_URL', SITEURL . "admin/banner/savebanner/"); //Frontend home pag
// Loan Products
define('ADMIN_LOAN_URL', SITEURL . "admin/loan/index/"); //Admin loan product page url





define('ADMIN_CUSTOMER_LIST_URL', SITEURL . "admin/customer/"); //Admin customer page url
define('ADMIN_ADD_CUSTOMER_URL', SITEURL . "admin/customer/addCustomer/"); //Admin add customer page url
define('ADMIN_CUSTOMER_SAVE_URL', SITEURL . "admin/customer/saveCustomer/"); //Admin add customer page url
define('ADMIN_CUSTOMER_DELETE_URL', SITEURL . "admin/customer/deleteCustomer/"); //Admin add customer page url

//Assign Model
define('ADMIN_ASSIGN_MODEL_URL', SITEURL . "admin/AssignModel/index/"); //Admin customer page url
define('ADMIN_ASIGN_MODEL_DLL_URL', SITEURL . "admin/AssignModel/getCity/");
define('ADMIN_ASIGN_MODEL_SAVE_URL', SITEURL . "admin/AssignModel/saveAssign/");
define('ADMIN_ASIGN_MODEL_DELETE_URL', SITEURL . "admin/AssignModel/delete_model/");
define('ADMIN_ASIGN_MODEL_COUNT_URL', SITEURL . "admin/AssignModel/getCount/");







/******ADMIN END*********/


/******FRONTEND START*******/

/******ADMIN START*******/


define('SITE_AUTHOR', "");



define('ADMIN_DASHBOARD_URL', SITEURL . "admin/dashboard");
define('LOGOUT_URL', SITEURL . "admin/login/logout");
define('ADMIN_PROFILE_DETAILS_URL', SITEURL . "admin/users/profile_details");
define('ADMIN_PROFILE_DETAILS_SUBMIT_URL', SITEURL . "admin/users/save_profile_details");




define('ADMIN_CHANGE_PASSWORD_DETAILS_URL', SITEURL . "admin/users/change_password");
define('ADMIN_CHANGE_PASSWORD_DETAILS_SUBMIT_URL', SITEURL . "admin/users/saveProfileCngPassword");
define('ADMIN_LOGIN_URL', SITEURL . "admin/login");
define('ADMIN_FORGET_PASSWORD_DETAILS_URL', SITEURL . "admin/login/forgot_password");
define('ADMIN_FORGET_PASSWORD_DETAILS_SUBMIT_URL', SITEURL . "admin/login/saveForgotPassword");
define('ADMIN_OTP_VERIFICATION_DETAILS_URL', SITEURL . "admin/login/otp_verification");
define('ADMIN_OTP_VERIFICATION_DETAILS_SUBMIT_URL', SITEURL . "admin/login/saveOtpVerification");
define('ADMIN_OTP_CONFIRM_PASSWORD_DETAILS_URL', SITEURL . "admin/login/confirm_password");
define('ADMIN_CONFIRM_PASSWORD_DETAILS_SUBMIT_URL', SITEURL . "admin/login/saveConfirmPassword");
// define('ADMIN_INQUIRY_LIST',SITEURL."admin/inquiry/inquiry_list");

// define('DEFAULT_SMTP_USER_EMAIL_ID',"bipin521999@gmail.com");//from
// define('DEFAULT_SMTP_USER_PASSWORD',"7774920260"); 
// define('DEFAULT_INQUIRY_MAIL',"bipin521999@gmail.com"); //to

define('DEFAULT_SMTP_USER_EMAIL_ID',"test007atul@gmail.com");//from
define('DEFAULT_SMTP_USER_PASSWORD',"@test123");  
define('DEFAULT_INQUIRY_MAIL',"webdev.atul007@gmail.com"); //to

// stock management
define('ADMIN_Stock_CHECK_LIST_URL', SITEURL . "admin/Add_stock/index/"); //Admin adjustment page url
define('ADMIN_Stock_Adjustment_LIST_URL', SITEURL . "admin/Stock_Adjustment/index/"); //Admin adjustment page url
define('ADMIN_SAVE_Stock_Adjustmen_URL', SITEURL . "admin/competitor/saveCompetitor/"); //Frontend home page



// checked data
define('ADMIN_CHECKED_DATA_URL', SITEURL . "admin/Stock_Adjustment/getRecourd/");

// model category

define('CATEGORY_MODEL_URL', SITEURL . "admin/Model_category/index/");
define('CATEGORY_MODEL_SAVE_URL', SITEURL . "admin/Model_category/save_model_category/");
define('CATEGORY_MODEL_DELETE_URL', SITEURL . "admin/Model_category/delete_data/");
define('CATEGORY_MODEL_STATUS_URL', SITEURL . "admin/Model_category/updateStatus/");





define('ADMIN_SERIES_CATEGORY_URL', SITEURL . "admin/series_category/index/"); //Frontend home page 7th aug
define('ADMIN_SAVES_SERIES_CATEGORY_URL', SITEURL . "admin/series_category/saveSeriescategory/"); //Frontend home page
define('ADMIN_DELETE_SERIES_CATEGORY_URL', SITEURL . "admin/series_category/delete_seriescategory/"); //Frontend home page
define('ADMIN_STATUS_SERIES_UPDATE_CATEGORY_URL', SITEURL . "admin/series_category/updateStatus/");


/******ADMIN END******/

/**--------------CUSTOM CONSTANTS END---------------**/