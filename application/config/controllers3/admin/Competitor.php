<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Competitor extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Competitor_model','competitor');
	}

	public function index($id=0)
	{
		$data = array();
		$data['page_title'] = 'Competitor';
		if(intval($id)>0)
		{
			$data['edit'] = $this->competitor->edit_competitor($id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->competitor->all(); 
		$this->load->view('admin/competitor_list', $data);
	}

	

	// public function deleteCompetitor($competitor_id = 0)
	// {
	// 	if(intval($competitor_id)>0)
	// 	{
	// 		$this->db->where('competitor_id', $competitor_id);
	// 		$check_id = $this->db->get('competitor')->row_array();
	// 		{
	// 			if($check_id > 0)
	// 			{
	// 				$this->db->where('competitor_id', $competitor_id);
	// 				$this->db->delete('competitor');
	// 				$this->session->set_flashdata('success', 'Class Deleted Successfully');
	// 				redirect(ADMIN_COMPETITOR_URL);
					
	// 			}
	// 		}
	// 	}
	// }


	 
	public function saveCompetitor()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$id =$this->input->post('id');
			$formArr['company_name'] = $company_name = (isset($_POST['company_name']) && !empty($_POST['company_name'])) ? $this->input->post('company_name') : '';

			if(intval($id)>0)
				{
					    $this->competitor->update_competitor($id,$formArr);
	                    $this->session->set_flashdata('success', 'Competitor Updated Successfully..');
	                    redirect(ADMIN_COMPETITOR_URL);
                }
                else
						{
							 $this->competitor->save_competitor($formArr);
							  $this->session->set_flashdata('success', 'Competitor Added Successfully..');
			                  redirect(ADMIN_COMPETITOR_URL);
						}	
			
			}
		redirect(ADMIN_COMPETITOR_URL);
		  
	}
}


