<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Download_model','downloads'));
		}
		$this->load->model('admin/Download_model','downloads');
	}

	public function index($id=0)
	{
		$data = array();
		$data['page_title'] = 'Downloads';
		if(intval($id) > 0)
		{
			$data['edit'] = $this->downloads->edit_downloads($id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->downloads->all(); 
		$this->load->view('admin/download_list', $data);
	}
	public function saveDownload()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$id =$this->input->post('id');
			$formArr['download_type_name'] = $download_type_name = (isset($_POST['download_type_name']) && !empty($_POST['download_type_name'])) ? $this->input->post('download_type_name') : '';

			$formArr['file_type_name'] = $file_type_name = (isset($_POST['file_type_name']) && !empty($_POST['file_type_name'])) ? $this->input->post('file_type_name') : '';

			if(isset($_FILES["files_name"]["name"]) && !empty($_FILES["files_name"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|pdf|jpg|jpeg|png';
                  $config['max_size']      = 10000000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('files_name'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['files_name'] = $upload_data['file_name'];
                     }
              }


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
					    $this->downloads->update_downloads($id,$formArr);
	                    $this->session->set_flashdata('success', 'Series Updated Successfully..');
	                    redirect(ADMIN_DOWNLOAD_URL );
                }
			          else
						{	
							$this->downloads->save_downloads($formArr);
							  $this->session->set_flashdata('success', 'Series Added Successfully..');
			                  redirect(ADMIN_DOWNLOAD_URL);
						}	

			 
		}
		redirect(ADMIN_DOWNLOAD_URL );
		  
	}

	public function delete_download($id = 0)
	{
		if(intval($id)>0)
		{
			$this->db->where('id', $id);
			$check_id = $this->db->get('downloads')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('id', $id);
					$this->db->delete('downloads');
					$this->session->set_flashdata('success', 'Series Deleted Successfully');
					redirect(ADMIN_DOWNLOAD_URL);
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
	    $q=$this->downloads->updateStatus($id, $formArr);
	   
    }

}