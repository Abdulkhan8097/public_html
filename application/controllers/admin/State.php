<?php
defined('BASEPATH') or exit('No direct script access allowed');

class State extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/State_model', 'state');
	}

	public function index($country_id=0)
	{
		$data = array();
		$data['page_title'] = 'state';
		if(intval($country_id) > 0)
		{
			$data['edit'] = $this->state->edit_category($country_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->state->all();
		$data['list1'] = $this->state->all2($country_id); 
		$this->load->view('admin/state', $data);
	}

	


	public function save_state()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$state_id =$this->input->post('state_id');
			
			$country_id = $this->input->post('country_name');
			$formArr['country_id'] = $country_id;

			$formArr['state_name'] = $state_name = (isset($_POST['state_name']) && !empty($_POST['state_name'])) ? $this->input->post('state_name') : '';
			

			


			   if(intval($state_id)>0)
				{
					    $this->state->update_category($state_id,$formArr);
	                    $this->session->set_flashdata('success', 'state Updated Successfully..');
	                    redirect('admin/State/index');
                }
			          else
						{
							  $this->state->save_state($formArr);
							  $this->session->set_flashdata('success', 'state Added Successfully..');
			                  redirect('admin/State/index/'.$country_id);
						}	

			 
		}
		redirect('admin/State/index');
		  
	}

	public function delete_category($state_id = 0)
	{
		if(intval($state_id)>0)
		{
			$this->db->where('state_id', $state_id);
			$check_id = $this->db->get('country')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('state_id', $state_id);
					$this->db->delete('country');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Country/index');
				}
			}
		}
	}

}