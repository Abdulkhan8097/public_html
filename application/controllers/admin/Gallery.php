<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Gallery_model', 'gallery');
	}

	public function index($g_category_id=0)
	{
		$data = array();
		$data['page_title'] = 'Gallery';
		if(intval($g_category_id) > 0)
		{
			$data['edit'] = $this->gallery->edit_gallerym($g_category_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->gallery->all(); 
		$data['list1'] = $this->gallery->all1($g_category_id);
		$data['list2'] = $this->gallery->all2($g_category_id);
		$this->load->view('admin/gallery', $data);
	}




	public function myformAjax($g_category_id) {
       $result = $this->db->where("g_category_id",$g_category_id)->get("gallery_subcategory_m")->result();
       echo json_encode($result);
       
  
   }

	


	public function save_gallery()
	{


// print_r($_POST);exit;
		
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$gallery_id =$this->input->post('gallery_id');


			$g_category_id = $this->input->post('g_category_name');
			$formArr['g_category_id'] = $g_category_id;
			$g_subcategory_id = $this->input->post('g_subcategory_name');
			$formArr['g_subcategory_id'] = $g_subcategory_id;
			$formArr['g_type'] = $g_type = (isset($_POST['g_type']) && !empty($_POST['g_type'])) ? $this->input->post('g_type') : '';
			$formArr['g_youtube'] = $this->input->post('g_youtube');
			$formArr['g_seq'] = $this->input->post('g_seq');

			if(isset($_FILES["g_image"]["name"]) && !empty($_FILES["g_image"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|jpg|jpeg|png';
                  $config['max_size']      = 10000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('g_image'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['g_image'] = $upload_data['file_name'];
                     }
              }
			

			


			   if(intval($gallery_id)>0)
				{
					    $this->gallery->update_gallery($gallery_id,$formArr);
	                    $this->session->set_flashdata('success', 'Gallery Updated Successfully..');
	                    redirect('admin/Gallery/index');
                }
			          else
						{
							  $this->gallery->save_gallery($formArr);
							  $this->session->set_flashdata('success', 'Gallery Added Successfully..');
			                  redirect('admin/Gallery/index/'.$g_subcategory_id.$g_category_id);
						}	

			 
		}
		redirect('admin/Gallery/index');
		  
	}

	public function delete_category($gallery_id = 0)
	{
		if(intval($gallery_id)>0)
		{
			$this->db->where('gallery_id', $gallery_id);
			$check_id = $this->db->get('gallery_subcategory_m')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('gallery_id', $gallery_id);
					$this->db->delete('gallery_subcategory_m');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Gallery/index');
				}
			}
		}
	}

}