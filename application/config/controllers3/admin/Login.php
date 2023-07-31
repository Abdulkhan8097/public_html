<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{
	function __construct()
	{
		parent:: __construct();
	}

	public function index()
	{		
		$data = array();
		$data['page_title'] = 'Login';
		$this->load->view('admin/user/login',$data);
	}

	public function checkuserlogin()
	{

		$email= $this->input->post('email');
		$password=  $this->input->post('password');
		$remember_me = $this->input->post('remember_me');
		$encPassword=md5($password);
	   	$loginResult=$this->db->where(array('email' => $email,'password' => $encPassword))->get('user')->row_array();  
	   	//echo $this->db->last_query();exit;
		
		if(($loginResult)>0)
		{
			if($loginResult['user_type']=='1')
			{				
				if($loginResult['isActive']=='1')
				{
					//remember me
			if($remember_me == 1) 
			{
	            setcookie('email', $email, time() + 60 * 60 * 24 * 100, "/");
	            setcookie('password', $password, time() + 60 * 60 * 24 * 100, "/");
	            setcookie('remember_me', $remember_me, time() + 60 * 60 * 24 * 100, "/");
	        } 
	        else 
	        {
	            setcookie('email', 'gone', time() - 60 * 60 * 24 * 100, "/");
	            setcookie('password', 'gone', time() - 60 * 60 * 24 * 100, "/");
	            setcookie('remember_me', 'gone', time() - 60 * 60 * 24 * 100, "/");
	        }
	            $user_id=$loginResult['user_id'];
			    $usertype=$loginResult['user_type'];


	        //set session
			$sessionArr = array('user_id'=>$user_id,'user_type'=>$usertype,'isLoggedIn'=>TRUE);
			$this->session->set_userdata($sessionArr);
			// redirect(ADMIN_DASHBOARD_URL);
			redirect(site_url('admin/dashboard'));
				}
				else
				{
					$this->session->set_flashdata('failed','Account is Not Active..');
			        redirect(site_url('admin/login'));
				}
			}
			else
			{
				$this->session->set_flashdata('failed','Only Admin Permission!..');
			    redirect(site_url('admin/login'));
			}
		}
		else
		{
         	$this->session->set_flashdata('failed','Username or Password wrong!..');
			redirect(site_url('admin/login'));
		}


		
	}

	public function forgot_password()
	{		
		$data = array();
		$data['page_title'] = 'Forgot Password';
		$this->load->view('admin/user/forgot_password',$data);
	}

	public function saveForgotPassword()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$email =  $this->input->post('email');
			$checkEmail = $this->db->where(array('email' => $email))->get('tbl_mst_users')->row_array();
			if(count($checkEmail)>0)
			{
				$user_id = $checkEmail['user_id'];
				$upData['email_otp_verification'] = $email_otp_verification = mt_rand(100000, 999999);
	            $upData['email_otp_sent_on'] = CURRENT_DATE_TIME_YMD;
	            $saveAction = $this->db->where('user_id',$user_id)->update('tbl_mst_users',$upData);
	            redirect(ADMIN_OTP_VERIFICATION_DETAILS_URL.'/'.$user_id);
	            $this->session->set_flashdata('success',' Please enter 6-digit Otp No.');
	   //          if($saveAction)
				// {
				//   	$this->session->set_flashdata('success',' Please enter 6-digit Otp No.');
				// 	//SEND MAIL		
				// 	$mailBody='Please check Your otp number : '.$email_otp_verification;
				// 	//echo '<pre>';print_r($mailBody);exit();
					
				// 	$mailTitle = SITE_FULL_NAME;	
				// 	$this->load->library('email');
				// 	$this->email->set_newline("\r\n");
				// 	$this->email->set_mailtype("html");
				// 	$this->email->from($email);
				// 	$this->email->to(DEFAULT_INQUIRY_MAIL);
				// 	$this->email->subject($mailTitle);
				// 	$this->email->message($mailBody);
				// 	$this->email->send(); //trigger mail to send
				// 	//echo $this->email->print_debugger();exit;
				// 	redirect(ADMIN_OTP_VERIFICATION_DETAILS_URL);
					
				// }
				// else
				// {
				// 	$this->session->set_flashdata('success','Oops Something went wrong!');
				// 	redirect(ADMIN_FORGET_PASSWORD_DETAILS_URL);	
				// }
				
			}
		 	else
		 	{
		 		$this->session->set_flashdata('failed','Email Not Matched');
		 		redirect(ADMIN_FORGET_PASSWORD_DETAILS_URL);
		 	}
		}
	}

	public function otp_verification($user_id=0)
	{		
		$data = array();
		$data['page_title'] = 'Email Otp Verification';
		$data['user_id'] = $user_id;
		// print_r($data['user_id']);
		// exit;
		$this->load->view('admin/user/otp_verification',$data);
	}

	public function saveOtpVerification()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$user_id = $this->input->post('user_id');
			$email_otp_verification = $this->input->post('email_otp_verification');
			$checkOtpVerification = $this->db->where(array('email_otp_verification' => $email_otp_verification, 'user_id' => $user_id))->get('tbl_mst_users')->row_array();
			if(count($checkOtpVerification)>0)
			{
				redirect(ADMIN_OTP_CONFIRM_PASSWORD_DETAILS_URL.'/'.$user_id);
			}
		 	else
		 	{
		 		$this->session->set_flashdata('failed','Otp Not Matched');
		 		redirect(ADMIN_OTP_VERIFICATION_DETAILS_URL.'/'.$user_id);
		 	}
		}
	}

	public function confirm_password($user_id=0)
	{		
		$data = array();
		$data['page_title'] = 'Confirm Password';
		$data['user_id'] = $user_id;
		$this->load->view('admin/user/confirm_password',$data);
	}
	public function saveConfirmPassword()
	{
		if(isset($_POST) && !empty($_POST))
		{	
			$user_id = $this->input->post('user_id');
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('confirm_password');
			if($password==$confirm_password)
			{
				$data['password'] = md5($password);
				$action = $this->db->where('user_id',$user_id)->update('tbl_mst_users',$data);
				//echo $this->db->last_query($action);exit();
			}
			else
			{
				$this->session->set_flashdata('failed','Password Not Mached.');
				redirect(ADMIN_OTP_CONFIRM_PASSWORD_DETAILS_URL.'/'.$user_id);
			}
			
		}
		
		redirect(ADMIN_LOGIN_URL);
	}

	public function logout()
    {
    
        $this->session->sess_destroy();
        redirect(site_url('admin/login'));
    
    }

}#EOF
