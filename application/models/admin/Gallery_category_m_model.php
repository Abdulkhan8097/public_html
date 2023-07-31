<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Gallery_category_m_model extends CI_model
{
	public function save_gallery($data)
	{
		$this->db->insert('gallery_category_m', $data);
	}

    

    public function all()
    {
        
        $this->db->select('*');
        return $this->db->get('gallery_category_m')->result_array();       
    }
	public function update_gallery($g_category_id,$formArr)
    {
        $this->db->where('g_category_id', $g_category_id);
        $this->db->update('gallery_category_m',$formArr);
    }

    public function edit_gallery($g_category_id)
    {
    	$this->db->select('*');
    	$this->db->where('g_category_id', $g_category_id);
    	return $this->db->get('gallery_category_m')->row_array();
    }

	
}

 ?>