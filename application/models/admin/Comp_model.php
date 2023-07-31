<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Comp_model extends CI_model
{
	public function save_comp($formArr)
	{
		$this->db->insert('competitor_discount', $formArr);
	}
	function all()
	{
	$this->db->select('*');
	return $this->db->get('competitor_discount')->result_array();
	}
    public function edit_comp($competitor_discount_id)
    {
    	$this->db->select('*');
    	$this->db->where('competitor_discount_id', $competitor_discount_id);
    	return $this->db->get('competitor_discount')->row_array();
    }
    public function getCompany()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('competitor_master')->result_array();
	}
    public function update_comp($competitor_discount_id,$formArr)
    {
        $this->db->where('competitor_discount_id', $competitor_discount_id);
        $this->db->update('competitor_discount',$formArr);
    }
    public function get_discount()
    {
        $this->db->select('a.discount,b.company_name,a.competitor_discount_id');
        // $this->db->where('a.product_id', $product_id);
        $this->db->join('competitor_master as b', 'b.id = a.company_name2', 'left');
        // echo $this->db->last_query();exit;
        return $this->db->get('competitor_discount as a')->result_array();     
        // $admin= $this->db->get('competitor_discount as a')->result_array(); 
        // print_r($admin);
        // exit;
    }
	
}

 ?>