<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Crud_model extends CI_model
{
	public function save_model($formArr)
	{
		$this->db->insert('model', $formArr);
	}

      public function all()
	{
	$this->db->select('*');
	$this->db->order_by('model_name', 'DESC');
	return $this->db->get('model')->result_array();			
	}
	public function getVechicle()
	{
		$this->db->select('*');
		$this->db->order_by('vm_id', 'DESC');
		return $this->db->get('vehicle_make')->result_array();
	}

	public function update_model($model_id,$formArr)
    {
        $this->db->where('model_id', $model_id);
        $this->db->update('model',$formArr);
    }

    public function edit_model($model_id)
    {
    	$this->db->select('*');
    	$this->db->where('model_id', $model_id);
    	$this->db->join('vehicle_make as b', 'a.fk_vm_id = b.vm_id', 'left');
    	return $this->db->get('model as a')->row_array();
    }
    public function allVechicle()
    {
        $this->db->select('a.*,b.*');
        $this->db->join('vehicle_make as b', 'a.fk_vm_id = vm_id', 'left');
        return $this->db->get('model as a')->result_array();     
    }
	

}

 ?>