<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class User_login_model extends CI_model
{
	public function get_list()
    {
        $this->db->select('*');
         $this->db->where('session!=', null);
         $this->db->where('session!=','');
        return $this->db->get('user')->result_array();
        // print_r($a); exit;
    }

    
   
}

 ?>