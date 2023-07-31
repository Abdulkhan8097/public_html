<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retail extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/retail_model','retail'));
		}
		$this->load->model('admin/Retail_model','retail');
	}

	public function index()
	{
		$data = array();
		$data['page_title'] = 'Retail List';
		$data['product']  = $this->retail->all1(); 
		$data['list'] = $this->retail->all(); 
		$this->load->view('admin/retail_list', $data);
	}
	public function saveRetail()
	{
		$i=0;
		foreach ($_POST['part_no1'] as $key => $value) {
			$i++;
		}

		if(isset($_POST) && !empty($_POST))
		{
			$formArr = array();
			$retail_id =$this->input->post('retail_id');
			 for($j=0; $j<$i; $j++)
            {

				$formArr['product_id'] = $product_id = (isset($_POST['part_no'][$j]) && !empty($_POST['part_no'][$j])) ? $_POST['part_no'][$j] : '';
				$formArr['child_id'] = $part_no1 = (isset($_POST['part_no1'][$j]) && !empty($_POST['part_no1'][$j])) ? $_POST['part_no1'][$j] : '';
			$this->retail->save_retail($formArr);
			}
			$this->session->set_flashdata('success', 'Retail Added Successfully..');
			redirect(ADMIN_RETAIL_URL);
			
		}   
		else
		{	
			
			$this->session->set_flashdata('error', 'Something went wrong..');
			redirect(ADMIN_RETAIL_URL);
		}	

			 
	}
		
		  
	

	public function delete_retail($retail_id = 0)
	{
		if(intval($retail_id)>0)
		{
			$this->db->where('retail_id', $retail_id);
			$check_id = $this->db->get('retail_list')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('retail_id', $retail_id);
					$this->db->delete('retail_list');
					$this->session->set_flashdata('success', 'Retail Deleted Successfully');
					redirect(ADMIN_RETAIL_URL);
				}
			}
		}
	}

	

}