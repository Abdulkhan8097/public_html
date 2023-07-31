<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail extends CI_Controller
{
	
public function index($redeem_request_id = 0)
	{
		$this->load->model('admin/Detail_model','detail');
		$data = array();
		$data['page_title'] = 'Redeem Detail';		
		if(intval($redeem_request_id) > 0)
		{
			$data['detail'] = $this->detail->getdetail($redeem_request_id);
			// print_r($data['detail']);
			// exit;
		}	
		    $this->load->view('admin/detail', $data);
	}
	
    
}