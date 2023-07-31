<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Stock_adj_model extends CI_model
{
	

	public function fetch_part_no()
	{
		
			$this->db->select('*');

                        
			return $this->db->get('product')->result_array();
			
			
	}

	public function chacked_data($product_id){
		$this->db->select('product_id',$product_id);
		return $this->db->get('product')->result_array();


	}
	public function listing($product_id,$user_id){


		 $this->db->where('product_id',$product_id);
		 $this->db->where('user_id',$user_id);
		 $this->db->join('tbl_inventory_master as b', 'b.fk_product_id = a.product_id', 'left');

		 return $this->db->get('product as a')->result_array();
		
	}








	public function get_fetch($product_id,$user_id){
		// check data available or not available from database in this product_id

$this->db->select('*');
$this->db->from('tbl_inventory_master');
$this->db->where('fk_product_id', $product_id);
$this->db->where('user_id',$user_id);

return $this->db->get()->row_array();

// SELECT current_stock FROM `tbl_inventory_master` WHERE fk_product_id='550' and user_id=user id;

	}
	public function insert_data($product_id,$stock_adjustment,$user_id){


$array = array('fk_product_id' => $product_id, 'current_stock' => $stock_adjustment, 'user_id' => $user_id);

$this->db->set($array);
return $this->db->insert('tbl_inventory_master');

	// 	 $this->db->where('fk_product_id',$product_id);
	// return $this->db->insert('tbl_inventory_master');


	}
	public function get_stock($user_id){

		 $this->db->select('b.*,a.*');
		 $this->db->where('a.user_id',$user_id);
		
		 $this->db->join('product as b', 'b.product_id = a.fk_product_id', 'left');

		 return $this->db->get('tbl_inventory_master as a')->result_array();

	
		
	}


	 // public function edit_stock($product_id)
  //   {
  //   	$this->db->select('*');
  //   	$this->db->where('product_id',$product_id);
  //   	return $this->db->get('tbl_inventory_master')->row_array();
  //   }

		public function update_stock_master($product_id,$closing_balance,$user_id){



			// $this->db->query("update tbl_inventory_master form SET 'current_stock'= $closing_balance where 'fk_product_id'= $product_id  AND 'user_id'= $user_id");
			$this->db->where('fk_product_id',$product_id);
			$this->db->where('user_id',$user_id);



 $array = array('current_stock' =>$closing_balance);

 $this->db->set($array);

 $this->db->update('tbl_inventory_master');
// 		 // update tbl_inventory_master set current_stock= $closing Balance;
	
	}
public function insert_inventory_history($product_id,$user_id,$stock_adjustment,$stock_in_value,$stock_ot_value,$opening_balance,$closing_balance){
$array = array('fk_product_id' => $product_id,  'user_id' => $user_id, 'stock_in' => $stock_in_value, 'stock_out' => $stock_ot_value,'opening_bal' => $opening_balance, 'closing_bal'=> $closing_balance);

$this->db->set($array);
return $this->db->insert('tbl_inventory_history');

	
		
	}

	

 
	

	
}

 ?>