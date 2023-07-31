
<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Redeem_request_model extends CI_model
{


	public function save_redeem($formArr)
	{
		$this->db->insert('redeem_request', $formArr);
	}
	
	function all1()
	{
			$this->db->select('a.*, b.*');
			$this->db->join('company_m as b', 'b.company_id = a.company_id', 'left');
			return $this->db->get('redeem_request as a')->result_array();
			// echo "<pre>";
			// print_r($a);exit;		
	}

	public function edit_gallerym($redeem_request_id)
    {
        $this->db->select('*');
        $this->db->where('redeem_request_id', $redeem_request_id);
        return $this->db->get('redeem_request')->row_array();

    }

     public function update_redeem($redeem_request_id,$formArr)
    {
        $this->db->where('redeem_request_id', $redeem_request_id);
        $this->db->update('redeem_request',$formArr);
    } 	
}

 ?>
