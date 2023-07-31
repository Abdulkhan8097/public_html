<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Redeem_request extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Redeem_request_model','sale');
	}

	


	public function index()
	{
		$data = array();
		$data['page_title'] = 'Redeem';
		
		$data['user']  = $this->sale->all1();
		$this->load->view('admin/redeem_request', $data);
	}



	public function edit($redeem_request_id=0)
	{
		$data = array();
		$data['page_title'] = 'Redeem';
		if(intval($redeem_request_id) > 0)
		{
			$data['edit'] = $this->sale->edit_gallerym($redeem_request_id);
			// print_r($data['edit']);
			// exit;
		}
		    $this->load->view('admin/create_redeem', $data);
	}




	public function saveSeries()
	{

		// print_r($_POST);exit;



		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$redeem_request_id =$this->input->post('redeem_request_id');
			$formArr['redeem_details'] = $redeem_details = (isset($_POST['redeem_details']) && !empty($_POST['redeem_details'])) ? $this->input->post('redeem_details') : '';
			$formArr['redeem_status'] = $redeem_status = (isset($_POST['redeem_status']) && !empty($_POST['redeem_status'])) ? $this->input->post('redeem_status') : '';

			   if(intval($redeem_request_id)>0)
				{
					    $this->sale->update_redeem($redeem_request_id,$formArr);
	                    $this->session->set_flashdata('success', 'Redeem Details Updated Successfully..');
	                    redirect('admin/Redeem_request/index');
                }
			          else
						{	
							$this->sale->save_redeem($formArr);
							  $this->session->set_flashdata('success', 'Redeem Details Added Successfully..');
			                  redirect('admin/Redeem_request/index');
						}	

			 
		}
		redirect('admin/Redeem_request/index');
		  
	}




	

	

}