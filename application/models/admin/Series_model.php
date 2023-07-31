<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Series_model extends CI_model
{
	public function save_series($formArr)
	{
		$this->db->insert('series', $formArr);
	}


	function all()
	{
		
			$this->db->select('*');
                        $this->db->order_by('series_id', 'DESC');
			return $this->db->get('series')->result_array();
			// $result=$this->db->get('users')->result_array();
			// print_r($result);
			// exit;
			
	}

	

    public function edit_series($series_id)
    {
    	$this->db->select('*');
    	$this->db->where('series_id', $series_id);
    	return $this->db->get('series')->row_array();
    }

public function update_series($series_id,$formArr)
    {
        $this->db->where('series_id', $series_id);
        $this->db->update('series',$formArr);
    }
	
}

 ?>