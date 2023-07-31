<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('isLoggedIn') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Categories', 'category');
	}

	public function index($category_id=0)
	{
		$data = array();
		$data['page_title'] = 'Category';
		if(intval($category_id) > 0)
		{
			$data['edit'] = $this->category->edit_category($category_id);
			// print_r($data['edit']);
			// exit;
		}
		$data['list'] = $this->category->all(); 
		$this->load->view('admin/category_list.php', $data);
	}

	public function getParameter($id=0)
	{
		$data = array();
		$data['page_title'] = 'Paramater List';
		$data['list'] = $this->category->gerParameterDetails($id);
		$data['category'] =$this->category->getCategory($id);
		$this->load->view('admin/parameter_list', $data);
	}

	public function getProduct($id=0)
	{
		$data = array();
		$data['page_title'] = 'Product List';
		$data['product'] = $this->category->getProduct($id);
		$this->load->view('admin/product_list', $data);
	}


	// public function save_category()
 //    {
 //      $category_name = $this->input->post('category_name');
 //      $sequence_no = $this->input->post('sequence_no');
 //      $category_id = $this->input->post('category_id');
 //      // print_r($category_id);
 //      // exit;
 //      $remark = $this->input->post('remark');
 //      if($category_name !="" AND $sequence_no != "")
 //      {
 //        $checkuser=$this->category->checkcategory($category_name);
 //        $countexistuser=count($checkuser);
 //        if($countexistuser==0)
 //        {          
 //          $data = array(
 //          	'category_id'  =>$category_id,
 //            'category_name'    => $category_name,
 //            'sequence_no'    => $sequence_no,
 //            'remark'    => $remark,           
 //        );
 //          // print_r($_FILES);
 //          // exit;
 //          // if(isset($_FILES["category_image"]["name"]) && !empty($_FILES["category_image"]["name"]))
 //          //     {	
 //          //         $config['upload_path'] = PROFILE_UPLOAD_PATH_NAME;
 //          //         $config['allowed_types'] = 'gif|jpg|jpeg|png';
 //          //         $config['max_size']      = 10000;
 //          //         $config['encrypt_name'] = TRUE;
 //          //         $this->load->library('upload', $config);
 //          //         if($this->upload->do_upload('image'))
 //          //            {
 //          //                 $upload_data = $this->upload->data();
 //          //                 $data['category_image'] = $upload_data['file_name'];
 //          //            }
 //          //     }

 //              if(($category_id)>0)
	// 			{
	// 				    $this->category->update_category($category_id,$data);
	//                     $this->session->set_flashdata('success', 'Category Updated Successfully..');
	//                     redirect(ADMIN_CATEGORY_URL );
 //                }
	// 		          else
	// 					{
	// 						  $this->category->save_category($data);
	// 				          $this->session->set_flashdata('success', 'Category Added successfully.');
	// 				          redirect(ADMIN_CATEGORY_URL);
	// 					}	
          
 //        }
 //        else
 //        {
 //          $this->session->set_flashdata('error', 'Category Already Exist.');
 //          redirect(ADMIN_CATEGORY_URL);
 //        }
 //      }
 //      else
 //      {
 //        $this->session->set_flashdata('error', 'Field should not be blank.');
 //        redirect(ADMIN_CATEGORY_URL);
 //      }
 //      redirect(ADMIN_CATEGORY_URL);
 //    }



	public function save_category()
	{
		if(isset($_POST) &&!empty($_POST))
		{
			$formArr = array();
			$category_id =$this->input->post('category_id');
			$formArr['category_name'] = $category_name = (isset($_POST['category_name']) && !empty($_POST['category_name'])) ? $this->input->post('category_name') : '';
			$formArr['sequence_no'] = $sequence_no = (isset($_POST['sequence_no']) && !empty($_POST['sequence_no'])) ? $this->input->post('sequence_no') : '';

			if(isset($_FILES["category_image"]["name"]) && !empty($_FILES["category_image"]["name"]))
              {
                  $config['upload_path']          = PROFILE_UPLOAD_PATH_NAME;
                  $config['allowed_types']        = 'gif|jpg|jpeg|png';
                  $config['max_size']      = 10000;
                  $config['encrypt_name'] = TRUE;
                  $this->load->library('upload', $config);
                  if($this->upload->do_upload('category_image'))
                     {
                          $upload_data = $this->upload->data();
                          $formArr['category_image'] = $upload_data['file_name'];
                     }
              }


			   if(intval($category_id)>0)
				{
					    $this->category->update_category($category_id,$formArr);
	                    $this->session->set_flashdata('success', 'Category Updated Successfully..');
	                    redirect(ADMIN_CATEGORY_URL );
                }
			          else
						{
							  $this->category->save_category($formArr);
							  $this->session->set_flashdata('success', 'Category Added Successfully..');
			                  redirect(ADMIN_CATEGORY_URL);
						}	

			 
		}
		redirect(ADMIN_CATEGORY_URL );
		  
	}

	public function delete_category($category_id = 0)
	{
		if(intval($category_id)>0)
		{
			$this->db->where('category_id', $category_id);
			$check_id = $this->db->get('category')->row_array();
			{
				if($check_id > 0)
				{
					$this->db->where('category_id', $category_id);
					$this->db->delete('category');
					$this->session->set_flashdata('success', 'Category Deleted Successfully');
					redirect(ADMIN_CATEGORY_URL);
				}
			}
		}
	}

}