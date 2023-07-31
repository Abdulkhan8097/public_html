<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Country_model extends CI_model
{
	public function save_country($data)
	{
		$this->db->insert('country', $data);
	}

    

    

    public function all()
    {
        
        $this->db->select('*');
        return $this->db->get('country')->result_array();       
    }
	public function update_category($country_id,$formArr)
    {
        $this->db->where('country_id', $country_id);
        $this->db->update('country',$formArr);
    }

    public function edit_category($country_id)
    {
    	$this->db->select('*');
    	$this->db->where('country_id', $country_id);
    	return $this->db->get('country')->row_array();
    }

	
}

 ?>