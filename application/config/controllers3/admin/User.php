<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('admin/Userlist_model','user_list');

	}


	public function index()
	{
		$data = array();
		$data['page_title'] = 'User List';			
		$data['list'] = $this->user_list->get_list();
     $this->load->view('admin/user_list',$data);	
	}

	public function userList($user_id=0)
	{
		$data = array();
		$data['page_title'] = 'Create User';
		if(intval($user_id) > 0)
		{
			$data['edit'] = $this->user_list->edit_user($user_id);
		}
		$this->load->view('admin/create_user',$data);
	}

	public function saveUser()
	{

     if (isset($_POST) && !empty($_POST)) {
            $formArr = array();
            $user_id = $this->input->post('user_id');
            $formArr['first_name'] = $first_name = (isset($_POST['first_name']) && !empty($_POST['first_name'])) ? $this->input->post('first_name') : '';
            $formArr['last_name'] = $last_name = (isset($_POST['last_name']) && !empty($_POST['last_name'])) ? $this->input->post('last_name') : '';
            $formArr['email'] = $email = (isset($_POST['email']) && !empty($_POST['email'])) ? $this->input->post('email') : '';
            $formArr['phone'] = $phone = (isset($_POST['phone']) && !empty($_POST['phone'])) ? $this->input->post('phone') : '';
            $formArr['password'] = $password = (isset($_POST['password']) && !empty($_POST['password'])) ? md5($this->input->post('password')) : '';
            $formArr['gst'] = $gst = (isset($_POST['gst']) && !empty($_POST['gst'])) ? $this->input->post('gst') : '';
             $formArr['address'] = $address = (isset($_POST['address']) && !empty($_POST['address'])) ? $this->input->post('address') : '';
             $formArr['user_type'] = $user_type = (isset($_POST['user_type']) && !empty($_POST['user_type'])) ? $this->input->post('user_type') : '';
             $formArr['customer_id'] = $customer_id = (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) ? $this->input->post('customer_id') : '';
             $formArr['isActive'] = $isActive = (isset($_POST['isActive']) && !empty($_POST['isActive'])) ? $this->input->post('isActive') : '';
            // $formArr['created_by'] = $this->session->userdata('user_id');
             if(isset($_FILES["profile_img"]["name"]) && !empty($_FILES["profile_img"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|jpg|jpeg|png';
                  $config['max_size']      = 10000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('profile_img'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['profile_img'] = $upload_data['file_name'];
                     }
              }

                if(intval($user_id) > 0)
        {
              $this->user_list->update_user($user_id,$formArr);
                      $this->session->set_flashdata('success', 'User Updated Successfully..');
                      redirect(ADMIN_USER_URL);
                }
                else
            {
               $this->user_list->save_user($formArr);
                             $this->session->set_flashdata('success', 'New User added successfully..');
            }
            
            redirect(ADMIN_USER_URL);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect(ADMIN_USER_URL);
        }
                } 
                        

        
	

	 public function updatestatus()
    {
    	$user_id = $_POST['user_id'];
    	$status = $_POST['status'];
    	$formArr = array();
    	$formArr['isActive'] = $status; 
	    $this->user_list->updateStatus($user_id, $formArr);
    }
}