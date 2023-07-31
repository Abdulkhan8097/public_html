<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Download_model extends CI_model
{
	public function save_downloads($formArr)
	{
		$this->db->insert('downloads', $formArr);
	}


	function all()
	{
		
			$this->db->select('*');
			return $this->db->get('downloads')->result_array();
			// $result=$this->db->get('users')->result_array();
			// print_r($result);
			// exit;
			
	}

	

    public function edit_downloads($id)
    {
    	$this->db->select('*');
    	$this->db->where('id', $id);
    	return $this->db->get('downloads')->row_array();
    }

public function update_downloads($id,$formArr)
    {
        $this->db->where('id', $id);
        $this->db->update('downloads',$formArr);
    }
 public function updateStatus($id,$formArr)
	{
		 $this->db->where('id' ,$id);
       $this->db->update('downloads',$formArr);

	}
	
}

 ?>