<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sale extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/sale_model','sale');
	}

	public function index()
	{
		$data = array();
		$data['page_title'] = 'Sale'; 
		if(isset($_POST['name']) && !empty($_POST['name']))
		{
			$data['list'] = $this->sale->all($_POST['name']); 
		}
		else
			{
				$data['list'] = $this->sale->all(); 
			}
			$data['user']  = $this->sale->all1();
		    $this->load->view('admin/sale_list', $data);

	}
	

	public function deletesale($sales_competitor_discount_id = 0)
	{
		if(intval($sales_competitor_discount_id)>0)
		{
			$this->db->where('sales_competitor_discount_id', $sales_competitor_discount_id);
			$check_id = $this->db->get('sales_competitor_discount')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('sales_competitor_discount_id', $sales_competitor_discount_id);
					$this->db->delete('sales_competitor_discount');
					$this->session->set_flashdata('success', 'Sale Deleted Successfully');
					redirect(ADMIN_SALE_URL);
				}
			}
		}
	}

}