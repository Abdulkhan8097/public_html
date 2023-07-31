<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Banner_model extends CI_model
{
	public function save_banner($formArr)
	{
		$this->db->insert('banner', $formArr);
	}

      public function all()
	{
		
			$this->db->select('*');
			return $this->db->get('banner')->result_array();
			// $result=$this->db->get('users')->result_array();
			// print_r($result);
			// exit;
			
	}

	public function update_banner($id,$formArr)
    {
        $this->db->where('id', $id);
        $this->db->update('banner',$formArr);
    }

    public function edit_banner($id)
    {
    	$this->db->select('*');
    	$this->db->where('id', $id);
    	return $this->db->get('banner')->row_array();
    }

    public function updateStus($id,$formArr)
	{
		 $this->db->where('id' ,$id);
         $this->db->update('banner',$formArr);
	}
	

}

 ?>