<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_model extends CI_Model
{
    public function get_state()
    {
        $this->db->select('a.state_id,a.state_desc');
        $this->db->order_by('a.state_desc', 'ASC');
        return $this->db->get('tbl_mst_state as a')->result_array();
    }

    public function get_city($state_id)
    {
        $this->db->select('a.city_id,a.city_name');
        $this->db->order_by('a.city_name', 'ASC');
        $this->db->where('state_id', $state_id);
        return $this->db->get('tbl_mst_city as a')->result_array();
    }

    public function save_customer($formArr)
    {
        $this->db->insert('tbl_crm_customer_mst', $formArr);
    }

    public function update_customer($customer_id, $formArr)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->update('tbl_crm_customer_mst', $formArr);
    }

    public function delete_customer($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->delete('tbl_crm_customer_mst');
    }

    public function get_customers()
    {
        $this->db->order_by('customer_id', 'DESC');
        return $this->db->get('tbl_crm_customer_mst')->result_array();
    }

    public function get_customer($customer_id)
    {
        $this->db->select('a.*,b.state_desc,c.city_name,d.first_name,d.last_name');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->join('tbl_mst_state as b', 'a.state_id = b.state_id', 'left');
        $this->db->join('tbl_mst_city as c', 'a.city_id = c.city_id', 'left');
        $this->db->join('tbl_mst_users as d', 'a.relationship_manager_id = d.user_id', 'left');
        return $this->db->get('tbl_crm_customer_mst a')->row_array();
    }

    public function bankers_name()
    {
        $this->db->select('a.bank_id,a.bank_name,a.financer_name');
        $this->db->order_by('a.bank_id', 'desc');
        return $this->db->get('tbl_crm_bank as a')->result_array();
    }

    public function relationship_managers()
    {
        $this->db->select('a.user_id,a.first_name,a.last_name');
        $this->db->where('a.status', '1');
        return $this->db->get('tbl_mst_users as a')->result_array();
    }

    // FOR APPLY LOAN
    public function loan_products()
    {
        $this->db->select('a.product_id,a.product_name');
        $this->db->order_by('a.product_id', 'DESC');
        return $this->db->get('tbl_crm_loan_products as a')->result_array();
    }

    public function save_loan_customer($formArr)
    {
        $this->db->insert('tbl_crm_customer_loan_logins', $formArr);
    }

    public function update_loan_customer($customer_loan_id, $formArr)
    {
        $this->db->where('customer_loan_id', $customer_loan_id);
        $this->db->update('tbl_crm_customer_loan_logins', $formArr);
    }

    public function delete_loan_customer($customer_loan_id)
    {
        $this->db->where('customer_loan_id', $customer_loan_id);
        $this->db->delete('tbl_crm_customer_loan_logins');
    }

    public function get_customer_login($customer_id, $customer_loan_id)
    {
        $this->db->select('a.*');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->where('a.customer_loan_id', $customer_loan_id);
        return $this->db->get('tbl_crm_customer_loan_logins as a')->row_array();
    }

    public function chk_customer_status($customer_id)
    {
        $this->db->select('a.final_status');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->where('(a.final_status = "Disbursed" OR a.final_status = "Sanction")');
        $res = $this->db->get('tbl_crm_customer_loan_logins as a')->result_array();
        return $res;
    }

    // for listing
    public function get_customer_loans($customer_id)
    {
        $this->db->select('a.*,b.product_name,c.bank_name,c.financer_name');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->order_by('a.customer_loan_id', 'desc');
        $this->db->join('tbl_crm_loan_products as b', 'a.product = b.product_id', 'left');
        $this->db->join('tbl_crm_bank as c', 'a.financer_name_id = c.bank_id', 'left');
        return $this->db->get('tbl_crm_customer_loan_logins as a')->result_array();
    }

    // for disburse dropdown
    public function get_bank_name($customer_id)
    {        
        $this->db->select('a.customer_loan_id,b.bank_name');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->order_by('b.bank_id', 'desc');
        $this->db->join('tbl_crm_bank as b', 'a.financer_name_id = b.bank_id', 'left');
        return $this->db->get('tbl_crm_customer_loan_logins as a')->result_array();
    }

    public function save_disburse($formArr)
    {
        $this->db->insert('tbl_crm_disburse', $formArr);
    }

    public function get_disbursed_id($customer_id)
    {
        $this->db->select('bank_id');
        $this->db->where('customer_id',$customer_id);
        $res = $this->db->get('tbl_crm_disburse')->row_array();
        return $res;
    }

    public function get_customer_disburse($customer_id,$bank_id)
    {
        $this->db->select('a.disburse_id,a.applied_amount,a.approved_amount,a.remark,b.tenure,c.product_name,d.bank_name');
        $this->db->order_by('a.disburse_id','DESC');
        $this->db->where('b.customer_id', $customer_id);
        $this->db->where('b.financer_name_id', $bank_id);
        $this->db->join('tbl_crm_customer_loan_logins as b', 'a.customer_id = b.customer_id', 'left');
        $this->db->join('tbl_crm_loan_products as c', 'b.product = c.product_id', 'left');
        $this->db->join('tbl_crm_bank as d', 'a.bank_id = d.bank_id', 'left');
        $res = $this->db->get('tbl_crm_disburse as a')->result_array();
        // print_r($res);
        // die();
        return $res;
    }

    public function get_approved_amount($customer_id,$bank_id)
    {
        $this->db->select('a.approved_amount');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->where('a.bank_id',$bank_id);
        return $this->db->get('tbl_crm_disburse as a')->row_array();
    }

    public function get_prev_paid_amount($customer_id,$bank_id,$disbursed_id)
    {
        $this->db->select_sum('a.paid_amount');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->where('a.bank_id',$bank_id);
        $this->db->where('a.disbursed_id',$disbursed_id);
        return $this->db->get('tbl_crm_payments as a')->row_array();
    }

    public function save_payment($formArr)
    {
        $this->db->insert('tbl_crm_payments', $formArr);
    }

    public function get_payment_details($customer_id,$bank_id)
    {        
        $this->db->select('a.paid_amount,a.remark,a.payment_date,b.bank_name');
        $this->db->order_by('a.payment_id','DESC');
        $this->db->where('a.customer_id', $customer_id);
        $this->db->where('a.bank_id',$bank_id);
        $this->db->join('tbl_crm_bank as b', 'a.bank_id = b.bank_id', 'left');
        return $this->db->get('tbl_crm_payments as a')->result_array();
    }

}
                        
/* End of file Bank.php */