<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Userlist_model extends CI_model
{
	public function get_list($user_type)
    {
        $this->db->select('a.*,b.*');
        if($user_type==2 || $user_type==3 || $user_type==4 || $user_type==5){
       $this->db->where('user_type',$user_type);
   }
   $this->db->join('company_m as b', 'b.company_id=a.company_id', 'left');
        return $this->db->get('user a')->result_array();
    }

    public function getcompany()
    {
        $this->db->select('*');
        return $this->db->get('company_m')->result_array();

    }

    // public function getcountry($country_id)
    // {
    //   $this->db->where('country_id', $country_id);
    //     return $this->db->get('counrty')->result_array();

    // }

public function get_list2()
    {
        $this->db->select('first_name,email,phone');
         $this->db->where('session!=', null);
         $this->db->where('session!=','');
        return $this->db->get('user')->result_array();
        // print_r($a); exit;
    }

    public function save_user($formArr)
	{
		$this->db->insert('user', $formArr);
	}

	public function edit_user($user_id)
	{
		$this->db->select('*');
		$this->db->where('user_id', $user_id);
		return $this->db->get('user')->row_array();
	}

	 public function update_user($user_id,$formArr)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('user',$formArr);
    }

    public function updateStatus($user_id,$formArr)
	{
		 $this->db->where('user_id' ,$user_id);
         $this->db->update('user',$formArr);
	}



    function country()
    {
        
            $this->db->select('*');
            return $this->db->get('country')->result_array();  
            // print_r($a);
            // exit;        
    }


   
    

   
}

 ?>