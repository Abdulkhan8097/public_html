<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Series extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Series_model','series'));
		}
		$this->load->model('admin/Series_model','series');
	}

	public function index($series_id=0)
	{
		$data = array();
		$data['page_title'] = 'Series';
		if(intval($series_id) > 0)
		{
			$data['edit'] = $this->series->edit_series($series_id);
		}
		$data['list'] = $this->series->all(); 
		$this->load->view('admin/series_list', $data);
	}
	public function saveSeries()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$series_id =$this->input->post('series_id');
			$formArr['series_name'] = $series_name = (isset($_POST['series_name']) && !empty($_POST['series_name'])) ? $this->input->post('series_name') : '';

			   if(intval($series_id)>0)
				{
					    $this->series->update_series($series_id,$formArr);
	                    $this->session->set_flashdata('success', 'Series Updated Successfully..');
	                    redirect(ADMIN_SERIES_URL );
                }
			          else
						{	
							$this->series->save_series($formArr);
							  $this->session->set_flashdata('success', 'Series Added Successfully..');
			                  redirect(ADMIN_SERIES_URL);
						}	

			 
		}
		redirect(ADMIN_SERIES_URL );
		  
	}

	public function delete_series($series_id = 0)
	{
		if(intval($series_id)>0)
		{
			$this->db->where('series_id', $series_id);
			$check_id = $this->db->get('series')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('series_id', $series_id);
					$this->db->delete('series');
					$this->session->set_flashdata('success', 'Series Deleted Successfully');
					redirect(ADMIN_SERIES_URL);
				}
			}
		}
	}

}