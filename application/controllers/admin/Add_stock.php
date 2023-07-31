<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Add_stock extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('user_id') == FALSE) {
			$this->session->set_flashdata('failed', 'Your session has expired.Please Login.');
			redirect(site_url('admin/login'));
		}
		$this->load->model('admin/Stock_adj_model','stock');
	}

	public function index($user_id=0)

	{
		// print_r($_POST['SESSION']);
		// exit;
		$data = array();
		$user_id= $this->session->userdata('user_id');

		$data['page_title'] = 'Stock_Adjustment';
		
			$data['stock_data_list'] = $this->stock->get_stock($user_id);
			  // print_r($data['stock_data_list']);
			  // exit;
			//print_r($this->db->last_query());
			//exit;

			// print_r($data['stock_data_list']);
			// exit;
		

		$data['fetch_data'] = $this->stock->fetch_part_no();
		// print_r($data['fetch_data']);
		// exit;
		

		
		$this->load->view('admin/add_stock',$data);
	}
	public function sava_data($product_id=0){
	    $data = array();

		$product_id= $this->input->post('fk_product_id');
		  $current_stock= $this->input->post('current_stock');
		  $user_id= $this->session->userdata('user_id');

		 $radio= $this->input->post('radio');

		$Result= $this->stock->get_fetch($product_id,$user_id);
// $this->db->insert_id();
		


if(intval($Result != ""))
	{

		// echo "<pre>";
		// echo"records found";
		
 // print_r($Result);
 // exit;
		
        $data = array();
         $product_id= $this->input->post('fk_product_id');
 		 $stock_adjustment= $this->input->post('current_stock');
 		 $user_id= $this->session->userdata('user_id');
 		 $radio= $this->input->post('radio');



 		 if($radio == '1')
	 {
	 				 
	 				 $old_value=$Result['current_stock'];
	 				 // print_r($old_value);
	 				 // exit;
       $opening_balance= $old_value;
      //   print_r($opening_balance);
	 				// exit;
	 	// echo"value is plus";
	 	$stock_in_value=$stock_adjustment;

	 
	 	$stock_ot_value=0;
	 	$closing_balance = $opening_balance+$stock_in_value;
	 	// print_r($closing_balance);
	 	// exit;

$this->stock->insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance);



	$this->stock->update_stock_master($product_id,$closing_balance,$user_id);
	 redirect('admin/Add_stock/index');
		// print_r($result);
		// exit;

		
	 
	 	// $this->stock->insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance);
	 
	 }


	 else{
	// echo"value is minus";
	 	$old_value=$Result['current_stock'];
	 	 // print_r($old_value);
	 		// 		 exit;
      $opening_balance=$old_value;
		$stock_in_value=0; 
	$stock_ot_value=$stock_adjustment;
	$closing_balance =$opening_balance-$stock_ot_value ;
	// print_r($closing_balance);
	// exit;
	$this->stock->insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance);
	$this->stock->update_stock_master($product_id,$closing_balance,$user_id);
	 redirect('admin/Add_stock/index');
	}


}elseif (intval($Result = 1)) {
 		# code...
 
 		// echo"recourd not found";
 		

 		 $data = array();
 		 $product_id= $this->input->post('fk_product_id');
 		 $stock_adjustment= $this->input->post('current_stock');
 		 $user_id= $this->session->userdata('user_id');

 		  $radio= $this->input->post('radio');

 		//   print_r($radio);
		 // exit;


 		 

 	if($radio == '1')
	 {
       $opening_balance=0;
	 	// echo"value is plus";
	 	$stock_in_value=$stock_adjustment;

	 
	 	$stock_ot_value=0;
	 	$closing_balance =$opening_balance + $stock_in_value;
	 	$this->stock->insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance);


	 
	 }


	 else{
	// echo"value is minus";
     $opening_balance=0;
		$stock_in_value=0; 
	$stock_ot_value=$stock_adjustment;
	$closing_balance =$opening_balance - $stock_ot_value;
	$this->stock->insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance);
	}

 $this->stock->insert_data($product_id,$stock_adjustment,$user_id);



 		 redirect('admin/Add_stock/index');
 		

 		// echo "If No records found";
 	}	
 
 		

 	}




	

}


