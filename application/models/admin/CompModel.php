<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class CompModel extends CI_model
{
	 
	public function getCompany($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		return $this->db->get('competitor_master')->row_array();
	}

	public function insert_comp($formArr)
	
	{
		$this->db->insert('competitor', $formArr);
	}

}
 ?>