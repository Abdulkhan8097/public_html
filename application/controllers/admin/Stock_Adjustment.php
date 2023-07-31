<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_Adjustment extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/Series_model','series'));
		}
		$this->load->model('admin/Stock_adj_model','stock');
	}

	public function index()
	{
		$data = array();
		$data['page_title'] = 'Stock_Adjustment';
		$data['fetch_data'] = $this->stock->fetch_part_no();
		

		
		$this->load->view('admin/stock_adj',$data);
	}
	public function getRecourd(){
		// print_r($_POST);
		// exit;
			
		$data = array();
		$user_id= $this->session->userdata('user_id');
		$data['page_title'] = 'Stock_Adjustment';
		$product_id =$this->input->post('product_id');
		
		// print_r($formArr);
		// exit;
		if(intval($product_id)>0)
				{
		

		$data['idby'] = $this->stock->listing($product_id,$user_id);
		
	
		$this->load->view('admin/Stock_check',$data);
	}

	

}
}