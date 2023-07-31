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
		 $user_type=$this->session->userdata('user_type');			
		$data['list'] = $this->user_list->get_list($user_type);
		
     $this->load->view('admin/user_list',$data);

	}

public function get_excel_report()
{
	 
	 $result = $this->user_list->get_list2();


	// echo"<pre>";
	// print_r($result);
	// exit;

	$timestamp =time();
	$filename ='admin/User_' . $timestamp . '.xls';
// print_r($result);
// 	exit;
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename="data.xls"');

	$isPrintHeader = false;
	foreach ($result as $row){
		if (!$isPrintHeader) {
			echo implode ("\t", array_keys($row)) . "\n";
			$isPrintHeader = true;
			
		}
		echo implode ("\t", array_values($row)) . "\n";
		
	}
	exit();	

 	}

	public function userList($user_id=0)
	{
		$data = array();
		$data['page_title'] = 'Create User';
		if(intval($user_id) > 0)
		{
			$data['edit'] = $this->user_list->edit_user($user_id);
		}
		$data['company'] = $this->user_list->getcompany();
		$data['list'] = $this->user_list->country();
		// print_r($data['list']);exit;
		$this->load->view('admin/create_user',$data);
	}


	public function myformAjax($country_id) {

	   // $data = $data['list'] = $this->user_list->country($country_id);
       $result = $this->db->where("country_id",$country_id)->get("state")->result();
       echo json_encode($result);
       
  
   }

   

	public function saveUser()
	{

// print_r($_post);exit;
	  
		 $id=$this->session->userdata('user_id');

		 $c_id=$_POST['country'];
		 $query=$this->db->select('country_name')->from('country')->where('country_id',$c_id)->get()->row_array();
		 $country_name=$query['country_name'];

		 $c_id1=$_POST['state'];
		 $query1=$this->db->select('state_name')->from('state')->where('country_id',$c_id1)->get()->row_array();
		 $state_name=$query1['state_name'];



     if (isset($_POST) && !empty($_POST)) {
            $formArr = array();
            $user_id = $this->input->post('user_id');
            $formArr['created_by']=$id;
             $formArr['company_id'] = $company_id = (isset($_POST['company_id']) && !empty($_POST['company_id'])) ? $this->input->post('company_id') : '';
$formArr['Price_Comparison_Aceess'] = $Price_Comparison_Aceess = (isset($_POST['Price_Comparison_Aceess']) && !empty($_POST['Price_Comparison_Aceess'])) ? $this->input->post('Price_Comparison_Aceess') : '';
            $formArr['first_name'] = $first_name = (isset($_POST['first_name']) && !empty($_POST['first_name'])) ? $this->input->post('first_name') : '';
            $formArr['last_name'] = $last_name = (isset($_POST['last_name']) && !empty($_POST['last_name'])) ? $this->input->post('last_name') : '';
            $formArr['email'] = $email = (isset($_POST['email']) && !empty($_POST['email'])) ? $this->input->post('email') : '';
            $formArr['phone'] = $phone = (isset($_POST['phone']) && !empty($_POST['phone'])) ? $this->input->post('phone') : '';
            $formArr['password'] = $password = (isset($_POST['password']) && !empty($_POST['password'])) ? md5($this->input->post('password')) : '';
            $formArr['discount'] = $discount = (isset($_POST['discount']) && !empty($_POST['discount'])) ? $this->input->post('discount') : '';
            $formArr['gst'] = $gst = (isset($_POST['gst']) && !empty($_POST['gst'])) ? $this->input->post('gst') : '';
             $formArr['address'] = $address = (isset($_POST['address']) && !empty($_POST['address'])) ? $this->input->post('address') : '';
             $formArr['user_type'] = $user_type = (isset($_POST['user_type']) && !empty($_POST['user_type'])) ? $this->input->post('user_type') : '';
             $formArr['customer_id'] = $customer_id = (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) ? $this->input->post('customer_id') : '';

             $formArr['country'] = $country = $country_name;



             $formArr['state'] = $state = $state_name;
             $formArr['area'] = $area = (isset($_POST['area']) && !empty($_POST['area'])) ? $this->input->post('area') : '';
             $formArr['master_user'] = $master_user = (isset($_POST['master_user']) && !empty($_POST['master_user'])) ? $this->input->post('master_user') : '';
             
			 $p = @$_POST['password'];
			 #print $p;exit;
			 if(strlen($p)>10)
			 {
				 unset($formArr['password']);
			 }
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