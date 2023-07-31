<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_subcategory_m extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Gallery_subcategory_m_model', 'g_subcategory');
	}

	public function index($g_category_id=0)
	{
		$data = array();
		$data['page_title'] = 'g_subcategory';
		if(intval($g_category_id) > 0)
		{
			$data['edit'] = $this->g_subcategory->edit_gallerym($g_category_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->g_subcategory->all(); 
		$data['list1'] = $this->g_subcategory->all1($g_category_id);
		$this->load->view('admin/gallery_subcategory_m', $data);
	}

	


	public function save_gallery()
	{

// print_r($_POST);exit;

		
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$g_subcategory_id =$this->input->post('g_subcategory_id');


			$g_category_id = $this->input->post('g_category_name');
			$formArr['g_category_id'] = $g_category_id;
			$formArr['g_subcategory_name'] = $g_subcategory_name = (isset($_POST['g_subcategory_name']) && !empty($_POST['g_subcategory_name'])) ? $this->input->post('g_subcategory_name') : '';
			$formArr['g_subcategory_seq'] = $g_subcategory_seq = (isset($_POST['g_subcategory_seq']) && !empty($_POST['g_subcategory_seq'])) ? $this->input->post('g_subcategory_seq') : '';
			

			


			   if(intval($g_subcategory_id)>0)
				{
					    $this->g_subcategory->update_gallery($g_subcategory_id,$formArr);
	                    $this->session->set_flashdata('success', 'Gallery Updated Successfully..');
	                    redirect('admin/Gallery_subcategory_m/index');
                }
			          else
						{
							  $this->g_subcategory->save_gallery($formArr);
							  $this->session->set_flashdata('success', 'Gallery Added Successfully..');
			                  redirect('admin/Gallery_subcategory_m/index/'.$g_category_id);
						}	

			 
		}
		redirect('admin/Gallery_subcategory_m/index');
		  
	}

	public function delete_category($g_subcategory_id = 0)
	{
		if(intval($g_subcategory_id)>0)
		{
			$this->db->where('g_subcategory_id', $g_subcategory_id);
			$check_id = $this->db->get('gallery_subcategory_m')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('g_subcategory_id', $g_subcategory_id);
					$this->db->delete('gallery_subcategory_m');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Gallery_subcategory_m/index');
				}
			}
		}
	}

}