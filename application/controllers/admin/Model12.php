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
		$this->load->view('admin/index', $data);
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


	 
	public function saveModel()
	{
		// print_r($_FILES);
		// exit;
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$model_id =$this->input->post('model_id');
			$formArr['fk_vm_id'] = $vechicle_make = (isset($_POST['vechicle_make']) && !empty($_POST['vechicle_make'])) ? $this->input->post('vechicle_make') : '';
			$formArr['model_name'] = $model_name = (isset($_POST['model_name']) && !empty($_POST['model_name'])) ? $this->input->post('model_name') : '';
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

