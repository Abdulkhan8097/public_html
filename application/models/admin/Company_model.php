<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Company_model extends CI_model
{
	public function save_category($data)
	{
		$this->db->insert('company_m', $data);
	}

    


    public function all()
    {
        
        $this->db->select('*');
        return $this->db->get('company_m')->result_array();       
    }

     public function all2()
    {
        
        $this->db->select('*');
        $this->db->where('company_type','Parent');
        return $this->db->get('company_m')->result_array();  
        // print_r($a);exit;
    }

     public function all3()
    {
        
        $this->db->select('*');
         $this->db->where('company_type!=','Dealer');
          $this->db->where('company_type!=','Customer');
         return $this->db->get('company_m')->result_array();  
         // $this->db->last_query(); 

    }

     public function all4()
    {
        
        $this->db->select('*');
         $this->db->where('company_type','Parent');
        return $this->db->get('company_m')->result_array();       
    }




	public function update_category($company_id,$formArr)
    {
        $this->db->where('company_id', $company_id);
        $this->db->update('company_m',$formArr);
    }

    public function edit_category($company_id)
    {
    	$this->db->select('*');
    	$this->db->where('company_id', $company_id);
    	return $this->db->get('company_m')->row_array();
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
     public function check($company_type)
    {
        
        $this->db->select('*');
        $this->db->where('company_type',$company_type);
        return $this->db->get('company_m')->result_array();  
        // print_r($a);exit;
    }

	
}

 ?>