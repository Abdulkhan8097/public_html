 <?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Retail_model extends CI_model
{
	public function save_retail($formArr)
	{	
		

		$this->db->insert('retail_list', $formArr);
		
		
	}


	function all()
	{
		
			$this->db->select('*');
			return $this->db->get('retail_list')->result_array();			
	}

	function all1()
	{
		
			$this->db->select('*');
			return $this->db->get('product')->result_array();			
	}    
	
}

 ?>