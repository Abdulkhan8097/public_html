<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Discount_model extends CI_model
{
	public function save_discount($formArr)
	{
		$this->db->insert('everest_discount', $formArr);
	}

      public function all()
	{
		
			$this->db->select('*');
			return $this->db->get('everest_discount')->result_array();
			// $result=$this->db->get('users')->result_array();
			// print_r($result);
			// exit;
			
	}

	public function update_discount($everest_discount_id,$formArr)
    {
        $this->db->where('everest_discount_id', $everest_discount_id);
        $this->db->update('everest_discount',$formArr);
    }

    public function edit_discount($everest_discount_id)
    {
    	$this->db->select('*');
    	$this->db->where('everest_discount_id', $everest_discount_id);
    	return $this->db->get('everest_discount')->row_array();
    }
	

}

 ?>