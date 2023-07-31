<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comp extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Comp_model','comp'));
		}
		$this->load->model('admin/Comp_model','comp');
	}

	public function index($competitor_discount_id=0)
	{
		$data = array();
		$data['page_title'] = 'Competitor Discount';
		if(intval($competitor_discount_id) > 0)
		{
			$data['edit'] = $this->comp->edit_comp($competitor_discount_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['company'] = $this->comp->getCompany();
		$data['get_discount'] = $this->comp->get_discount(); 
		// print_r($data['get_discount']);
		// exit;
		$this->load->view('admin/comp_list', $data);
	}
	public function saveComp()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$competitor_discount_id =$this->input->post('competitor_discount_id');
			$formArr['company_name'] = $company_name = (isset($_POST['company_name']) && !empty($_POST['company_name'])) ? $this->input->post('company_name') : '';
			$formArr['discount'] = $discount = (isset($_POST['discount']) && !empty($_POST['discount'])) ? $this->input->post('discount') : '';


			   if(intval($competitor_discount_id)>0)
				{
					    $this->comp->update_comp($competitor_discount_id,$formArr);
	                    $this->session->set_flashdata('success', 'Discount Updated Successfully..');
	                    redirect(ADMIN_COMP_URL );
                }
			          else
						{	
							$this->comp->save_comp($formArr);
							  $this->session->set_flashdata('success', 'Discount Added Successfully..');
			                  redirect(ADMIN_COMP_URL);
						}	

			 
		}
		redirect(ADMIN_COMP_URL );
		  
	}

	public function delete_comp($competitor_discount_id = 0)
	{
		if(intval($competitor_discount_id)>0)
		{
			$this->db->where('competitor_discount_id', $competitor_discount_id);
			$check_id = $this->db->get('competitor_discount')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('competitor_discount_id', $competitor_discount_id);
					$this->db->delete('competitor_discount');
					$this->session->set_flashdata('success', 'Discount Deleted Successfully');
					redirect(ADMIN_COMP_URL);
				}
			}
		}
	}

}