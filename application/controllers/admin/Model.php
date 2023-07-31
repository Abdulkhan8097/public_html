<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Crud_model','model');
	}

	public function index($model_id=0)
	{
		
		$data = array();
		
		$data['page_title'] = 'Model';
		if(intval($model_id)>0)
		{
			$data['edit'] = $this->model->edit_model($model_id);
		}
		$data['details'] = $this->model->allVechicle($model_id);
		$data['vechicle'] = $this->model->getVechicle();
		
	
		

		$data['list'] = $this->model->all();
		// print_r($data['list']);
		// exit; 
		$this->load->view('admin/index', $data);
	}

	  public function myformAjax($category_model_id) { 
       $result = $this->db->where("fk_vm_category_id",$category_model_id)->get("tbl_model_category")->result();
       echo json_encode($result);
       
  
   }

	public function fetch_data($id=0)
	{
		$data = array();
		$data['page_title'] = 'Product list';
		$data['product'] = $this->model->getModel($id);
		// echo '<pre>';
		// print_r($data['list']);
		// exit;
		$this->load->view('admin/product_list', $data);
	}

	public function getModelProduct($id=0)
	{
		$data = array();
		$data['page_title'] = 'Product list';
		$data['product'] = $this->model->getModel($id);
		// echo '<pre>';
		// print_r($data['list']);
		// exit;
		$this->load->view('admin/product_list', $data);
	}


	public function addModel()
	{
		$dara = array();
		$data['page_title'] = 'Asign Model';
		$this->load->view('admin/add_model', $data);
	}



	public function deleteModel($model_id = 0)
	{
		if(intval($model_id)>0)
		{
			$this->db->where('model_id', $model_id);
			$check_id = $this->db->get('model')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('model_id', $model_id);
					$this->db->delete('model');
					$this->session->set_flashdata('success', 'Model Deleted Successfully');
					redirect(ADMIN_MODEL_URL);
					
				}
			}
		}
	}

	public function updatestatus()
    {
    	$model_id  = $_POST['model_id'];
    	$status = $_POST['status'];
    	$formArr = array();
    	$formArr['status'] = $status; 
	    $this->model->updateStatus($model_id, $formArr);
    }



	 
	public function saveModel()
	{
		// print_r($_FILES);
		// exit;
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$model_id =$this->input->post('model_id');
			$formArr['fk_vm_id'] = $fk_vm_id = (isset($_POST['fk_vm_id']) && !empty($_POST['fk_vm_id'])) ? $this->input->post('fk_vm_id') : '';
			$formArr['fk_model_category_id'] = $fk_model_category_id = (isset($_POST['fk_model_category_id']) && !empty($_POST['fk_model_category_id'])) ? $this->input->post('fk_model_category_id') : '';
			$formArr['model_name'] = $model_name = (isset($_POST['model_name']) && !empty($_POST['model_name'])) ? $this->input->post('model_name') : '';
$formArr['model_sequence'] = $model_sequence = (isset($_POST['model_sequence']) && !empty($_POST['model_sequence'])) ? $this->input->post('model_sequence') : '';
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
                          $formArr['image'] = $upload_data['file_name'];
                     }
              }

			if(intval($model_id)>0)
				{
					    $this->model->update_model($model_id,$formArr);
	                    $this->session->set_flashdata('success', 'Model Updated Successfully..');
	                    redirect(ADMIN_MODEL_URL);
                }
                else
						{
							 $this->model->save_model($formArr);
							  $this->session->set_flashdata('success', 'Model Added Successfully..');
			                  redirect(ADMIN_MODEL_URL);
						}	
			
			}
		redirect(ADMIN_MODEL_URL );


		
		  
	}
}

