<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Discount extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Discount_model','discount');
	}

	public function index($everest_discount_id=0)
	{
		$data = array();
		$data['page_title'] = 'Everest Discount';
		if(intval($everest_discount_id)>0)
		{
			$data['edit'] = $this->discount->edit_discount($everest_discount_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->discount->all(); 
		$this->load->view('admin/discount_list', $data);
	}

	

	public function deleteDiscount($everest_discount_id = 0)
	{
		if(intval($everest_discount_id)>0)
		{
			$this->db->where('everest_discount_id', $everest_discount_id);
			$check_id = $this->db->get('everest_discount')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('everest_discount_id', $everest_discount_id);
					$this->db->delete('everest_discount');
					$this->session->set_flashdata('success', 'Class Deleted Successfully');
					redirect(ADMIN_DISCOUNT_URL);
					
				}
			}
		}
	}


	 
	public function saveDiscount()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$everest_discount_id =$this->input->post('everest_discount_id');
			$formArr['discount'] = $discount = (isset($_POST['discount']) && !empty($_POST['discount'])) ? $this->input->post('discount') : '';

			if(intval($everest_discount_id)>0)
				{
					    $this->discount->update_discount($everest_discount_id,$formArr);
	                    $this->session->set_flashdata('success', 'Discount Updated Successfully..');
	                    redirect(ADMIN_DISCOUNT_URL);
                }
                else
						{
							 $this->discount->save_discount($formArr);
							  $this->session->set_flashdata('success', 'Discount Added Successfully..');
			                  redirect(ADMIN_DISCOUNT_URL);
						}	
			
			}
		redirect(ADMIN_DISCOUNT_URL);
		  
	}
}


