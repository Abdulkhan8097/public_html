<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
     $this->load->library('form_validation');
		 $this->load->model('admin/Product_model','product');
	}


	public function index()
	{
		$data = array();
		$data['page_title'] = 'Product List';
		$data['product'] = $this->product->get_product();
		$this->load->view('admin/product_list', $data);
	}

	public function addProduct($product_id=0)
	{
		$data = array();
		$data['page_title'] = 'Add Product';
            if(intval($product_id) > 0)
            {
                  $data['edit_product'] = $this->product->edit_product($product_id);
            }
		        $data['categories'] = $this->product->get_categories();
            $data['model'] = $this->product->get_model();
            $data['get_series'] = $this->product->get_series();
            // $data['model'] = $this->product->model('model_id');
            // print_r($data['model']);
            // exit;
		        $this->load->view('admin/add_product', $data);
	}

	public function saveProduct()
  	{
       $this->form_validation->set_rules(
        'eve_part_no', 'Everest Number',
        'is_unique[product.eve_part_no]',
        array(
                'is_unique'     => ''
        )
);
        if ($this->form_validation->run() == FALSE)
                {
                       
                        redirect(ADMIN_ADD_PRODUCT_URL);
                        $this->session->set_flashdata('failed', 'Part Number Already Exits..');
                }
                else
                {

		if (isset($_POST) && !empty($_POST)) {
            $formArr = array();
            $formArr['category_id'] = $category_id = (isset($_POST['category_id']) && !empty($_POST['category_id'])) ? $this->input->post('category_id') : '';
            $formArr['cross_size'] = $cross_size = (isset($_POST['cross_size']) && !empty($_POST['cross_size'])) ? $this->input->post('cross_size') : '';

            $formArr['plate_design'] = $plate_design = (isset($_POST['plate_design']) && !empty($_POST['plate_design'])) ? $this->input->post('plate_design') : '';
            $formArr['plate_diameter'] = $plate_diameter = (isset($_POST['plate_diameter']) && !empty($_POST['plate_diameter'])) ? $this->input->post('plate_diameter') : '';
            $formArr['hole_size'] = $hole_size = (isset($_POST['hole_size']) && !empty($_POST['hole_size'])) ? $this->input->post('hole_size') : '';
            $formArr['bolt_no'] = $bolt_no = (isset($_POST['bolt_no']) && !empty($_POST['bolt_no'])) ? md5($this->input->post('bolt_no')) : '';
            $formArr['height'] = $height = (isset($_POST['height']) && !empty($_POST['height'])) ? $this->input->post('height') : '';
             $formArr['eve_part_no'] = $eve_part_no = (isset($_POST['eve_part_no']) && !empty($_POST['eve_part_no'])) ? $this->input->post('eve_part_no') : '';
             $formArr['model_display'] = $model_display = (isset($_POST['model_display']) && !empty($_POST['model_display'])) ? $this->input->post('model_display') : '';
             $formArr['stock'] = $stock = (isset($_POST['stock']) && !empty($_POST['stock'])) ? $this->input->post('stock') : '';
             $formArr['mrp'] = $mrp = (isset($_POST['mrp']) && !empty($_POST['mrp'])) ? $this->input->post('mrp') : '';
             $formArr['landing_price'] = $landing_price = (isset($_POST['landing_price']) && !empty($_POST['landing_price'])) ? $this->input->post('landing_price') : '';
             $formArr['remark'] = $remark = (isset($_POST['remark']) && !empty($_POST['remark'])) ? $this->input->post('remark') : '';
             $formArr['no_of_spline'] = $no_of_spline = (isset($_POST['no_of_spline']) && !empty($_POST['no_of_spline'])) ? $this->input->post('no_of_spline') : '';
             $formArr['mating_part_no'] = $mating_part_no = (isset($_POST['mating_part_no']) && !empty($_POST['mating_part_no'])) ? $this->input->post('mating_part_no') : '';
             $formArr['child_parts'] = $child_parts = (isset($_POST['child_parts']) && !empty($_POST['child_parts'])) ? $this->input->post('child_parts') : '';
             $formArr['discounted_price'] = $discounted_price = (isset($_POST['discounted_price']) && !empty($_POST['discounted_price'])) ? $this->input->post('discounted_price') : '';
             $formArr['pipe_diameter'] = $pipe_diameter = (isset($_POST['pipe_diameter']) && !empty($_POST['pipe_diameter'])) ? $this->input->post('pipe_diameter') : '';
             $formArr['length'] = $length = (isset($_POST['length']) && !empty($_POST['length'])) ? $this->input->post('length') : '';
             $formArr['cup_size'] = $cup_size = (isset($_POST['cup_size']) && !empty($_POST['cup_size'])) ? $this->input->post('cup_size') : '';
             $formArr['crosslock_length'] = $crosslock_length = (isset($_POST['crosslock_length']) && !empty($_POST['crosslock_length'])) ? $this->input->post('crosslock_length') : '';
             $formArr['description'] = $description = (isset($_POST['description']) && !empty($_POST['description'])) ? $this->input->post('description') : '';
             $formArr['no_of_teeths'] = $no_of_teeths = (isset($_POST['no_of_teeths']) && !empty($_POST['no_of_teeths'])) ? $this->input->post('no_of_teeths') : '';
             $formArr['idod'] = $idod = (isset($_POST['idod']) && !empty($_POST['idod'])) ? $this->input->post('idod') : '';
             $formArr['yoke_length'] = $yoke_length = (isset($_POST['yoke_length']) && !empty($_POST['yoke_length'])) ? $this->input->post('yoke_length') : '';
             $formArr['teeth_length'] = $teeth_length = (isset($_POST['teeth_length']) && !empty($_POST['teeth_length'])) ? $this->input->post('teeth_length') : '';
             $formArr['bearing_no'] = $bearing_no = (isset($_POST['bearing_no']) && !empty($_POST['bearing_no'])) ? $this->input->post('bearing_no') : '';
             $formArr['type'] = $type = (isset($_POST['type']) && !empty($_POST['type'])) ? $this->input->post('type') : '';
             $formArr['total_length'] = $total_length = (isset($_POST['total_length']) && !empty($_POST['total_length'])) ? $this->input->post('total_length') : '';
             $formArr['steeve_yoke_length'] = $steeve_yoke_length = (isset($_POST['steeve_yoke_length']) && !empty($_POST['steeve_yoke_length'])) ? $this->input->post('steeve_yoke_length') : '';
             $formArr['rear_teeth_length'] = $rear_teeth_length = (isset($_POST['rear_teeth_length']) && !empty($_POST['rear_teeth_length'])) ? $this->input->post('rear_teeth_length') : '';
             $formArr['bearing_nodiameter'] = $bearing_nodiameter = (isset($_POST['bearing_nodiameter']) && !empty($_POST['bearing_nodiameter'])) ? $this->input->post('bearing_nodiameter') : '';
             $formArr['half_yoke_length'] = $half_yoke_length = (isset($_POST['half_yoke_length']) && !empty($_POST['half_yoke_length'])) ? $this->input->post('half_yoke_length') : '';
             $formArr['application'] = $application = (isset($_POST['application']) && !empty($_POST['application'])) ? $this->input->post('application') : '';
             $formArr['gst'] = $gst = (isset($_POST['gst']) && !empty($_POST['gst'])) ? $this->input->post('gst') : '';
             $formArr['everest_cross'] = $everest_cross = (isset($_POST['everest_cross']) && !empty($_POST['everest_cross'])) ? $this->input->post('everest_cross') : '';
             $formArr['no_of_holes'] = $no_of_holes = (isset($_POST['no_of_holes']) && !empty($_POST['no_of_holes'])) ? $this->input->post('no_of_holes') : '';
             $formArr['oil_seal'] = $oil_seal = (isset($_POST['oil_seal']) && !empty($_POST['oil_seal'])) ? $this->input->post('oil_seal') : '';
             $formArr['equivalent_no'] = $equivalent_no = (isset($_POST['equivalent_no']) && !empty($_POST['equivalent_no'])) ? $this->input->post('equivalent_no') : '';
             $formArr['oe_competitor_no'] = $oe_competitor_no = (isset($_POST['oe_competitor_no']) && !empty($_POST['oe_competitor_no'])) ? $this->input->post('oe_competitor_no') : '';
             $formArr['coupon'] = $coupon = (isset($_POST['coupon']) && !empty($_POST['coupon'])) ? $this->input->post('coupon') : '';
              // $formArr['created_by'] = $this->session->userdata('user_id');
             $formArr['created_date'] = $created_date = date('Y-m-d H:i:s');            
             $product_id = $this->input->post('product_id');
             if(isset($_FILES["profile_img"]["name"]) && !empty($_FILES["profile_img"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|jpg|jpeg|png';
                  $config['max_size']      = 10000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('profile_img'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['profile_img'] = $upload_data['file_name'];
                     }
              }

                  if(intval($product_id) > 0)
                        {
                          $this->product->update_product($product_id,$formArr);
                          $this->session->set_flashdata('success', 'Product Updated Successfully..');
                          redirect(ADMIN_PRODUCT_URL);
                        }
                    else
                        {

                          $this->product->save_product($formArr);

                          $last_id = $this->db->insert_id();
                          // print_r($last_id);
                          // exit;
                          if(intval($last_id) > 0)
                          {
                              $formArr1 = array();
                              $formArr1['series_id'] = $series_id = (isset($_POST['series_id']) && !empty($_POST['series_id'])) ? $this->input->post('series_id') : '';
                              $formArr1['product_id']= $last_id;
                              $this->product->insert_series($formArr1);
                              $formArr2 = array();
                              $formArr2['model_id'] = $model_id = (isset($_POST['model_id']) && !empty($_POST['model_id'])) ? $this->input->post('model_id') : '';
                              $formArr2['product_id']= $last_id;
                              $this->product->insert_model($formArr2);
                              $this->session->set_flashdata('success', 'New Product added successfully..');
                              redirect(ADMIN_PRODUCT_URL);
                          }

                        }
            redirect(ADMIN_PRODUCT_URL);
        }   else {
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect(ADMIN_PRODUCT_URL);
        }
    }

  	}

	
}