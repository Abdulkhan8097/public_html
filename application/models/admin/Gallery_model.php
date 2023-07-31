<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Gallery_model extends CI_model
{
    public function save_gallery($data)
    {
        $this->db->insert('gallery_m', $data);
    }

    

    public function all()
    {
        
        $this->db->select('a.*,b.*,c.*');
        $this->db->join('gallery_category_m as b', 'b.g_category_id=a.g_category_id', 'left');
         $this->db->join('gallery_subcategory_m as c', 'c.g_category_id=a.g_category_id', 'left');
       return $this->db->get('gallery_m as a')->result_array();
       // print_r($a);exit;
           
    }



     public function all1()
    {
        
        $this->db->select('*');
        return $this->db->get('gallery_category_m')->result_array();       
    }


     public function all2()
    {
        
        $this->db->select('*');
        return $this->db->get('gallery_subcategory_m')->result_array();       
    }

   

    public function update_gallery($gallery_id,$formArr)
    {
        $this->db->where('gallery_id', $gallery_id);
        $this->db->update('gallery_m',$formArr);
    }

    public function edit_gallerym($gallery_id)
    {
        $this->db->select('*');
        $this->db->where('gallery_id', $gallery_id);
        return $this->db->get('gallery_m')->row_array();

    }

    
}

 ?>