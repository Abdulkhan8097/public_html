<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class State_model extends CI_model
{
	public function save_state($data)
	{
		$this->db->insert('state', $data);
	}

    

    public function all()
    {
        
        $this->db->select('a.*,b.*');
        $this->db->join('country as b', 'b.country_id=a.country_id', 'left');
       return $this->db->get('state as a')->result_array();
       // print_r($a);exit;
           
    }

    

    // public function all()
    // {
        
    //     $this->db->select('*');
    //     return $this->db->get('state')->result_array();       
    // }


    public function all2()
    {
        
        $this->db->select('*');
        return $this->db->get('country')->result_array();       
    }



	public function update_category($state_id,$formArr)
    {
        $this->db->where('state_id', $state_id);
        $this->db->update('state',$formArr);
    }

    public function edit_category($state_id)
    {
    	$this->db->select('*');
    	$this->db->where('state_id', $state_id);
    	return $this->db->get('state')->row_array();
    }

	
}

 ?>