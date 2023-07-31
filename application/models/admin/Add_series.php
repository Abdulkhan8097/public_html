<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Add_series extends CI_model
{
	 
	public function getProduct($product_id)
	{
		$this->db->select('*');
		$this->db->where('product_id', $product_id);
		return $this->db->get('product')->row_array();
	}

	public function getSeries()
	{
		$this->db->select('*');
		
		return $this->db->get('series')->result_array();
		// $admin=$this->db->get('series1')->result_array();
		// print_r($admin);
		// exit;

	}
	public function getvehicle()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('vehicle_make')->result_array();
	}
	public function insert_series($formArr)
	
	{
		$this->db->insert('product_series', $formArr);
	}

	public function get_seriesDetails($product_id)
    {
        $this->db->select('a.product_id,a.product_series_id,b.series_name,c.eve_part_no');
        $this->db->where('a.product_id', $product_id);
        $this->db->join('series as b', 'a.series_id = b.series_id', 'left');
        $this->db->join('product as c', 'a.product_id = c.product_id', 'left');
        return $this->db->get('product_series as a')->result_array(); 
       // $admin= $this->db->get('product_series as a')->result_array(); 
       // print_r($admin);
       // exit;    
    }
}

 ?>