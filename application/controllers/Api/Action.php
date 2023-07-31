<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Kolkata");
header("Access-Control-Allow-Origin: *");

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Action extends REST_Controller
{

    private $allowed_img_types;
    private $shopID;

	var $verandroid="1022";
	var $vernameandroid="1.0.22";

	var $verios="1025";
	var $vernameios="1.0.25";

    function __construct()
    {
        parent::__construct();
		$this->load->library('user_agent');

		$this->methods['versioncheck_get']['limit'] = 500; 

        $this->methods['otp_login_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['otp_forgot_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['otp_forgotget_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_login_post']['limit'] = 500; // 500 requests per hour per user/key
		
		$this->methods['product_details_get']['limit'] = 500; 
		$this->methods['product_partno_get']['limit'] = 500; 
		$this->methods['competitor_partno_get']['limit'] = 500; 
		$this->methods['product_series_get']['limit'] = 500; 
		$this->methods['product_compt_get']['limit'] = 500; 
		$this->methods['product_brand_get']['limit'] = 500; 
		$this->methods['product_model_get']['limit'] = 500; 
		$this->methods['product_modelbrand_get']['limit'] = 500; 
		$this->methods['product_upcoming_get']['limit'] = 500; 
		$this->methods['product_newlaunch_get']['limit'] = 500; 
		$this->methods['download_get']['limit'] = 500; 
        $this->methods['contact_post']['limit'] = 500; // 500 requests per hour per user/key
		
		$this->methods['product_category_get']['limit'] = 500; 
		$this->methods['product_category_par_get']['limit'] = 500; 
		$this->methods['product_category_value_post']['limit'] = 500; 

		$this->methods['product_category_par2_post']['limit'] = 500; 
		$this->methods['product_category_value2_post']['limit'] = 500; 

		$this->methods['banner_get']['limit'] = 500; 
		$this->methods['banner2_get']['limit'] = 500; 
		
		$this->methods['padd_post']['limit'] = 500; 
		$this->methods['pupdate_post']['limit'] = 500; 
		$this->methods['pdel_post']['limit'] = 500; 
		$this->methods['padd_search_post']['limit'] = 500; 

		$this->methods['plist_post']['limit'] = 500; 
		$this->methods['order_post']['limit'] = 500; 
        $this->methods['outstanding_list_get']['limit'] = 500; 
        $this->methods['invoice1_list_get']['limit'] = 500; 
        $this->methods['order_list_post']['limit'] = 500; 
		$this->methods['series_get']['limit'] = 500; 
		$this->methods['change_pass_post']['limit'] = 500; 
		$this->methods['coststru_post']['limit'] = 500; 
		$this->methods['getdefaultcost_post']['limit'] = 500; 
		$this->methods['setcost_post']['limit'] = 500; 
		$this->methods['setcost2_post']['limit'] = 500; 
		$this->methods['setcostAll_post']['limit'] = 500; 
		$this->methods['getpartcompet_post']['limit'] = 500; 
		$this->methods['part_get']['limit'] = 500; 
		$this->methods['invlist_post']['limit'] = 500; 
		$this->methods['invaddupdate_post']['limit'] = 500; 
		$this->methods['comparecheck_post']['limit'] = 500; 


        $this->load->model(array('Api_model'));
        $this->allowed_img_types = $this->config->item('allowed_img_types');

		#print_r($this->methods);exit;
		$shopID= $this->input->get_post('shopID');
		$key= $this->input->get_post('key');
		$this->shopID = $shopID;
		#print $key;exit;
		
		#$this->Api_model->checklogin($shopID,$key);
		#print $this->db->last_query() . "\n\n\n";exit;
    }

	public function versioncheck_get()
	{
		$device_platform = $this->input->get_post('device_platform');
		$version = $this->input->get_post('version');
		$update= 'no';
		//$RowInfo = $this->checkvalidsession2($session);
		$ver = $vername = '';
		
		if($device_platform == 'Android')
		{
		
			#print "ver: $version * this->verandroid: " . $this->verandroid . "";
			if($version < $this->verandroid)
			{
				$ver= $this->verandroid;
				$vername= $this->vernameandroid;
				$update= 'yes';
			}
		}
		if($device_platform == 'iOS')
		{
			if($version < $this->verios)
			{
				$ver= $this->verios;
				$vername= $this->vernameios;
				$update= 'yes';
			}
		}

		$status = 'success';
		$mydata = array();
		$message = $message2 = '';
		$status_code = '100';
		
		if($update == 'yes')
		{
			$message = "Please update to Latest version " . $vername . "";
		}
		
		#$status_code = '500';	

		$mydata['status'] = $status;
		$mydata['message'] = $message;
		#$mydata['message2'] = $message2;
		$mydata['update'] = $update;
		$mydata['version'] = $ver;
		$mydata['status_code'] = $status_code;
		$mydata['version_name'] = $vername;
					  
		print json_encode($mydata);
	}
	
    public function otp_login_post($lang ='en')
    {	
		$mobile= $this->input->get_post('mobile');
		$pass= $this->input->get_post('pass');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'Mobile No is empty';
        }	
		if (!isset($_REQUEST['pass']) || empty($_REQUEST['pass'])) {
            $errors[] = 'pass is empty';
        }		
		#phpinfo();exit;
		$data = array();
		$data['mobile'] = $mobile;
		$data['pass'] = $pass;
		#print_r($data);exit;
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			#print "1";exit;
			$users = $this->Api_model->user_details($data);
			#print $this->db->last_query() . "\n\n\n";
			#print $users;exit;
			if(@$users =='0')
			{
				$this->response(['status' => FALSE,'message' => 'Password not matching'], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

			}else{
				if ($users) 
				{
					#print_r($info2);exit;
					$this->response([
					'status' => TRUE,'message' =>  'OTP Sent', "data" => @$users ]
					, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}else {
				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => 'No users were found'
						], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
				}
			}
			
     		
        }
	}
	
	public function otp_forgot_post($lang ='en')
    {	
		$mobile= $this->input->get_post('mobile');
		#$otp= $this->input->get_post('otp');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'Mobile No is empty';
        }	
		#if (!isset($_REQUEST['otp']) || empty($_REQUEST['otp'])) {
        #    $errors[] = 'otp is empty';
        #}		
		#phpinfo();exit;
		$data = array();
		$data['mobile'] = $mobile;
		#$data['otp'] = $otp;
		#print_r($data);exit;
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$users = $this->Api_model->user_details_forgot($data);
			#print $this->db->last_query() . "\n\n\n";
			if ($users) 
			{
				#print_r($info2);exit;
				$this->response([
                'status' => TRUE,'message' =>  'OTP Sent', "data" => @$users ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid Mobile No'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
    public function otp_forgotget_post($lang ='en')
    {	
		$mobile= $this->input->get_post('mobile');
		$otp= $this->input->get_post('otp');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'Mobile No is empty';
        }	
		if (!isset($_REQUEST['otp']) || empty($_REQUEST['otp'])) {
            $errors[] = 'otp is empty';
        }		
		#phpinfo();exit;
		$data = array();
		$data['mobile'] = $mobile;
		$data['otp'] = $otp;
		#print_r($data);exit;
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$users = $this->Api_model->user_details_forgotget($data);
			#print $this->db->last_query() . "\n\n\n";
			if ($users) 
			{
				#print_r($info2);exit;
				$this->response([
                'status' => TRUE,'message' =>  'Password Sent', "data" => @$users ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'OTP not matched'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
    public function user_login_post($lang ='en')
    {	
		$mobile= $this->input->get_post('mobile');
		$pass= $this->input->get_post('pass');
		$otp= $this->input->get_post('otp');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'Mobile No is empty';
        }	
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile is empty';
        }		
		if (!isset($_REQUEST['otp']) || empty($_REQUEST['otp'])) {
            $errors[] = 'otp is empty';
        }		
		#phpinfo();exit;
		$data = array();
		$data['mobile'] = $mobile;
		$data['pass'] = $pass;
		$data['otp'] = $otp;
		#print_r($data);exit;
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$users = $this->Api_model->user_login($data);
			#print $this->db->last_query() . "\n\n\n";
			if ($users) 
			{
				#print_r($info2);exit;
				$this->response([
                'status' => TRUE,'message' =>  'Login Done', "data" => @$users ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No users(OTP) were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function product_details_get($lang ='en')
    {	
		$product_id= $this->input->get_post('product_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		if($product_id == 'null') $product_id = '';
		$errors = [];
		if (!isset($_REQUEST['product_id']) || empty($product_id)) {
            $errors[] = 'product_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		
		
		#print $product_id;exit;
		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['product_id'] = $product_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_details($data);
            $related = $this->Api_model->product_detailsrelated($data);
            $banner = $this->banner2_get("Product",$mobile, $session);
			
			#print_r($info);
			$category_id = $info[0]['category_id'];
			#print $category_id;exit;
            $fields = $this->fields_get($category_id,$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info, 'related' => $related, 'banner' => $banner, 'fields' => $fields, 'showdis' =>'Y' ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	
	public function product_partno_get($lang ='en')
    {	
		$partno= $this->input->get_post('partno');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['partno']) || empty($_REQUEST['partno'])) {
            $errors[] = 'partno No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['partno'] = $partno;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_parts($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	

	public function competitor_partno_get($lang ='en')
    {	
		$partno= $this->input->get_post('partno');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['partno']) || empty($_REQUEST['partno'])) {
            $errors[] = 'partno No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['partno'] = $partno;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_cparts($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	
	public function product_series_get($lang ='en')
    {	
		$series= $this->input->get_post('series');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['series']) || empty($_REQUEST['series'])) {
            $errors[] = 'series No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['series'] = $series;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_series($data);
			$banner = $this->banner2_get("Series",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	
	public function product_brand_get($lang ='en')
    {	
		#$series= $this->input->get_post('series');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['series']) || empty($_REQUEST['series'])) {
        #    $errors[] = 'series No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['series'] = $series;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_brand($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' brand were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No Brand were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	

	public function product_model_get($lang ='en')
    {	
		$vm_id= $this->input->get_post('vm_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['vm_id']) || empty($_REQUEST['vm_id'])) {
            $errors[] = 'vm_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['vm_id'] = $vm_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_model($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' model were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No model were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}		

	public function product_modelbrand_get($lang ='en')
    {	
		$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
            $errors[] = 'model_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_modelbrand($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	
	public function product_upcoming_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_upcoming($data, 1);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	
	public function product_newlaunch_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_upcoming($data, 2);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function download_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->downloads($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' files were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No files were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function product_compt_get($lang ='en')
    {	
		$comppartno= $this->input->get_post('comppartno');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['comppartno']) || empty($_REQUEST['comppartno'])) {
            $errors[] = 'competitor part No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['comppartno'] = $comppartno;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_competitor($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' products were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No products were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	

    public function contact_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$name= $this->input->get_post('name');
		$emailid= $this->input->get_post('emailid');
		$phoneno= $this->input->get_post('phoneno');
		$message= $this->input->get_post('message');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		if (!isset($_POST['name']) || empty($_POST['name'])) {
            $errors[] = 'First name is empty';
        }
		if (!isset($_POST['emailid']) || empty($_POST['emailid'])) {
            $errors[] = 'E-mail ID is empty';
        }
		if (!isset($_POST['phoneno']) || empty($_POST['phoneno'])) {
            $errors[] = 'Mobile No is empty';
        }
		$data = array();
		$data['name'] = $name;
		$data['emailid'] = $emailid;
		$data['mobile'] = $phoneno;
		$data['comments'] = $message;
		$data['mobileno'] = $mobile;
		$data['ip'] = $ip;
		#$data['browser'] = $browser;
		$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				
				$this->Api_model->setData('contacts', $data);
				$message = ['status' => TRUE,'message' => 'Added a contact'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	

	public function product_category_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_category($data);
			$banner = $this->banner2_get("Category",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' category were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No category were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}

	public function product_category_par_get($lang ='en')
    {	
		$lastcount= @$this->input->get_post('lastcount');
		$category_id= $this->input->get_post('category_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            $errors[] = 'category_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['category_id'] = $category_id;
		$data['lastcount'] = $lastcount;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_category_par($data);
			#$banner = $this->banner2_get("Category",$mobile, $session);
			$banner = array();
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  $info['pcount'] .' products were found','data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No parameter were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}

	public function product_category_value_post($lang ='en')
    {	
		$category_id= $this->input->get_post('category_id');
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$par= $this->input->get_post('par');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            $errors[] = 'category_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['category_id'] = $category_id;
		#$data['field'] = $field;
		#$data['val'] = $val;
		$data['par'] = $par;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_category_value($data);
			$banner = $this->banner2_get("Category",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info['products']) .' product were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No parameter were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function product_category_par2_post($lang ='en')
    {	
		$category_id= $this->input->get_post('category_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            $errors[] = 'category_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['category_id'] = $category_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_category_par2($data);
			$banner = $this->banner2_get("Category",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' parameter were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No parameter were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}

	public function product_category_value2_post($lang ='en')
    {	
		$category_id= $this->input->get_post('category_id');
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$par= $this->input->get_post('par');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            $errors[] = 'category_id No is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['category_id'] = $category_id;
		#$data['field'] = $field;
		#$data['val'] = $val;
		$data['par'] = $par;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->product_category_value2($data);
			$banner = $this->banner2_get("Category",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' parameter were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No parameter were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
		
	public function banner_get($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$type= $this->input->get_post('type');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['type']) || empty($_REQUEST['type'])) {
            $errors[] = 'type is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['type'] = $type;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->banner_value($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' banner were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No banner were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function banner2_get($type, $mobile, $session)
    {	
		$errors = [];
		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['type'] = $type;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->banner_value($data);
			return $info;
     		
        }
	}
	
	public function fields_get($category_id, $mobile, $session)
    {	
		$errors = [];
		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['type'] = $type;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			#print "hi $category_id";
			
			$this->db->select('*');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where_in('display_details', 'yes');
			$this->db->where('category_id', $category_id, FALSE);
			$this->db->order_by("display_details_order,parameter_name");
			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('parameter_search', 1000);
			
			return $result->result();
			#print $result->num_rows();exit;
			#print $this->db->last_query();exit;
			
			
            #$info = $this->Api_model->banner_value($data);
			#return $info;
     		
        }
	}	
	
	public function padd_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$pid= $this->input->get_post('pid');
		$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['pid']) || empty($_REQUEST['pid'])) {
            $errors[] = 'pid is empty';
        }				
		if (!isset($_REQUEST['qty']) || empty($_REQUEST['qty'])) {
            $errors[] = 'qty is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		$data['pid'] = $pid;
		$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $a = $this->db->query("select * from tmp_product where mobile='$mobile' and session='$session' and pid=$pid")->result();
			#print sizeof($a);exit;
			#print_r($a);exit;
			#print $this->db->last_query();exit;
			if(sizeof($a)==0)
			{
				$info = $this->Api_model->setData('tmp_product', $data);
				$this->response(['status' => TRUE,'message' =>  ' Product added' ], REST_Controller::HTTP_OK); 
				#print $info;exit;
				#print $this->db->last_query();exit;
			}else{
				
				$info = $this->Api_model->UpdateData('tmp_product', $data, array('mobile'=>$mobile,'session'=>$session,'pid'=>$pid));
				#print $this->db->last_query();exit;
				$this->response(['status' => TRUE,'message' =>  ' updated' ], REST_Controller::HTTP_OK); 
			}
     		#print $this->db->last_query();exit;
        }
	}

	public function pdel_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$pid= $this->input->get_post('pid');
		#$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['pid']) || empty($_REQUEST['pid'])) {
            $errors[] = 'pid is empty';
        }				
		#if (!isset($_REQUEST['qty']) || empty($_REQUEST['qty'])) {
        #    $errors[] = 'qty is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		$data['pid'] = $pid;
		#$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $a = $this->db->query("delete from tmp_product where mobile='$mobile' and session='$session' and pid=$pid");
			
			$this->response(['status' => TRUE,'message' =>  ' deleted' ], REST_Controller::HTTP_OK); 
			
     		#print $this->db->last_query();exit;
        }
	}

	public function padd_search_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$partno= $this->input->get_post('partno');
		$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['partno']) || empty($_REQUEST['partno'])) {
            $errors[] = 'partno is empty';
        }				
		if (!isset($_REQUEST['qty']) || empty($_REQUEST['qty'])) {
            $errors[] = 'qty is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		#$data['pid'] = $pid;
		$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			
			$pid =0;
			$a = $this->db->query("select * from product where eve_part_no='$partno' ")->result();
			foreach($a as $row1)
			{
				$pid =  $row1->product_id;
			}
			
			if(empty($pid))
			{
				$this->response(['status' => FALSE,'message' =>  ' product not found' ], REST_Controller::HTTP_OK); 
			}else{
				$data['pid'] = $pid;
				$b = $this->db->query("select * from tmp_product where mobile='$mobile' and session='$session' and pid=$pid")->result();
				#print sizeof($a);exit;
				#print_r($data);exit;
				#print $this->db->last_query();exit;
				if(sizeof($b)==0)
				{
					$info = $this->Api_model->setData('tmp_product', $data);
					$this->response(['status' => TRUE,'message' =>  ' added' ], REST_Controller::HTTP_OK); 
					#print $info;exit;
					#print $this->db->last_query();exit;
				}else{
					
					$info = $this->Api_model->UpdateData('tmp_product', $data, array('mobile'=>$mobile,'session'=>$session,'pid'=>$pid));
					#print $this->db->last_query();exit;
					$this->response(['status' => TRUE,'message' =>  ' updated' ], REST_Controller::HTTP_OK); 
				}
			}
     		#print $this->db->last_query();exit;
        }
	}

	public function plist_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		#$partno= $this->input->get_post('partno');
		#$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
				
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		#$data['pid'] = $pid;
		#$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			
		$result = $this->db->query("select product_id, eve_part_no, profile_img, qty from tmp_product inner join product on product.product_id=tmp_product.pid where mobile='$mobile' and session='$session' ");
						$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);exit;
			
			
			if(empty($darry))
			{
				$this->response(['status' => FALSE,'message' =>  ' list empty' ], REST_Controller::HTTP_OK); 
			}else{
				#$data['pid'] = $pid;
				#print sizeof($a);exit;
				#print_r($data);exit;
				#print $this->db->last_query();exit;
				
				#print $this->db->last_query();exit;
				$this->response(['status' => TRUE,'message' =>  ' list','data' => $darry ], REST_Controller::HTTP_OK); 
			
			}
     		#print $this->db->last_query();exit;
        }
	}

    public function order_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$products= $this->input->get_post('products');
		$name= $this->input->get_post('name');
		$emailid= $this->input->get_post('emailid');
		#$phoneno= $this->input->get_post('phoneno');
		$message= $this->input->get_post('message');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		#if (!isset($_POST['name']) || empty($_POST['name'])) {
        #    $errors[] = 'First name is empty';
        #}
		#if (!isset($_POST['emailid']) || empty($_POST['emailid'])) {
        #    $errors[] = 'E-mail ID is empty';
        #}
		if (!isset($_POST['products']) || empty($_POST['products'])) {
            $errors[] = 'products is empty';
        }
		/*
		$data = array();
		$data['name'] = $name;
		$data['emailid'] = $emailid;
		$data['comments'] = $message;
		$data['mobileno'] = $mobile;
		$data['products'] = $products;
		$data['ip'] = $ip;
		#$data['browser'] = $browser;
		$data['datec'] = $datec;
		*/
		
		$pp = json_decode($products, false);
		#print_r($pp);
		$products ='';
		$total_amount = $disc_amount = $gst_amount = 0;
		//discount
		
		$discount = 0;
		$query= $this->Api_model->_usersessioncheck($session, $mobile);
		#print_r($query);exit;

		if(sizeof($query->result_array()) != 0)//session found
		{
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;	
		}	
		//print "discount: $discount";exit;
		
		$this->db->select('*');
		$this->db->where('phone', $mobile, FALSE);
		$result0 = $this->db->get('user', 1000);
		
		#print $result->num_rows();exit;
		#print $this->db->last_query();exit;
		if($result0->num_rows() == 0)
		{
			$name ="";
		}else{
			$name = $result0->result()[0]->first_name . ' ' . $result0->result()[0]->last_name;
		}
		
		$body = '';
		foreach($pp as $p)
		{
			$pid = $p->product_id;
			$qty = $p->qty;
			#print $pid . '<br>';
			#$products .= ", " . $pid;
			
			$this->db->select('product_id, mrp, gst');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			#$this->db->where_in('product_id', $products, FALSE);
			$this->db->where('product_id', $pid, FALSE);
			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', 1000);
			
			#print $result->num_rows();exit;
			#print $this->db->last_query();exit;
			if($result->num_rows() == 0)
			{
				 $message = ['status' => FALSE,'message' => "Product $pid not found"];
				 $this->set_response($message, REST_Controller::HTTP_CREATED);exit();
			}else{
				$mrp = $result->result()[0]->mrp;
				$gst = $result->result()[0]->gst;
				#print $mrp;exit;
				
				//print "$mrp *  $discount)/100 ";
				$disc_amount = round(($mrp *  $discount * $qty)/100,0);
				$lp = ($mrp - round(($mrp * $discount/100),0));
				#print $lp;exit;
				$gst_amount1 = round(($lp * $gst/100),0);
				$gst_amount = $gst_amount + ($gst_amount1 * $qty);
				//$total_amount = $total_amount + ($mrp *  $qty);
				$total_amount = $total_amount + (($lp +  $gst_amount1) * $qty);

			}
		}
		#$products = substr($products,2);

		#print $result->num_rows();exit;
		$data = array();
		$data['order_date'] = @$datec;
		$data['order_by'] = @$mobile;
		$data['disc_per1'] = @$discount;
		$data['disc_amount'] = @$disc_amount;
		$data['gst_amount'] = @$gst_amount;
		$data['total_amount'] = @$total_amount;
		$data['emailid'] = @$emailid;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				
				#$this->Api_model->setData('enquiry', $data);
				$order_id = $this->Api_model->setData('order_master', $data);
				
				$body .="<center>Name: $name<br>Mobile: $mobile<br> <h1>Order Details</h1><table border=\"1\" colspacing=\"2\" cellspacing=\"0\" style=\"width:80%\">\n";
				$body .="<tr><th>Code</th><th>Qty</th><th>MRP</th><th>LP</th></tr>\n";
				
				foreach($pp as $p)
				{
					$pid = $p->product_id;
					$qty = $p->qty;
					$eve_part_no = $p->eve_part_no;
					
					$this->db->select('product_id, mrp, gst');
					#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
					#$this->db->where_in('product_id', $products, FALSE);
					$this->db->where('product_id', $pid, FALSE);
					#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
					$result2 = $this->db->get('product', 1000);
					#print $this->db->last_query();
					if($result2->num_rows() == 0)
					{
						 $message = ['status' => FALSE,'message' => "Product $pid not found"];
						 $this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
					}else{						

						$mrp2 = $result2->result()[0]->mrp;
						$gst2 = $result2->result()[0]->gst;
						$disc_amount2 = round(($mrp2 *  $discount)/100,0);
						$lp2 = round($mrp2 - ($mrp2 * $discount/100),0);
						#print $cp;exit;
						$gst_amount1 = round(($lp2 * $gst/100),0);

						$data2 = array();
						$data2['fk_order_id'] = @$order_id;
						$data2['fk_product_id'] = @$pid;
						$data2['quantity'] = @$qty;
						$data2['mrp'] = @$mrp2;
						$data2['discounted_amount'] = $lp2;
						$data2['disc_amount'] = $disc_amount2;
						$data2['disc_per'] = $discount;
						$data2['o_gst_amount'] = $gst_amount1;
						$data2['t_amount'] = $lp2 + $gst_amount1;
						#print "<pre>";
						#print_r($data2);//exit;
						
						$this->Api_model->setData('order_item', $data2);
						
						//$body .= "$eve_part_no * $qty - $mrp2 / $lp2 /$disc_amount2/$discount/$gst_amount1<br>\n";
						$body .="<tr><td>$eve_part_no</td><td>$qty</td><td>$mrp2</td><td>$lp2</td></tr>\n";
					}
				}
				$body .="</table></center>";
				#print "hk";exit;
				$a = $this->db->query("delete from tmp_product where mobile='$mobile' and session='$session'");
				
				$this->Api_model->sendmail("IndoEverest",$emailid,$body,"New Order: " . $order_id);
				$message = ['status' => TRUE,'message' => 'Order placed'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	

	public function order_list_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				$this->db->select('*');
				#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
				#$this->db->where_in('product_id', $products, FALSE);
				$this->db->where('order_by', $mobile);
				$this->db->order_by('order_id', 'desc');
				#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
				$result1 = $this->db->get('order_master', 1000);
				#print $this->db->last_query();exit;
				if($result1->num_rows() == 0)
				{
					$this->response([
					'status' => TRUE,'message' =>  'Order not  found', 'data' => array() ]
					, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
				else{
					#print "ok";
					$res = array();
					$i=0;
					foreach($result1->result_array() as $row)
					{
						//print_r($row);exit;
						$order_id = $row['order_id'];
						$order_date = $row['order_date'];
						$order_date2= date('d-M-Y', strtotime($order_date));
						//$order_date2= date('d-M-Y h:ia', strtotime($order_date));
						#print $order_date2;exit;
						$row['order_date'] = $order_date2;
						#print $order_id;exit;
						
						$this->db->select('*');
						$this->db->join('product', 'product.product_id=order_item.fk_product_id', 'inner');
						$this->db->where('fk_order_id', $order_id, FALSE);
						#$this->db->where('order_by', $mobile, FALSE);
						#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
						$result2 = $this->db->get('order_item', 1000);
						#print $this->db->last_query();exit;
						#$r2 = $result2->result();
						#print_r($r2);exit;
						$row3 = array();
						$j=0;
						#print_r(json_decode(json_encode($result2->result_array()), true));exit;
						foreach($result2->result_array() as $row2)
						{
							#print_r($row2);exit;
							$row3[$j] = $row2;
							$j++;
						}
						#print_r($row);
						$row['products'] = $result2->result_array();
						#print_r($row);exit;
						$res[$i]=$row;
						#$res[$i]['products'] =json_encode(array("Volvo",22,18), 0);
						#$res[$i]['products'] =array();

						$i++;
					}
					
					#print_r($res);
				}
			
				#print $this->db->last_query();exit;
			}
			
			#exit;
            #$info = $this->Api_model->series($data);
			#$banner = $this->banner2_get("Series",$mobile, $session);
			if ($res) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($res) .' order were found', 'data' => $res ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No order were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function outstanding_list_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				$result1 = $this->db->query("SELECT outstanding.*, DATEDIFF(date(now()), STR_TO_DATE(DUEDATE , '%d/%m/%Y')) days
 FROM `outstanding` where 1=0 group by REFERENCE limit 1000"); // WHERE `REFERENCE` = '640'
				#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
				#$this->db->where_in('product_id', $products, FALSE);
				#$this->db->where('order_by', $mobile, FALSE);
				#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
				#$result1 = $this->db->get('order_master', 1000);
				if($result1->num_rows() == 0)
				{
					$this->response([
					'status' => TRUE,'message' =>  'outstanding not  found', 'data' => '' ]
					, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
				else{
					#print "ok";
					$res = array();
					$i=0;
					$res = $result1->result_array();
					
					#print_r($res);
				}
			
				#print $this->db->last_query();exit;
			}
			
			#exit;
            #$info = $this->Api_model->series($data);
			#$banner = $this->banner2_get("Series",$mobile, $session);
			if ($res) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($res) .' outstandding were found', 'data' => $res ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No outstandding were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}
	
	public function invoice1_list_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				$result1 = $this->db->query("select INVDATE,REFERENCE,NAME,CITY, ROUNDOFF, round(sum(SGST),2) SGST, round(sum(CGST),2) CGST, round(sum(IGST),2) IGST,
				(round(sum(SGST),2) + round(sum(CGST),2) + round(sum(IGST),2) ) GST,
				 round(sum(TAXABLEAMT),2) TAXABLEAMT, 
				round((round(sum(SGST),2)+round(sum(CGST),2)+round(sum(IGST),2)+round(sum(TAXABLEAMT),2)-ROUNDOFF),0) TOTAL, GSTIN
				from invoice where 1=0
				group by REFERENCE ORDER BY REFERENCE DESC"); // where REFERENCE='1'
				#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
				#$this->db->where_in('product_id', $products, FALSE);
				#$this->db->where('order_by', $mobile, FALSE);
				#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
				#$result1 = $this->db->get('order_master', 1000);
				if($result1->num_rows() == 0)
				{
					$this->response([
					'status' => TRUE,'message' =>  'invoice not  found', 'data' => '' ]
					, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
				else{
					#print "ok";
					$res = array();
					$i=0;
					$res = $result1->result_array();
					
					#print_r($res);
				}
			
				#print $this->db->last_query();exit;
			}
			
			#exit;
            #$info = $this->Api_model->series($data);
			#$banner = $this->banner2_get("Series",$mobile, $session);
			if ($res) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($res) .' invoice were found', 'data' => $res ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No invoice were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}

	public function series_get($lang ='en')
    {	
		#$model_id= $this->input->get_post('model_id');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		#if (!isset($_REQUEST['model_id']) || empty($_REQUEST['model_id'])) {
        #    $errors[] = 'model_id No is empty';
        #}			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		#$data['model_id'] = $model_id;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->Api_model->series($data);
			$banner = $this->banner2_get("Series",$mobile, $session);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' series were found', 'data' => $info, 'banner' => $banner ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No series were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}

    public function change_pass_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$pass1= $this->input->get_post('pass1');
		$pass2= $this->input->get_post('pass2');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		if (!isset($_POST['pass1']) || empty($_POST['pass1'])) {
            $errors[] = 'pass1 is empty';
        }
		if (!isset($_POST['pass2']) || empty($_POST['pass2'])) {
            $errors[] = 'pass2 is empty';
        }
		
		if($pass1 != $pass2)
		{
			$errors[] = 'password not matching';
		}

		$data = array();
		$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				
				$this->Api_model->UpdateData('user', $data, array('phone' =>$mobile,'session' => $session));
				$message = ['status' => TRUE,'message' => 'Password Updated'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	

    public function coststru_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		
		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $info = $this->db->query("select * from user where phone='$mobile' ")->result();
			$Price_Comparison_Aceess =@$info[0]->Price_Comparison_Aceess;
			$user_type=@$info[0]->user_type;
			$discount=@$info[0]->discount;
			
			if(empty($discount))
			{
				$info2 = $this->db->query("select * from everest_discount order by everest_discount_id desc limit 1 ")->result();
				$discount=@$info2[0]->discount;
				$this->db->query("UPDATE user set discount=$discount where phone='$mobile'");
			}
			
			if($Price_Comparison_Aceess == 'Yes')
			{
				$user_type = 2;
			}
			$data['Price_Comparison_Aceess'] = $Price_Comparison_Aceess;
			$data['user_type'] = $user_type;
			$data['discount'] = $discount;
			
			#print "user_type: $user_type * discount: $discount";
			#exit;
			
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' discount were found', 'data' => $data ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No banner were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }		

    public function getdefaultcost_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		
		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            /*
			$info = $this->db->query("select * from competitor_master
left join competitor_discount
on competitor_discount.fk_com_master_id = competitor_master.id ")->result();
*/
			$info = $this->db->query("select * from competitor_master order by company_name ")->result();
			$discount=@$info[0]->discount2;			
			//print "mobile: $mobile * discount: $discount";exit;
			
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			$user_id = $query->result()[0]->user_id;
			#print $this->db->last_query();exit;
			#print $user_id;exit;
			
			#print $this->db->last_query();exit;
			if ($info) {
			
			$i=0;
			$infodata = array();
			foreach($info as $i2)
			{
				$id = $i2->id;
				$company_name = $i2->company_name;
				$discount2 = $i2->discount2;
				$q1 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$id")->result();
				#print $this->db->last_query() . "\n";//exit;
				if(sizeof($q1) ==0) //0 means not found in sales_competitor_discount
				{					
					$q2 = $this->db->query("select * from competitor_discount where fk_com_master_id=$id")->result();
					#print $this->db->last_query();exit;
					if(sizeof($q2) ==0)
					{
						$discount = $discount2;
					}
					$data = array();
					$data['company_name2'] =$company_name;
					$data['fk_com_master_id'] =$id;
					$data['discount1'] =$discount;
					$data['user_id'] = $user_id;
					
					$this->Api_model->setData("sales_competitor_discount", $data);
				}else{
					$discount = $q1[0]->discount1;
				}
				$infodata[$i]['id']= $id;
				$infodata[$i]['company_name']= $company_name;
				$infodata[$i]['discount']= $discount;
				$i++;
				#print $i2->id . ' ' . $company_name . ' ' . $discount . "\n";
			}
			#print_r($infodata);exit;
			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' discount were found', 'data' => $infodata, 'discount' => $discount ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No banner were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }		

	
    public function getdefaultcost_post_OLD($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		
		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            /*
			$info = $this->db->query("select * from competitor_master
left join competitor_discount
on competitor_discount.fk_com_master_id = competitor_master.id ")->result();
*/
			$info = $this->db->query("select * from competitor_master order by company_name ")->result();
            $info2 = $this->db->query("select * from everest_discount order by everest_discount_id desc limit 1 ")->result();
			$discount=@$info2[0]->discount;			
			#print "user_type: $user_type * discount: $discount";
			#exit;
			
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			$user_id = $query->result()[0]->user_id;
			#print $this->db->last_query();exit;
			#print $user_id;exit;
			
			#print $this->db->last_query();exit;
			if ($info) {
			
			$i=0;
			$infodata = array();
			foreach($info as $i2)
			{
				$id = $i2->id;
				$company_name = $i2->company_name;
				$q1 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$id")->result();
				#print $this->db->last_query() . "\n";//exit;
				if(sizeof($q1) ==0) //0 means not found
				{					
					$q2 = $this->db->query("select * from competitor_discount where fk_com_master_id=$id")->result();
					#print $this->db->last_query();exit;
					if(sizeof($q2) ==0)
					{
						$discount = 0;
					}
				}else{
					$discount = $q1[0]->discount;
				}
				$infodata[$i]['id']= $id;
				$infodata[$i]['company_name']= $company_name;
				$infodata[$i]['discount']= $discount;
				$i++;
				#print $i2->id . ' ' . $company_name . ' ' . $discount . "\n";
			}
			#print_r($infodata);exit;
			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' discount were found', 'data' => $infodata, 'discount' => $discount ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No banner were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }		

	public function setcostAll_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$id5= $this->input->get_post('id');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		if (!isset($_POST['id']) || empty($_POST['id'])) {
            $errors[] = 'id is empty';
        }

		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			#print $query->result()[0]->user_id;exit;
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				//sales_competitor_discount user_id fk_com_master_id

				$id4 = substr($id5,3);
				$id3 = explode("$$$", $id4);
				#print "<pre>";
				#print_r($id3);exit;
				foreach($id3 as $id2)
				{
					$id1 = explode(":", $id2);
					$id =$id1[0];
					$discount =$id1[1];
				
					$q1 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$id")->result();
					#print  $this->db->last_query();exit;
					#print sizeof($q1);exit;
					$data['fk_com_master_id'] = $id;
					$data['discount1'] = $discount;
					if(sizeof($q1) ==0)
					{
						$data['user_id'] = $user_id;
						$this->Api_model->setData('sales_competitor_discount', $data);
					}else{
						$this->Api_model->UpdateData('sales_competitor_discount', $data, array('user_id' =>$user_id,'fk_com_master_id' => $id));
					}
					#print $this->db->last_query();exit;
				}
				$message = ['status' => TRUE,'message' => 'Discount rate updated in system'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	

    public function setcost_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$id= $this->input->get_post('id');
		$discount= $this->input->get_post('discount');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		if (!isset($_POST['id']) || empty($_POST['id'])) {
            $errors[] = 'id is empty';
        }
		if (!isset($_POST['discount']) || empty($_POST['discount'])) {
            $errors[] = 'discount is empty';
        }


		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			#print $query->result()[0]->user_id;exit;
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				//sales_competitor_discount user_id fk_com_master_id
				$q1 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$id")->result();
				
				#print sizeof($q1);exit;
				$data['fk_com_master_id'] = $id;
				$data['discount1'] = $discount;
				if(sizeof($q1) ==0)
				{
					$data['user_id'] = $user_id;
					$this->Api_model->setData('sales_competitor_discount', $data);
				}else{
					$this->Api_model->UpdateData('sales_competitor_discount', $data, array('user_id' =>$user_id,'fk_com_master_id' => $id));
				}
				#print $this->db->last_query();exit;
				$message = ['status' => TRUE,'message' => 'Discount updated'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	

    public function setcost2_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		//$id= $this->input->get_post('id');
		$discount= $this->input->get_post('discount');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		//if (!isset($_POST['id']) || empty($_POST['id'])) {
        //    $errors[] = 'id is empty';
        //}
		if (!isset($_POST['discount']) || empty($_POST['discount'])) {
            $errors[] = 'discount is empty';
        }


		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			#print $query->result()[0]->user_id;exit;
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				$data['discount'] = $discount;
				$this->Api_model->UpdateData('user', $data, array('user_id' =>$user_id));
				
				#print $this->db->last_query();exit;
				$message = ['status' => TRUE,'message' => 'Discount updated'];
			}

        }
        #print $this->db->last_query();exit;
		$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	


    public function getpartcompet_post($lang ='en')
    {	

		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');

		$partno= $this->input->get_post('partno');
		$ip = $this->input->ip_address();
		#$browser = $this->agent->browser().' '.$this->agent->version();
		$browser= $this->input->get_post('browser');
		#$ip= $this->input->get_post('ipadd');
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
            $errors[] = 'mobile is empty';
        }
		if (!isset($_POST['session']) || empty($_POST['session'])) {
            $errors[] = 'session is empty';
        }

		if (!isset($_POST['partno']) || empty($_POST['partno'])) {
            $errors[] = 'partno is empty';
        }
		
		$partno = trim($partno);
		$data = array();
		#$data['password'] = md5($pass1);
		#$data['session'] = $session;
		#$data['mobileno'] = $mobile;
		#$data['ip'] = $ip;
		#$data['browser'] = $browser;
		#$data['datec'] = $datec;

		#print_r($data);exit;
		#print_r($errors);exit;
		
		if (!empty($errors)) {
			#print "1";
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
        } else {
            #print "2";
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;
			#print $query->result()[0]->user_id;exit;
		
			if(sizeof($query->result_array()) != 0)//session found
			{
				//sales_competitor_discount user_id fk_com_master_id
				$q1 = $this->db->query("select * from product where eve_part_no='$partno' limit 1 ")->result();
				#$info2 = $this->db->query("select * from everest_discount order by everest_discount_id desc limit 1 ")->result();
				#$discount=@$info2[0]->discount;						
				#print sizeof($q1);exit;
				#$data['fk_com_master_id'] = $id;
				#$data['discount'] = $discount;
				
				$product_id = @$q1[0]->product_id;
				
				if(empty($product_id))
				{
					$message = [
						'status' => FALSE,'message' => 'product not avaialble'
					];
					$this->set_response($message, REST_Controller::HTTP_CREATED);#exit();
				}else{
				$mrp = @$q1[0]->mrp;
				$gst = @$q1[0]->gst;
				$landingprice = ($mrp - $mrp*$discount/100);
				$gst2 = round(($landingprice * $gst/100),0);

				$infod = $comp2 = array();
				$infod['product_id'] = $product_id;
				$infod['partno'] = $partno;
				$infod['mrp'] = $mrp;
				$infod['gst'] = $gst2;
				$infod['discount'] = $discount;
				$infod['landingprice'] = round($landingprice,0);
				$infod['landingpricegst'] = round(($landingprice + $gst2 ),0);
				$infod['currency'] = "₹";
				
				$q2 = "select * from product_competitor
inner join competitor on competitor.competitor_id = product_competitor.competitor_id 
inner join competitor_master on competitor_master.id = competitor.fk_com_master_id
WHERE `product_id` = $product_id";
				#print $q2;exit;
				$q2row = $this->db->query($q2)->result();
				$j=0;
				foreach($q2row as $row2)
				{
					$competitor_id = $row2->fk_com_master_id;
					$company_name = $row2->company_name;
					$comp_part_no = $row2->comp_part_no;
					$mrp2 = $row2->mrp;
					#print $competitor_id . ' * ';
					#
					#print $this->db->last_query();exit;
					$discount2 = 0;
					$q11 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$competitor_id	")->result();
					#print $this->db->last_query() . "\n";//exit;
					if(sizeof($q11) ==0) //0 means not found
					{					
						$q21 = $this->db->query("select * from competitor_discount where fk_com_master_id=$competitor_id")->result();
						#print $this->db->last_query();exit;
						if(sizeof($q21) ==0)
						{
							$discount2 = 0;
						}
					}else{
						$discount2 = $q11[0]->discount1;
					}
					#print $discount2;exit;
					$eff_date = $row2->eff_date;
					$eff_date2 = '';
					if(!empty($eff_date))
					{
						$eff_date2 = "Wef: " . date('d-M-Y', strtotime($eff_date));
					}
					$landingprice2 = round(($mrp2 - $mrp2*$discount2/100),0);
					$gain = round($landingprice2 - $landingprice);
					if(empty($mrp2))
					{
						$gain = $landingprice2 = '-';
					}

					$comp2[$j]['srno'] = $j+1;
					$comp2[$j]['company_name'] = $company_name;
					$comp2[$j]['comp_part_no'] = $comp_part_no;
					$comp2[$j]['remark'] = $row2->remark;
					$comp2[$j]['eff_date'] = $eff_date2;
					$comp2[$j]['mrp2'] = $mrp2;
					$comp2[$j]['discount2'] = $discount2;
					$comp2[$j]['landingprice'] = $landingprice2;
					$comp2[$j]['gain'] = $gain;
					$comp2[$j]['currency'] = "₹";
					$j++;
				}
				
				if(sizeof($q1) ==0)
				{
					$message = [
						'status' => FALSE,'message' => 'product not avaialble'
					];
					$this->set_response($message, REST_Controller::HTTP_CREATED);//exit();
				}else{
					$this->response([
					'status' => TRUE,'message' =>  sizeof($q1) .' product were found', 'data' => $infod, 'compete' => $comp2 ]
					, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
				#print $this->db->last_query();exit;
				$message = ['status' => TRUE,'message' => 'data Updated'];
				}
			}

        }
        #print $this->db->last_query();exit;
		#$this->set_response($message, REST_Controller::HTTP_OK);
		#print_r($data);exit;
		
    }	
	
	public function part_get($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$type= $this->input->get_post('type');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$query= $this->input->get_post('query');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['type']) || empty($_REQUEST['type'])) {
            $errors[] = 'type is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['type'] = $type;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			$q = '';
			if($type == 'evpart')
			{
				$q = "select distinct eve_part_no from product where eve_part_no like '$query%' order by eve_part_no";
			}
			if($type == 'comp')
			{
				$q = "select distinct comp_part_no eve_part_no from competitor where comp_part_no like '$query%' order by comp_part_no";
			}
			
			$q21 = $this->db->query($q);
			$res = array();
			$i=0;
			foreach($q21->result() as $row)
			{
				#print $row->comp_part_no . "<br>";
				$res[$i]['id'] = $i;
				$res[$i]['name'] = $row->eve_part_no;
				$i++;
			}
			print json_encode($res); exit;
			#print_r($res);
			#exit;
            $info = $this->Api_model->banner_value($data);
			#print $this->db->last_query();exit;
			if ($info) {

			$this->response([
                'status' => TRUE,'message' =>  sizeof($info) .' product were found', 'data' => $info ]
				, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No product were found'
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
     		
        }
	}	
	

	public function invlist_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		#$partno= $this->input->get_post('partno');
		#$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
				
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		#$data['pid'] = $pid;
		#$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		$query= $this->Api_model->_usersessioncheck($session, $mobile);
		#print_r($query);exit;

		if(sizeof($query->result_array()) != 0)//session found
		{
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;	
		}	

		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			
		$result = $this->db->query("select product_id, eve_part_no, profile_img, current_stock from tbl_inventory_master inner join product on product.product_id=tbl_inventory_master.fk_product_id  where user_id='$user_id' and current_stock>0 ");
		#print $this->db->last_query();exit;
			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);exit;
			
			
			if(empty($darry))
			{
				$this->response(['status' => FALSE,'message' =>  'Stock details not available', 'data' => array() ], REST_Controller::HTTP_OK); 
			}else{
				#$data['pid'] = $pid;
				#print sizeof($a);exit;
				#print_r($data);exit;
				#print $this->db->last_query();exit;
				
				#print $this->db->last_query();exit;
				$this->response(['status' => TRUE,'message' =>  ' list','data' => $darry ], REST_Controller::HTTP_OK); 
			
			}
     		#print $this->db->last_query();exit;
        }
	}	

	public function comparecheck_post()
	{
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		#$partno= $this->input->get_post('partno');
		#$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
				
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$data = array();
		$data['mobile'] = $mobile;
		$data['session'] = $session;
		$data['datec'] = $datec;
		$data['ip'] = $ip;
		#$data['pid'] = $pid;
		#$data['qty'] = $qty;
		#$data['field'] = $field;
		#$data['val'] = $val;
		
		$query= $this->Api_model->_usersessioncheck($session, $mobile);
		#print_r($query);exit;

		$pc ='';
		if(sizeof($query->result_array()) != 0)//session found
		{
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;	
			$pc = $query->result()[0]->Price_Comparison_Aceess;	
		}	

		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
				if($pc =='No')
				{
					$message = "This feature is not allowed";
				}else{
					$message = "Allowed";
				}

				$this->response(['status' => TRUE,'message' =>  $message,'pc' => $pc ], REST_Controller::HTTP_OK); 
			
			
        }
	}
	
	public function invaddupdate_post($lang ='en')
    {	
		#$field= $this->input->get_post('field');
		#$val= $this->input->get_post('val');
		$partno= $this->input->get_post('partno');
		$qty= $this->input->get_post('qty');
		$mobile= $this->input->get_post('mobile');
		$session= $this->input->get_post('session');
		$act= $this->input->get_post('act');
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$datec = date("Y-m-d H:i:s");
		
		$errors = [];
		if (!isset($_REQUEST['partno']) || empty($_REQUEST['partno'])) {
            $errors[] = 'partno is empty';
        }				
		if (!isset($_REQUEST['qty']) || empty($_REQUEST['qty'])) {
            $errors[] = 'qty is empty';
        }			
		if (!isset($_REQUEST['session']) || empty($_REQUEST['session'])) {
            $errors[] = 'session No is empty';
        }		
		if (!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])) {
            $errors[] = 'mobile No is empty';
        }		

		$query= $this->Api_model->_usersessioncheck($session, $mobile);
		#print_r($query);exit;
		if(sizeof($query->result_array()) != 0)//session found
		{
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;	
		}	
		
		if (!empty($errors)) {
            $error = implode(", ", $errors);
            $message = [
                'status' => FALSE,'message' => $error
            ];
			
			$this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
			
			$pid =0;
			$a = $this->db->query("select * from product where eve_part_no='$partno' ")->result();
			foreach($a as $row1)
			{
				$pid =  $row1->product_id;
			}
			$inventory_id = 0;
			$b = $this->db->query("select * from tbl_inventory_master where user_id ='$user_id' and fk_product_id='$pid' ")->result();
			foreach($b as $row2)
			{
				$inventory_id =  $row2->inventory_id;
				$opening_bal = $row2->current_stock;
				if($act == 'add') 
				{
					$closing_bal = $opening_bal + $qty;
				}
				if($act == 'sub') 
				{
					$closing_bal = $opening_bal - $qty;
				}
			}	
			#print $inventory_id . " : " . $pid;exit;
			
			if(empty($pid))
			{
				$this->response(['status' => FALSE,'message' =>  ' product not found' ], REST_Controller::HTTP_OK); 
			}else{
				$data['pid'] = $pid;
				
				if(empty($inventory_id)){
					$opening_bal = 0;
					$closing_bal = $qty;
				}
				if($act == 'add') 
				{
					$stock_in = $qty;
					$stock_out = 0;
				}
				if($act == 'sub') 
				{
					$stock_in = 0;
					$stock_out = $qty;
				}
				$f1 = array();
				$f1['stock_date'] = $datec;
				$f1['fk_product_id'] = $pid;
				$f1['user_id'] = $user_id;
				$f1['opening_bal'] = $opening_bal;
				$f1['stock_in'] = $stock_in;
				$f1['stock_out'] = $stock_out;
				$f1['closing_bal'] = $closing_bal;
				#print "<pre>"; print_r($f1);exit;
				//tbl_inventory_history
				
				$data = array();					
				$data['current_stock'] = $closing_bal;
				$data['inventory_date'] =$datec;

				if($act == 'sub')
				{
					if(empty($inventory_id))
					{
						$this->response(['status' => FALSE,'message' =>  ' stock not exists' ], REST_Controller::HTTP_OK); 
					}else{
						if($closing_bal <0)
						{
							$this->response(['status' => FALSE,'message' =>  "Current stock: $opening_bal , stock can not be negative" ], REST_Controller::HTTP_OK);
						}else{
							$this->Api_model->setData("tbl_inventory_history", $f1);
							$info = $this->Api_model->UpdateData('tbl_inventory_master', $data, array('inventory_id'=>$inventory_id,'fk_product_id'=>$pid));
						}
						#print $this->db->last_query();exit;
					}
					$this->response(['status' => TRUE,'message' =>  ' updated' ], REST_Controller::HTTP_OK);
				}else{
					$this->Api_model->setData("tbl_inventory_history", $f1);

					if(empty($inventory_id))
					{
						$data['fk_product_id'] = $pid;
						$data['user_id'] = $user_id;
						$this->Api_model->setData("tbl_inventory_master", $data);
					}else{
						$info = $this->Api_model->UpdateData('tbl_inventory_master', $data, array('inventory_id'=>$inventory_id,'fk_product_id'=>$pid));
					}
					
					#print $this->db->last_query();exit;
					$this->response(['status' => TRUE,'message' =>  ' updated' ], REST_Controller::HTTP_OK); 
				}
				
				
			}
     		#print $this->db->last_query();exit;
        }
	}


}
