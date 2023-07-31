<?php

if (!defined('BASEPATH'))exit('No direct script access allowed');
   class Assign extends CI_Model {
    
    public function getModels()
    {
      $this->db->select('*');
       return $this->db->get('model')->row_array();
      
    }

    public function getCategories()
    {
      $this->db->select('*');
      return $this->db->get('category')->result_array();
    }

    public function get_number($model_id)
  {
    $this->db->select('*');
    $this->db->where('model_id', $model_id);
    return $this->db->get('model')->row_array();
  }

  public function insert_assign($formArr)
  {
    $this->db->insert('model_product', $formArr);
  }


  public function get_modelDetails($model_id)
    {
        $this->db->select('a.product_id,a.model_product_id,b.model_name,c.eve_part_no,d.category_name');
        $this->db->where('a.model_id', $model_id);
        $this->db->join('model as b', 'a.model_id = b.model_id', 'left');
        $this->db->join('product as c', 'a.product_id = c.product_id', 'left');
        $this->db->join('category as d', 'd.category_id = c.category_id', 'left');
        return $this->db->get('model_product as a')->result_array();
       // $admin=$this->db->get('model_product as a')->result_array();  
       // print_r($admin);
       // exit;   

    }
}

?>
