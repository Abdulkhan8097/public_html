<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Add_model extends CI_model
{
	 
	public function getProduct($product_id)
	{
		$this->db->select('*');
		$this->db->where('product_id', $product_id);
		return $this->db->get('product')->row_array();
	}

	public function getModel()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('model')->result_array();
	}
	public function insert_model($formArr)
	
	{
		$this->db->insert('model_product', $formArr);
	}

	public function getvehicle()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('vehicle_make')->result_array();
	}

	public function get_modelDetails($product_id)
    {
        $this->db->select('a.product_id,a.model_product_id,b.model_name,c.eve_part_no,d.vehicle_make_name');
        $this->db->where('a.product_id', $product_id);
        $this->db->join('model as b', 'a.model_id = b.model_id', 'left');
        $this->db->join('product as c', 'a.product_id = c.product_id', 'left');
        $this->db->join('vehicle_make as d', 'b.fk_vm_id = d.vm_id', 'left');
        return $this->db->get('model_product as a')->result_array();     
    }
}

 ?>