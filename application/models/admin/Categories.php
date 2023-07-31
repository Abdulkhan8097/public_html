<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Categories extends CI_model
{
	public function save_category($data)
	{
		$this->db->insert('category', $data);
	}

    //  public function checkcategory($category_name,$category_id ="")
    // {
    //     if($category_id !=""){
    //         $sql="select * from category where category_name='$category_name' AND category_id  != $category_id ";
    //     }else{
    //         $sql="select * from category where category_name='$category_name'";
    //     }
    //     $query=$this->db->query($sql);
    //     return $query->result_array();
    // }

    public function all()
    {
        
        $this->db->select('*');
        $this->db->order_by('sequence_no', 'ASC');
        return $this->db->get('category')->result_array();       
    }
	public function update_category($category_id,$formArr)
    {
        $this->db->where('category_id', $category_id);
        $this->db->update('category',$formArr);
    }

    public function edit_category($category_id)
    {
    	$this->db->select('*');
    	$this->db->where('category_id', $category_id);
    	return $this->db->get('category')->row_array();
    }

    public function gerParameterDetails($category_id)
    {
        $this->db->select('a.*, b.category_name');
        $this->db->where('a.category_id', $category_id);
        $this->db->join('category as b', 'b.category_id = a.category_id', 'left');
        return $this->db->get('parameter_search as a')->result_array();

    }

    public function getProduct($category_id)
    {
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        return $this->db->get('product')->result_array();
    }

    public function getCategory($category_id)
    {
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        return $this->db->get('category')->result_array();
    }

    function all1()
    {
        
            $this->db->select('*');
            return $this->db->get('category')->result_array();          
    }  
     public function updateStatus($category_id,$formArr)
    {
         $this->db->where('category_id' ,$category_id);
         $this->db->update('category',$formArr);
    } 

	
}

 ?>