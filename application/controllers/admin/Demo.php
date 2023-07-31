<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller 
{
	function __construct()
	{
		parent:: __construct();
		if($this->session->userdata('isLoggedIn')==FALSE)
		{
			$this->session->set_flashdata('failed','Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
	}

	public function List()
	{		
		$data = array();
		$data['page_title'] = 'List';
		$this->load->view('admin/demo/List',$data);
	}

	public function jqgrid_list()
	{		
		$data = array();
		$data['page_title'] = 'table JqGrid List';
		$this->load->view('admin/demo/jqgrid_list',$data);
	}

	public function data_edit_table_list()
	{		
		$data = array();
		$data['page_title'] = 'Data Table List';
		$this->load->view('admin/demo/data_edit_table_list',$data);
	}

	public function details()
	{		
		$data = array();
		$data['page_title'] = 'Details';
		$this->load->view('admin/demo/details',$data);
	}

}#EOF
