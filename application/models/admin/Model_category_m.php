<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Model_category_m extends CI_model
{
	public function vechile_brand_data()
	{
		$this->db->select('*');
	return $this->db->get('vehicle_make')->result_array();
		
	}
	
	public function insert_category_model_data($formArr)
	{
		
   $this->db->insert('tbl_model_category',$formArr);
		
	}
	public function vechile_get_data()
	{
		$this->db->select('a.*,b.vm_id,vehicle_make_name,');
		$this->db->join('vehicle_make as b', 'a.fk_vm_category_id = b.vm_id', 'left');
	return $this->db->get('tbl_model_category as a')->result_array();
		
	}
	public function edit_data($category_model_id)
    {
    	$this->db->select('*');
    	$this->db->where('category_model_id', $category_model_id);
    	return $this->db->get('tbl_model_category')->row_array();
    }


	 public function update_data($category_model_id,$formArr)
    {
        $this->db->where('category_model_id', $category_model_id);
        $this->db->update('tbl_model_category', $formArr);
    }

 public function updateStatus($id,$formArr)
	{
		 $this->db->where('category_model_id',$id);
       $this->db->update('tbl_model_category',$formArr);

	}


}

 ?>