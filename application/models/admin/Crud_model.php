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
	$this->db->select('a.*, b.vm_id ,vehicle_make_name,c.category_model_id,model_category_name');
	$this->db->order_by('model_id', 'DESC');
	$this->db->join('vehicle_make as b', 'b.vm_id = a.fk_vm_id', 'left');
	$this->db->join('tbl_model_category as c', 'c.category_model_id = a.fk_model_category_id', 'left');
	
	return $this->db->get('model as a')->result_array();			
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

    public function getModel($model_id)
    {
    	$this->db->select('a.*,b.*, c.category_name');
    	$this->db->where('a.model_id', $model_id);
    	$this->db->join('product as b', 'b.product_id = a.product_id', 'left');
    	$this->db->join('category as c', 'c.category_id = b.category_id', 'left');
    	return $this->db->get('model_product as a')->result_array();
    }
public function updateStatus($model_id,$formArr)
	{
		 $this->db->where('model_id' ,$model_id);
         $this->db->update('model',$formArr);
	}
	

}

 ?>