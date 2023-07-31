<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AddSeries extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Add_series','series');
	}


	public function index($product_id = 0)
	{
		$data = array();
		$data['page_title'] = 'Add Series';
		if(intval($product_id) > 0)
		{
			$data['get_product'] = $this->series->getProduct($product_id);
		}
			$data['get_seriesdetails'] = $this->series->get_seriesDetails($product_id);
			$data['vehicle'] = $this->series->getvehicle();
		    $data['get_series'] = $this->series->getSeries();
		    // print_r($data['get_series']);
		    // exit;
		    $this->load->view('admin/add_series', $data);
	}

	public function saveSeries()
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
	            $formArr['series_id	'] = $model_id = (isset($_POST['send_model_id'][$j]) && !empty($_POST['send_model_id'][$j])) ? $_POST['send_model_id'][$j] : '';
	            $formArr['product_id'] = $product_id = (isset($_POST['product_id'][$j]) && !empty($_POST['product_id'][$j])) ? $_POST['product_id'][$j] : '';
				$this->series->insert_series($formArr);
			}
            $this->session->set_flashdata('success', 'New Series added successfully..');
            
            redirect(ADMIN_ADD_SERIES_URL.$product_id);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect(ADMIN_ADD_SERIES_URL);
        }
	}

	 public function getCityDL()
    {
        $append = '';

        if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
            $this->db->select('series_name,series_id');
            $this->db->order_by('series_name', 'ASC');
            $this->db->where('fk_vm_id', $_POST['state_id']);
            $cities = $this->db->get('series1')->result_array();
            if (isset($cities) && !empty($cities)) {
                $append .= "<option disabled selected value=''>-- Select Series --</option>";
                foreach ($cities  as $key => $value) {
                    $append .= "<option  value=" . $value['series_id'] . ">" . $value['series_name'] . "</option>";
                }
            } else {
                $append .= "<option disabled selected value='' >Model not found</option>";
            }
        }
        echo $append;
        exit;
    }
}