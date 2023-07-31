<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Competitor_model extends CI_model
{
	public function save_competitor($formArr)
	{
		$this->db->insert('competitor_master', $formArr);
	}

      public function all()
	{
		$this->db->select('*');
		return $this->db->get('competitor_master')->result_array();
	}

	public function update_competitor($id,$formArr)
    {
        $this->db->where('id', $id);
        $this->db->update('competitor_master',$formArr);
    }

    public function edit_competitor($id)
    {
    	$this->db->select('*');
    	$this->db->where('id', $id);
    	return $this->db->get('competitor_master')->row_array();
    }
	

}

 ?>