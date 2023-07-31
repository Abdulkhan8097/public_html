<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vechicle extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Vechicle_model','vechicle');
	}


	public function index($vm_id=0)
	{
		$data = array();
		$data['page_title'] = 'Add Vehicle';
		$data['vechicle'] = $this->vechicle->getVechicle();
		if(intval($vm_id) > 0)
		{
			$data['edit'] = $this->vechicle->edit_vechicle($vm_id);
		}
		    $this->load->view('admin/add_vehicle', $data);
	}
	public function saveVechicle()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$vm_id =$this->input->post('vm_id');
			$formArr['vehicle_make_name'] = $vehicle_make_name = (isset($_POST['vehicle_make_name']) && !empty($_POST['vehicle_make_name'])) ? $this->input->post('vehicle_make_name') : '';
$formArr['vm_sequence'] = $vm_sequence = (isset($_POST['vm_sequence']) && !empty($_POST['vm_sequence'])) ? $this->input->post('vm_sequence') : '';
if(isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|jpg|jpeg|png';
                  $config['max_size']      = 10000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('image'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['image'] = $upload_data['file_name'];
                     }
              }

					if(intval($vm_id) > 0)
				{
					    $this->vechicle->update_vechicle($vm_id,$formArr);
	                    $this->session->set_flashdata('success', 'Vechicle Updated Successfully..');
	                    redirect(ADMIN_ADD_VEHICLE_URL);
                }
			          else
						{
					    $this->vechicle->insert_vechicle($formArr);
					    $this->session->set_flashdata('success', 'Vechicle Added Successfully..');
			            redirect(ADMIN_ADD_VEHICLE_URL);
						}
			
			}
		redirect(ADMIN_ADD_VEHICLE_URL);
	}
}