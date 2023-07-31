<?php

if (!defined('BASEPATH'))exit('No direct script access allowed');
   class ParameterDetails_model extends CI_Model {

    public function gerParameterDetails($category_id)
    {
      $this->db->select('a.*, b.category_name');
      $this->db->where('a.category_id', $category_id);
      $this->db->join('category as b', 'b.category_id = a.category_id', 'left');
      return $this->db->get('parameter_search as a')->result_array();
    }
    
    // public function getProductList()
    // {
    //   $this->db->select('*');
    //   $admin =  $this->db->get('product')->result_array();
    //   $count = count($admin);
    //   return $count;
    // }
    // public function bookCar($formArr)
    // {
    // 	$this->db->insert('tbl_car', $formArr);
    // }
    // public function bookingList()
    // {
    // 	$this->db->select('*');
    // 	$this->db->order_by('id', 'DESC');
    // 	return $this->db->get('tbl_car')->result_array();
    // }
}

?>
