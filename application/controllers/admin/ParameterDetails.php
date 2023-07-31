<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ParameterDetails  extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/ParameterDetails_model','model');
	}


	public function index($category_id=0)
	{ 
		$data = array();
		$data['page_title'] = 'Parameter Details';
		$data['list'] = $this->model->gerParameterDetails($category_id);
		$this->load->view('admin/parameter_details', $data);
	}

}