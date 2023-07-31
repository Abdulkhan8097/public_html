<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Add_comp extends CI_model
{
	 
	public function getProduct($product_id)
	{
		$this->db->select('*');
		$this->db->where('product_id', $product_id);
		return $this->db->get('product')->row_array();
	}

	public function getComp()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('competitor_master')->result_array();
	}
	public function insert_comp($formArr)
	
	{
		$this->db->insert('product_competitor', $formArr);
	}
	public function get_CompDetails($product_id)
    {
        $this->db->select('a.product_id,a.product_competitor_id,b.comp_part_no,c.eve_part_no');
        $this->db->where('a.product_id', $product_id);
        $this->db->join('competitor as b', 'a.competitor_id = b.competitor_id', 'left');
        $this->db->join('product as c', 'a.product_id = c.product_id', 'left');
        return $this->db->get('product_competitor as a')->result_array();     
    }
    public function delete($id){
    $this -> db -> where('product_competitor_id', $id);
    $this -> db -> delete('product_competitor');
}
}

 ?>