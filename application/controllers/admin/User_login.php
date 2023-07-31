<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_login extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		

		
		$this->load->model('admin/User_login_model','user_list');

	}

	
	public function index()
	{
		$data = array();
		$data['page_title'] = 'User List';			
		$data['list'] = $this->user_list->get_list();
		$this->load->view('admin/user_login' ,$data);
	}
}
