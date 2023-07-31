<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Series_category extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Series_model','series'));
		}
		$this->load->model('admin/series_category_model','series');
	}

	public function index($category_series_id=0)
	{
		$data = array();
		$data['page_title'] = 'Series';
		if(intval($category_series_id) > 0)
		{
			$data['edit'] = $this->series->edit_seriescategory($category_series_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->series->all(); 
		$this->load->view('admin/series_category', $data);
	}
	public function saveSeriescategory()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$category_series_id =$this->input->post('category_series_id');
			$formArr['series_category_name'] = $series_category_name = (isset($_POST['series_category_name']) && !empty($_POST['series_category_name'])) ? $this->input->post('series_category_name') : '';
			$formArr['series_category_sequence'] = $series_category_sequence = (isset($_POST['series_category_sequence']) && !empty($_POST['series_category_sequence'])) ? $this->input->post('series_category_sequence') : '';

			   if(intval($category_series_id)>0)
				{
					    $this->series->update_seriescategory($category_series_id,$formArr);
	                    $this->session->set_flashdata('success', 'Series Updated Successfully..');
	                    redirect(ADMIN_SERIES_CATEGORY_URL );
                }
			          else
						{	
							$this->series->save_seriescategory($formArr);
							  $this->session->set_flashdata('success', 'Series Added Successfully..');
			                  redirect(ADMIN_SERIES_CATEGORY_URL);
						}	

			 
		}
		redirect(ADMIN_SERIES_CATEGORY_URL );
		  
	}

	public function delete_seriescategory($category_series_id = 0)
	{
		if(intval($category_series_id)>0)
		{
			$this->db->where('category_series_id', $category_series_id);
			$check_id = $this->db->get('tbl_series_category')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('category_series_id', $category_series_id);
					$this->db->delete('tbl_series_category');
					$this->session->set_flashdata('success', 'Series Deleted Successfully');
					redirect(ADMIN_SERIES_CATEGORY_URL);
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
	    $q=$this->series->updateStatus($id, $formArr);
	   
    }

}