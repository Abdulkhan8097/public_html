<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller 
{
	function __construct()
	{
		parent:: __construct();
		if($this->session->userdata('isLoggedIn')==FALSE)
		{
			$this->session->set_flashdata('failed','Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/User_model','user_model');
	}

	public function profile_details($user_id=0)
	{		
		$data = array();
		$data['page_title'] = 'Profile Details';
		$data['user_id'] = $user_id;
		if(intval($user_id)>0)
		{
			$data['user_details'] = $user_details = $this->user_model->getUserDetails($user_id);
			//echo '<pre>';print_r($user_details);exit();
		}
		$this->load->view('admin/user/profile_details',$data);
	}

	public function save_profile_details()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$user_id=(isset($_POST['user_id']))?$_POST['user_id']:'';
			$userData['first_name']=$first_name=(isset($_POST['first_name']))?$_POST['first_name']:'';
	        $userData['last_name']=$last_name=(isset($_POST['last_name']))?$_POST['last_name']:'';
	        $userData['email']=$email=(isset($_POST['email']))?$_POST['email']:'';
	        $userData['phone']=$phone=(isset($_POST['phone']))?$_POST['phone']:'';
	        
	    	if(isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"]))
	        {
	            $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
	            $config['allowed_types']        = 'gif|jpg|jpeg|png';
	            $config['max_size']      = 10000;
	            $config['encrypt_name'] = TRUE;
	            $this->load->library('upload', $config);
	        	if($this->upload->do_upload('image'))
	        	   {
	        	        $upload_data = $this->upload->data();
	        	        $userData['profile_img'] = $upload_data['file_name'];
	        	   }
	        }
	        if(intval($user_id)>0)
	        {
	            $this->db->where('user_id',$user_id)->update('tbl_mst_users',$userData);
	            $this->session->set_flashdata('success','Profile Updated Successful...');
	        }
		}
		redirect(ADMIN_PROFILE_DETAILS_URL.'/'.$user_id);
	}


	public function change_password($user_id=0)
	{		
		$data = array();
		$data['page_title'] = 'Change Password';
		$data['user_id'] = $user_id;
		$data['user_id'] = $this->session->userdata('user_id'); 
		$this->load->view('admin/user/change_password',$data);
	}

	//UPDATE CHANGE PASSSWORD
    public function saveProfileCngPassword()
    {
        if(isset($_POST) && !empty($_POST))
        {
            $action = $this->user_model->saveProfileCngPassword();
        }
        
    }


   

}#EOF
