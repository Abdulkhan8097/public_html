<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CompPart extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Part_comp','part');
	}

	public function getCompProduct($id=0)
	{
		$data = array();
		$data['page_title'] = 'Product list';
		$data['product'] = $this->part->getComptetor($id);
		// echo '<pre>';
		// print_r($data['product']);
		// exit;
		$this->load->view('admin/product_list', $data);
	}


	public function index($competitor_id=0)
	{
		$data = array();
		$data['page_title'] = 'Competitor Part';
		$data['getComp'] = $this->part->getCompany();
		$data['part'] = $this->part->get_PartDetails();
		if(intval($competitor_id)>0)
		{
			$data['edit'] = $this->part->getPart($competitor_id);
		}	
		$this->load->view('admin/comp_part', $data);
	}

	public function saveCompPart()
	{
		// print_r($_POST);
		// exit;
		if(isset($_POST) && !empty($_POST))
		{
			$formArr = array();
			$id =$this->input->post('id');
			$formArr['fk_com_master_id'] = $fk_com_master_id = (isset($_POST['fk_com_master_id']) && !empty($_POST['fk_com_master_id'])) ? $this->input->post('fk_com_master_id') : '';	
			$formArr['comp_part_no'] = $comp_part_no = (isset($_POST['comp_part_no']) && !empty($_POST['comp_part_no'])) ? $this->input->post('comp_part_no') : '';
			$formArr['mrp'] = $mrp = (isset($_POST['mrp']) && !empty($_POST['mrp'])) ? $this->input->post('mrp') : '';	
			$formArr['remark'] = $remark = (isset($_POST['remark']) && !empty($_POST['remark'])) ? $this->input->post('remark') : '';		
			$formArr['eff_date'] = $eff_date = (isset($_POST['eff_date']) && !empty($_POST['eff_date'])) ? $this->input->post('eff_date') : '';		

			// if(intval($id)>0)
			// 	{
			// 		    $this->part->update_part($competitor_id,$formArr);
	  //                   $this->session->set_flashdata('success', 'Part Updated Successfully..');
	  //                   redirect(ADMIN_ADD_COMPTITOR_PART_URL);
   //              }
   //              else
			// 			{
							 $this->part->insert_part($formArr);
							  $this->session->set_flashdata('success', 'Part Added Successfully..');
			                  redirect(ADMIN_ADD_COMPTITOR_PART_URL);
						// }	
			
			}
		redirect(ADMIN_BANNER_URL );	  
	}


	public function deletepart($competitor_id = 0)
	{
		if(intval($competitor_id)>0)
		{
			$this->db->where('competitor_id', $competitor_id);
			$check_id = $this->db->get('competitor')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('competitor_id', $competitor_id);
					$this->db->delete('competitor');
					$this->session->set_flashdata('success', 'Part Deleted Successfully');
					redirect(ADMIN_ADD_COMPTITOR_PART_URL);
					
				}
			}
		}
	}

	
}