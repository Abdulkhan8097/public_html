<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class series_category_model extends CI_model
{
	public function save_seriescategory($formArr)
	{
		$this->db->insert('tbl_series_category', $formArr);
	}


	function all()
	{
		
			$this->db->select('*');
            $this->db->order_by('category_series_id', 'DESC');
			return $this->db->get('tbl_series_category')->result_array();
			// $result=$this->db->get('users')->result_array();
			// print_r($result);
			// exit;
			
	}

	

    public function edit_seriescategory($category_series_id)
    {
    	$this->db->select('*');
    	$this->db->where('category_series_id', $category_series_id);
    	return $this->db->get('tbl_series_category')->row_array();
    }

public function update_seriescategory($category_series_id,$formArr)
    {
        $this->db->where('category_series_id', $category_series_id);
        $this->db->update('tbl_series_category',$formArr);
    }
public function updateStatus($id,$formArr)
	{
		 $this->db->where('category_series_id' ,$id);
       $this->db->update('tbl_series_category',$formArr);

	}
	
}

 ?>