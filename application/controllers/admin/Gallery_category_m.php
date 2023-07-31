<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_category_m extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Gallery_category_m_model', 'g_category');
	}

	public function index($g_category_id=0)
	{
		$data = array();
		$data['page_title'] = 'g_category';
		if(intval($g_category_id) > 0)
		{
			$data['edit'] = $this->g_category->edit_gallery($g_category_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->g_category->all(); 
		$this->load->view('admin/gallery_category_m', $data);
	}

	


	public function save_gallery()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$g_category_id =$this->input->post('g_category_id');
			$formArr['g_category_name'] = $g_category_name = (isset($_POST['g_category_name']) && !empty($_POST['g_category_name'])) ? $this->input->post('g_category_name') : '';
			$formArr['g_category_seq'] = $g_category_seq = (isset($_POST['g_category_seq']) && !empty($_POST['g_category_seq'])) ? $this->input->post('g_category_seq') : '';
			

			


			   if(intval($g_category_id)>0)
				{
					    $this->g_category->update_gallery($g_category_id,$formArr);
	                    $this->session->set_flashdata('success', 'Gallery Updated Successfully..');
	                    redirect('admin/Gallery_category_m/index');
                }
			          else
						{
							  $this->g_category->save_gallery($formArr);
							  $this->session->set_flashdata('success', 'Gallery Added Successfully..');
			                  redirect('admin/Gallery_category_m/index');
						}	

			 
		}
		redirect('admin/Gallery_category_m/index');
		  
	}

	public function delete_category($g_category_id = 0)
	{
		if(intval($g_category_id)>0)
		{
			$this->db->where('g_category_id', $g_category_id);
			$check_id = $this->db->get('gallery_category_m')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('g_category_id', $g_category_id);
					$this->db->delete('gallery_category_m');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Gallery_category_m/index');
				}
			}
		}
	}

}