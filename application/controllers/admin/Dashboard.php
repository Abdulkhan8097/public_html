<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		//$this->load->model('admin/Dashboard_model','dashboard_model');
	}


	public function index()
	{ 
		
		$data = array();
		$data['page_title'] = 'Dashboard';
		$this->load->view('admin/dashboard', $data);
	}

}