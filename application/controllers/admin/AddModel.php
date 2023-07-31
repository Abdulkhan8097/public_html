<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AddModel extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Add_model','addmodel');
	}

	public function index($product_id = 0)
	{
		$data = array();
		$data['page_title'] = 'Add Model';
		
			
		if(intval($product_id) > 0)
		{
			$data['get_product'] = $this->addmodel->getProduct($product_id);
		}	
		    $this->load->view('admin/add_model', $data);
	}

	// public function saveModel()
	// {
	// 	$i=0;
	// 	foreach ($_POST['send_model_id'] as $key => $value) {
	// 		$i++;
	// 	}
 //        if (isset($_POST) && !empty($_POST)) {

 //            $formArr = array();
 //            // $user_id = $this->input->post('user_id');
 //            for($j=0; $j<$i; $j++)
 //            {
	//             $formArr['model_id'] = $model_id = (isset($_POST['send_model_id'][$j]) && !empty($_POST['send_model_id'][$j])) ? $_POST['send_model_id'][$j] : '';
	//             $formArr['product_id'] = $product_id = (isset($_POST['product_id'][$j]) && !empty($_POST['product_id'][$j])) ? $_POST['product_id'][$j] : '';
	// 			$this->addmodel->insert_model($formArr);
	// 		}
 //            $this->session->set_flashdata('success', 'New Model added successfully..');
            
 //            redirect(ADMIN_ADD_MODEL_URL.$product_id);
 //        } else {
 //            $this->session->set_flashdata('error', 'Something went wrong.');
 //            redirect(ADMIN_ADD_MODEL_URL);
 //        }
	// }

	//  public function getCityDLL()
 //    {
 //        $append = '';

 //        if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
 //            $this->db->select('model_name,model_id');
 //            $this->db->order_by('model_name', 'ASC');
 //            $this->db->where('fk_vm_id', $_POST['state_id']);
 //            $cities = $this->db->get('model')->result_array();
            
 //            if (isset($cities) && !empty($cities)) {
 //                $append .= "<option disabled selected value=''>-- Select Model --</option>";
 //                foreach ($cities  as $key => $value) {
 //                    $append .= "<option  value=" . $value['model_id'] . ">" . $value['model_name'] . "</option>";
 //                }
 //                echo '<pre>';
 //                print_r($cities);
            
 //            } else {
 //                $append .= "<option disabled selected value='' >Model not found</option>";
 //            }
 //        }
 //        echo $append;
 //        exit;
 //    }

 //    public function delete_model($id = 0)
	// {
	// 	if(intval($id)>0)
	// 	{
	// 		$this->db->where('model_product_id', $id);
	// 		$check_id = $this->db->get('model_product')->row_array();
	// 		{
	// 			if($check_id > 0)
	// 			{
	// 				$this->db->where('model_product_id', $id);
	// 				$this->db->delete('model_product');
	// 				$this->session->set_flashdata('success', 'Model Deleted Successfully');
	// 				redirect(ADMIN_ADD_MODEL_URL.$id);
	// 			}
	// 		}
	// 	}
		
	// }
    
}