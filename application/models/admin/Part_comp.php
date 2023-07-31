<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Part_comp extends CI_model
{
	public function getComptetor($competitor_id)
    {
    	$this->db->select('a.*,b.*, c.category_name');
    	$this->db->where('a.competitor_id', $competitor_id);
    	$this->db->join('product as b', 'b.product_id = a.product_id', 'left');
    	$this->db->join('category as c', 'c.category_id = b.category_id', 'left');
    	return $this->db->get('product_competitor as a')->result_array();
    }
	
	 
	public function getPart($competitor_id)
	{
		$this->db->select('*');
		$this->db->where('competitor_id', $competitor_id);
		return $this->db->get('competitor')->row_array();
	}

	public function getCompany()
	{
		$this->db->select('*');
		// $this->db->where('product_id', $product_id);
		return $this->db->get('competitor_master')->result_array();
	}

	public function insert_part($formArr)
	
	{
		$this->db->insert('competitor', $formArr);
	}
	public function update_part($competitor_id,$formArr)
    {
        $this->db->where('competitor_id ', $competitor_id);
        $this->db->update('competitor',$formArr);
    }
	public function get_PartDetails()
    {
        $this->db->select('a.competitor_id,a.comp_part_no,a.mrp,b.company_name,a.remark,a.eff_date');
        $this->db->order_by('competitor_id', 'DESC');
        $this->db->join('competitor_master as b', 'a.fk_com_master_id = b.id', 'left');
        return $this->db->get('competitor as a')->result_array();     
    }
}

 ?>