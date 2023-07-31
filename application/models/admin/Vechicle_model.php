<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Vechicle_model extends CI_model
{

	public function getVechicle()
	{
		$this->db->select('*');
		$this->db->order_by('vm_id', 'DESC');
		return $this->db->get('vehicle_make')->result_array();
	}
	public function edit_vechicle($vm_id)
	{
		$this->db->select('*');
		$this->db->where('vm_id', $vm_id);
		return $this->db->get('vehicle_make')->row_array();
	}
	public function insert_vechicle($formArr)
	
	{
		$this->db->insert('vehicle_make', $formArr);
	}

	 public function update_vechicle($vm_id,$formArr)
    {
        $this->db->where('vm_id', $vm_id);
        $this->db->update('vehicle_make',$formArr);
    }
}

 ?>