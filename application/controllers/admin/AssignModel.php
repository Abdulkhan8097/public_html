<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AssignModel extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Assign','assign');
	}


	public function index($model_id=0)
	{
		$data = array();
		$data['page_title'] = 'Assign Model';
		$data['model'] = $this->assign->getModels();
		$data['category'] = $this->assign->getCategories();
		if(intval($model_id) > 0)
		{
			$data['get_number'] = $this->assign->get_number($model_id);
		}
		$data['details'] = $this->assign->get_modelDetails($model_id);		
		$this->load->view('admin/assign_model', $data);
	}

	public function saveAssign()
	{
		$i=0;
		foreach ($_POST['send_model_id'] as $key => $value) {
			$i++;
		}
        if (isset($_POST) && !empty($_POST)) {

            $formArr = array();
            // $user_id = $this->input->post('user_id');
            for($j=0; $j<$i; $j++)
            {
	            $formArr['product_id'] = $model_id  = (isset($_POST['send_model_id'][$j]) && !empty($_POST['send_model_id'][$j])) ? $_POST['send_model_id'][$j] : '';
	            $formArr['model_id'] = $product_id = (isset($_POST['product_id'][$j]) && !empty($_POST['product_id'][$j])) ? $_POST['product_id'][$j] : '';
				$this->assign->insert_assign($formArr);
			}
            $this->session->set_flashdata('success', 'New Model Assign successfully..');  
            redirect(ADMIN_ASSIGN_MODEL_URL.$product_id);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect(ADMIN_ASSIGN_MODEL_URL);
        }
	}

	 public function getCity()
    {
        $append = '';
        if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
            $this->db->select('eve_part_no,product_id ');
            $this->db->order_by('eve_part_no', 'ASC');
            $this->db->where('category_id ', $_POST['state_id']);
            $cities = $this->db->get('product')->result_array();
            if (isset($cities) && !empty($cities)) {
                $append .= "<option disabled selected value=''>-- Select Part Number --</option>";
                foreach ($cities  as $key => $value) {
                    $append .= "<option  value=" . $value['product_id'] . ">" . $value['eve_part_no'] . "</option>";
                }
            } else {
                $append .= "<option disabled selected value='' >Part Number not found</option>";
            }
        }
         echo $append;           
        }


public function getCount()
    {
       if (isset($_POST['model_id']) && !empty($_POST['model_id'])) {
        	$appends='';
        	$this->db->select('a.product_id');
            // $this->db->order_by('eve_part_no', 'ASC');
            $this->db->where('category_id', $_POST['model_id']);
            $this->db->join('product as b', 'b.product_id = a.product_id', 'left');
            $query = $this->db->get('model_product as a')->result_array();
            // print_r($query);
            // exit;
            $numbers=count($query);
            if (isset($numbers) && !empty($numbers)) {
               
                    $appends .= "<option>" . $numbers . "</option>";
            } else {
                $appends .= "<option disabled selected value='' >Not Foun</option>";
            }
            }
          
            echo $appends;
       
    
  } 
       
    

    public function delete_model($id = 0)
	{
		if(intval($id)>0)
		{

			$this->db->where('model_product_id', $id);
			$check_id = $this->db->get('model_product')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('model_product_id ', $id);
					$this->db->delete('model_product');
					$this->session->set_flashdata('success', 'Model Product Deleted Successfully');
					redirect(ADMIN_ASSIGN_MODEL_URL.$product_id);
				}
			}
		}
	}

	
	
         
       
    
  } 
