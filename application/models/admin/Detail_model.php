<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Detail_model extends CI_model
{
	 
	public function getdetail($redeem_request_id)
	{
		$this->db->select('*');
		$this->db->where('redeem_request_id', $redeem_request_id);
		return $this->db->get('redeem_request')->result_array();

		
	}

}

 ?>