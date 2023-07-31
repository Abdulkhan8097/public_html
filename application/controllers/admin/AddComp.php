<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AddComp extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Add_comp','addcomp');
	}


	public function index($product_id = 0)
	{
		$data = array();
		$data['page_title'] = 'Add Competitor';
		if(intval($product_id) > 0)
		{
			$data['get_product'] = $this->addcomp->getProduct($product_id);
		}
		    $data['get_comp'] = $this->addcomp->getComp();
		    $data['get_details'] =$this->addcomp->get_CompDetails($product_id);
		    // print_r($data['get_details']);
		    // exit;
		    $this->load->view('admin/add_competitor', $data);
	}

	public function saveComp()
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
	            $formArr['competitor_id '] = $competitor_id  = (isset($_POST['send_model_id'][$j]) && !empty($_POST['send_model_id'][$j])) ? $_POST['send_model_id'][$j] : '';
	            $formArr['product_id'] = $product_id = (isset($_POST['product_id'][$j]) && !empty($_POST['product_id'][$j])) ? $_POST['product_id'][$j] : '';
				$this->addcomp->insert_comp($formArr);
			}
            $this->session->set_flashdata('success', 'New Competitor Add successfully..');
            
            redirect(ADMIN_ADD_COMPTITOR_URL.$product_id);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect(ADMIN_ADD_COMPTITOR_URL);
        }
	}

	 public function getCity()
    {
        $append = '';
        if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
            $this->db->select('comp_part_no,competitor_id');
            $this->db->order_by('comp_part_no', 'ASC');
            $this->db->where('fk_com_master_id', $_POST['state_id']);
            $cities = $this->db->get('competitor')->result_array();
            if (isset($cities) && !empty($cities)) {
                $append .= "<option disabled selected value=''>-- Select Part Number --</option>";
                foreach ($cities  as $key => $value) {
                    $append .= "<option  value=" . $value['competitor_id'] . ">" . $value['comp_part_no'] . "</option>";
                }
            } else {
                $append .= "<option disabled selected value='' >Part Number not found</option>";
            }
        }
        echo $append;
        exit;
    }

    public function delete_comp($id = 0)
	{
		if(intval($id)>0)
		{

			$this->db->where('product_competitor_id', $id);
			$check_id = $this->db->get('product_competitor')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('product_competitor_id', $id);
					$this->db->delete('product_competitor');
					$this->session->set_flashdata('success', 'Model Deleted Successfully');
					redirect(ADMIN_ADD_COMPTITOR_URL.$product_id);
				}
			}
		}
	}

	 // public function delete_comp($id)//for deleting the user
  // {
    
  //   $delete=$this->addcomp->delete($id);
  //     if($delete)
  //       {
  //         echo "Success";
  //       }
  //    else
  //       {
  //         echo "Error";
  //       }

  // }
}