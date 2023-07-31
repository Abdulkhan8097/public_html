<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Company_model', 'company');
	}

	public function index($company_id=0)
	{
		$data = array();
		$data['page_title'] = 'company';
		if(intval($company_id) > 0)
		{
			$data['edit'] = $this->company->edit_category($company_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->company->all(); 
		$data['list1'] = $this->company->all2();
		$data['list2'] = $this->company->all3();
		// print_r($data['list2']);exit;
		$data['list3'] = $this->company->all4();
		$this->load->view('admin/company', $data);
	}





	public function save_company()
	{

		// print_r($_POST);exit;


		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$company_id =$this->input->post('company_id');
			$formArr['company_type'] = $company_type = (isset($_POST['company_type']) && !empty($_POST['company_type'])) ? $this->input->post('company_type') : '';

			$formArr['c_name'] = $c_name = (isset($_POST['c_name']) && !empty($_POST['c_name'])) ? $this->input->post('c_name') : '';

			$formArr['order_email_id'] = $order_email_id = (isset($_POST['order_email_id']) && !empty($_POST['order_email_id'])) ? $this->input->post('order_email_id') : '';

			$formArr['dealer_distributor_id'] = $dealer_distributor_id = (isset($_POST['dealer_distributor_id']) && !empty($_POST['dealer_distributor_id'])) ? $this->input->post('dealer_distributor_id') : '';

			$formArr['h_com'] = $h_com = (isset($_POST['h_com']) && !empty($_POST['h_com'])) ? $this->input->post('h_com') : '';
			
				if ($company_type=='1') {
					
			$checkdata=$this->company->check($company_type,);
			if ($checkdata) {
				$this->session->set_flashdata('error', 'Sorry Company Already Created..');
				return  redirect('admin/Company/index');
			}
		}
			


			   if(intval($company_id)>0)
				{
					    $this->company->update_category($company_id,$formArr);
	                    $this->session->set_flashdata('success', 'company Updated Successfully..');
	                    redirect('admin/Company/index');
                }
			          else
						{
							  $this->company->save_category($formArr);
							  $this->session->set_flashdata('success', 'company Added Successfully..');
			                  redirect('admin/Company/index');
						}	

			 
		}
		redirect('admin/Company/index');
		  
	}

	public function delete_category($company_id = 0)
	{
		if(intval($company_id)>0)
		{
			$this->db->where('company_id', $company_id);
			$check_id = $this->db->get('company_m')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('company_id', $company_id);
					$this->db->delete('company_m');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect('admin/Company/index');
				}
			}
		}
	}

}