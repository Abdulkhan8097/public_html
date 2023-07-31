<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Gallery_subcategory_m_model extends CI_model
{
	public function save_gallery($data)
	{
		$this->db->insert('gallery_subcategory_m', $data);
	}

    

    public function all()
    {
        
        $this->db->select('a.*,b.*');
        $this->db->join('gallery_category_m as b', 'b.g_category_id=a.g_category_id', 'left');
        return $this->db->get('gallery_subcategory_m as a')->result_array(); 
        // print_r($a);exit;     
    }



     public function all1()
    {
        
        $this->db->select('*');
        return $this->db->get('gallery_category_m')->result_array();       
    }

   

	public function update_gallery($g_subcategory_id,$formArr)
    {
        $this->db->where('g_subcategory_id', $g_subcategory_id);
        $this->db->update('gallery_subcategory_m',$formArr);
    }

    public function edit_gallerym($g_subcategory_id)
    {
    	$this->db->select('*');
    	$this->db->where('g_subcategory_id', $g_subcategory_id);
    	return $this->db->get('gallery_subcategory_m')->row_array();

    }

	
}

 ?>