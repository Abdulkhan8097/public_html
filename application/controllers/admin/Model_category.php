<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_category extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Model_category_m','model_list');
	}

	public function index($category_model_id=0)
	{
		$data = array();
		$data['page_title'] = 'Model Category';
		if(intval($category_model_id) > 0)
		{

			$data['edit'] = $this->model_list->edit_data($category_model_id);
			
		}
		$data['v_brand']=$this->model_list->vechile_brand_data();
		$data['get_data']=$this->model_list->vechile_get_data();
		
		
		
		$this->load->view('admin/model_category',$data);
	}
	public function save_model_category()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			// print_r($_POST);
			// exit;
			$formArr = array();
			$Category_model_id =$this->input->post('category_model_id');
			
			$formArr['fk_vm_category_id'] = $fk_vm_category_id = (isset($_POST['fk_vm_category_id']) && !empty($_POST['fk_vm_category_id'])) ? $this->input->post('fk_vm_category_id') : '';
			$formArr['model_category_name'] = $model_category_name = (isset($_POST['model_category_name']) && !empty($_POST['model_category_name'])) ? $this->input->post('model_category_name') : '';
			$formArr['category_sequence'] = $category_sequence = (isset($_POST['category_sequence']) && !empty($_POST['category_sequence'])) ? $this->input->post('category_sequence') : '';


			   if(intval($Category_model_id)>0)
				{
					    $this->model_list->update_data($Category_model_id,$formArr);
	                    $this->session->set_flashdata('success', 'Model Updated Successfully..');
	                    redirect(CATEGORY_MODEL_URL );
                }
			          else
						{	
								// echo "<pre>";
							 //  print_r($formArr);
							 //  exit;
							
							$this->model_list->insert_category_model_data($formArr);
						

							
							  $this->session->set_flashdata('success', 'Model Added Successfully..');
							  
			                  redirect(CATEGORY_MODEL_URL);
						}	

			 
		}
		redirect(CATEGORY_MODEL_URL );
		  
	}

	public function delete_data($Category_model_id = 0)
	{
		if(intval($Category_model_id)>0)
		{
			$this->db->where('Category_model_id', $Category_model_id);
			$check_id = $this->db->get('tbl_model_category')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('Category_model_id', $Category_model_id);
					$this->db->delete('tbl_model_category');
					$this->session->set_flashdata('success', ' Deleted Successfully');
					redirect(CATEGORY_MODEL_URL);
				}
			}
		}
	}
	 public function updateStatus()
    {
    	$id = $_POST['id'];
    	$status = $_POST['status'];
    	$formArr = array();
    	$formArr['status'] = $status; 
	    $q=$this->model_list->updateStatus($id, $formArr);
	   
    }

}