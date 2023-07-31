<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Banner_model','banner');
	}

	public function index($id=0)
	{
		$data = array();
		$data['page_title'] = 'Banner';
		if(intval($id)>0)
		{
			$data['edit'] = $this->banner->edit_banner($id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->banner->all(); 
		$this->load->view('admin/banner_list', $data);
	}


	function viewimage(){
    $data = array();

    $this->load->model('upload_images');

    $data['uploaded_images'] = $this->upload_images->get_images();


    $this->load->view('home', $data);
}

	

	public function deleteBanner($id = 0)
	{
		if(intval($id)>0)
		{
			$this->db->where('id', $id);
			$check_id = $this->db->get('banner')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('id', $id);
					$this->db->delete('banner');
					$this->session->set_flashdata('success', 'Class Deleted Successfully');
					redirect(ADMIN_BANNER_URL);
					
				}
			}
		}
	}


	 
	public function saveBanner()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$id =$this->input->post('id');
			$formArr['banner_name'] = $banner_name = (isset($_POST['banner_name']) && !empty($_POST['banner_name'])) ? $this->input->post('banner_name') : '';
			$formArr['page'] = $page = (isset($_POST['page']) && !empty($_POST['page'])) ? $this->input->post('page') : '';
			$formArr['screen_type'] = $screen_type = (isset($_POST['screen_type']) && !empty($_POST['screen_type'])) ? $this->input->post('screen_type') : '';
			$formArr['publish'] = $publish = (isset($_POST['publish']) && !empty($_POST['publish'])) ? $this->input->post('publish') : '';
			$formArr['description'] = $description = (isset($_POST['description']) && !empty($_POST['description'])) ? $this->input->post('description') : '';
			$formArr['order'] = $order = (isset($_POST['order']) && !empty($_POST['order'])) ? $this->input->post('order') : '';
			$formArr['hyper_link'] = $hyper_link = (isset($_POST['hyper_link']) && !empty($_POST['hyper_link'])) ? $this->input->post('hyper_link') : '';
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

			if(intval($id)>0)
				{
					    $this->banner->update_banner($id,$formArr);
	                    $this->session->set_flashdata('success', 'Model Updated Successfully..');
	                    redirect(ADMIN_BANNER_URL);
                }
                else
						{
							 $this->banner->save_banner($formArr);
							  $this->session->set_flashdata('success', 'Model Added Successfully..');
			                  redirect(ADMIN_BANNER_URL);
						}	
			
			}
		redirect(ADMIN_BANNER_URL );	  
	}

	public function updatebanner()
    {
    	$id = $_POST['id'];
    	$status = $_POST['status'];
    	$formArr = array();
    	$formArr['status'] = $status; 
	    $this->banner->updateStus($id, $formArr);
    }
}

