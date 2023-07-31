<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Country extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Country_model', 'country');
	}

	public function index($country_id=0)
	{
		$data = array();
		$data['page_title'] = 'country';
		if(intval($country_id) > 0)
		{
			$data['edit'] = $this->country->edit_category($country_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->country->all(); 
		$this->load->view('admin/country', $data);
	}

	


	public function save_country()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$country_id =$this->input->post('country_id');
			$formArr['country_name'] = $country_name = (isset($_POST['country_name']) && !empty($_POST['country_name'])) ? $this->input->post('country_name') : '';
			

			


			   if(intval($country_id)>0)
				{
					    $this->country->update_category($country_id,$formArr);
	                    $this->session->set_flashdata('success', 'country Updated Successfully..');
	                    redirect('admin/Country/index');
                }
			          else
						{
							  $this->country->save_country($formArr);
							  $this->session->set_flashdata('success', 'country Added Successfully..');
			                  redirect('admin/Country/index');
						}	

			 
		}
		redirect('admin/Country/index');
		  
	}

	public function delete_category($country_id = 0)
	{
		if(intval($country_id)>0)
		{
			$this->db->where('country_id', $country_id);
			$check_id = $this->db->get('country')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('country_id', $country_id);
					$this->db->delete('country');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Country/index');
				}
			}
		}
	}

}