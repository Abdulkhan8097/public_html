<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Sale_model extends CI_model
{
	function all($user_id='')
	{
		
			$this->db->select('a.*,b.*');
			if($user_id!='')
			{
				$this->db->where('b.user_id', $user_id);
			}
			  $this->db->join('user as b', 'a.user_id = b.user_id', 'left');
			return $this->db->get('sales_competitor_discount as a')->result_array();			
	}
	function all1()
	{
		
			$this->db->select('first_name,last_name,user_id');
			return $this->db->get('user')->result_array();			
	}	
}

 ?>
