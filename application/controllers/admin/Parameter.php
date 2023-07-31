<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parameter extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Parameter_model','parameter_search'));
		}
		$this->load->model('admin/Parameter_model','parameter_search');
	}

	public function index($id=0)
	{
		$data = array();
		$data['page_title'] = 'parameter_search';
		if(intval($id) > 0)
		{
			$data['edit'] = $this->parameter_search->edit_parameter_search($id);
			// print_r($data['edit']);
			// exit;
		}
		$data['category']  = $this->parameter_search->all1();
		$data['list'] = $this->parameter_search->all(); 
		$this->load->view('admin/parameter_list', $data);
	}
	public function saveParameter()
	{
		// echo '<pre>';
		// print_r($_POST);
		// exit;
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$id =$this->input->post('id');
			$formArr['category_id'] = $category_id = (isset($_POST['category_id']) && !empty($_POST['category_id'])) ? $this->input->post('category_id') : '';
			$formArr['display_order'] = $display_order = (isset($_POST['display_order']) && !empty($_POST['display_order'])) ? $this->input->post('display_order') : '';
			$formArr['display_details_order'] = $display_details_order = (isset($_POST['display_details_order']) && !empty($_POST['display_details_order'])) ? $this->input->post('display_details_order') : '';

			$formArr['display_details'] = $display_details = (isset($_POST['display_details']) && !empty($_POST['display_details'])) ? $this->input->post('display_details') : '';

			$formArr['parameter_name'] = $parameter_name = (isset($_POST['parameter_name']) && !empty($_POST['parameter_name'])) ? $this->input->post('parameter_name') : '';

			$formArr['parameter_order'] = $parameter_order = (isset($_POST['parameter_order']) && !empty($_POST['parameter_order'])) ? $this->input->post('parameter_order') : '';
			$formArr['related'] = $related = (isset($_POST['related']) && !empty($_POST['related'])) ? $this->input->post('related') : '';
			$formArr['advance_flag'] = $advance_flag= (isset($_POST['advance_flag']) && !empty($_POST['advance_flag'])) ? $this->input->post('advance_flag') : '';

			$formArr['display'] = $display= (isset($_POST['display']) && !empty($_POST['display'])) ? $this->input->post('display') : '';
			$formArr['search_dimension'] = $search_dimension= (isset($_POST['search_dimension']) && !empty($_POST['search_dimension'])) ? $this->input->post('search_dimension') : '';

			   if(intval($id)>0)
				{
					    $this->parameter_search->update_parameter_search($id,$formArr);
	                    $this->session->set_flashdata('success', 'Parameter Updated Successfully..');
	                    redirect(ADMIN_PARAMETER_URL );
                }
			          else
						{	
							$this->parameter_search->save_parameter_search($formArr);
							  $this->session->set_flashdata('success', 'Parameter Added Successfully..');
			                  redirect(ADMIN_PARAMETER_URL);
						}	

			 
		}
		redirect(ADMIN_PARAMETER_URL );
		  
	}

	public function delete_parameter($id = 0)
	{
		if(intval($id)>0)
		{
			$this->db->where('id', $id);
			$check_id = $this->db->get('parameter_search')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('id', $id);
					$this->db->delete('parameter_search');
					$this->session->set_flashdata('success', 'Parameter Deleted Successfully');
					redirect(ADMIN_PARAMETER_URL);
				}
			}
		}
	}

}