<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Add_competitor_model extends CI_model
{
	 
	public function getProduct($company_id)
	{
		$this->db->select('*');
		$this->db->where('company_id', $company_id);
		return $this->db->get('company_m')->row_array();
	}

	public function getSeries()
	{
		$this->db->select('*');
		
		return $this->db->get('competitor_master')->result_array();
		// $admin=$this->db->get('series1')->result_array();
		// print_r($admin);
		// exit;

	}
	public function getvehicle()
	{
		$this->db->select('*');
		// $this->db->where('company_id', $company_id);
		return $this->db->get('vehicle_make')->result_array();
	}
	public function insert_series($formArr)
	
	{
		$this->db->insert('competitor_company', $formArr);
	}

	public function get_seriesDetails()
    {
        $this->db->select('a.company_id,a.cc_id,b.company_name,c.c_name');
        $this->db->join('competitor_master as b', 'a.cc_id = b.id', 'left');
        $this->db->join('company_m as c', 'a.company_id = c.company_id', 'left');
        return $this->db->get('competitor_company as a')->result_array();
         // print_r($admin);
       // exit; 
       // $admin= $this->db->get('product_series as a')->result_array(); 
       // print_r($admin);
       // exit;    
    }
}

 ?>