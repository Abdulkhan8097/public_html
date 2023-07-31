<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Parameter_model extends CI_model
{
	public function save_parameter_search($formArr)
	{
		$this->db->insert('parameter_search', $formArr);
	}


	function all()
	{
		
			$this->db->select('a.*,b.category_name');
			$this->db->order_by('id', "DESC");
			$this->db->join('category as b', 'a.category_id = b.category_id', 'left');
			return $this->db->get('parameter_search as a')->result_array();
	}
	function all1()
	{
		
			$this->db->select('*');
			return $this->db->get('category')->result_array();			
	}    

	

    public function edit_parameter_search($id)
    {
    	$this->db->select('*');
    	$this->db->where('id', $id);
    	return $this->db->get('parameter_search')->row_array();
    }

public function update_parameter_search($id,$formArr)
    {
        $this->db->where('id', $id);
        $this->db->update('parameter_search',$formArr);
    }
	
}

 ?>