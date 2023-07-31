<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Product_model extends CI_model
{
function __construct() {
        // Set table name
        $this->table = 'product';
    }
    
    /*
     * Fetch members data from the database
     * @param array filter data based on the passed parameters
     */
	public function get_categories()
    {
        $this->db->select('*');
        // $this->db->order_by('a.users_id', 'ASC');
        return $this->db->get('category')->result_array();
    }
    public function get_model()
    {
        $this->db->select('*');
        // $this->db->order_by('a.users_id', 'ASC');
        return $this->db->get('model')->result_array();
    }
    public function get_series()
    {
        $this->db->select('*');
        // $this->db->order_by('a.users_id', 'ASC');
        return $this->db->get('series')->result_array();
    }


    public function get_product()
    {
        $this->db->select('a.*, b.category_name');
        $this->db->order_by('a.product_id', 'DESC');
        $this->db->join('category as b', 'b.category_id = a.category_id', 'left');
        return $this->db->get('product as a')->result_array();
    } 

    public function save_product($formArr)
	{
		$this->db->insert('product', $formArr);
	}

	public function insert_model($formArr2)
	{
		$this->db->insert('model_product', $formArr2);
	}

	public function insert_series($formArr1)
	{
		$this->db->insert('product_series', $formArr1);
	}

	public function edit_product($product_id)
	{
		$this->db->select('*');
		$this->db->where('product_id', $product_id);
		return $this->db->get('product')->row_array();
	}

	 public function update_product($product_id,$formArr)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('product',$formArr);
    } 
    public function getParameter()
    {
        $this->db->select('*');
        $this->db->order_by('id', 'DESC');
        return $this->db->get('parameter_search')->result_array();
    } 
     public function updateStatus($product_id,$formArr)
    {
         $this->db->where('product_id' ,$product_id);
         $this->db->update('product',$formArr);
    }
 public function getRows($params = array()){
        $this->db->select('eve_part_no,mrp');
       $this->db->from($this->table);

          
        if(array_key_exists("where", $params)){
            foreach($params['where'] as $key => $val){
                $this->db->where($key, $val);
            }
        }
        
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();
        }else{
            if(array_key_exists("product_id", $params)){
                $this->db->where('product_id', $params['product_id']);
                $query = $this->db->get();
                $result = $query->row_array();
            }else{
                $this->db->order_by('product_id', 'desc');
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
                }
                
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
              
            }
        }
        
        // Return fetched data
        return $result;

    }

     public function update($data, $condition = array()) {
        if(!empty($data)){
            // Add modified date if not included
            // if(!array_key_exists("modified", $data)){
            //     $data['modified'] = date("Y-m-d H:i:s");
            // }
            
            // Update member data
            $update = $this->db->update($this->table, $data, $condition);
            
            // Return the status
            return $update?true:false;
        }
        return false;
    }
 // public function insert($data = array()) {
    //     if(!empty($data)){
    //         // Add created and modified date if not included
    //         // if(!array_key_exists("created", $data)){
    //         //     $data['created'] = date("Y-m-d H:i:s");
    //         // }
    //         // if(!array_key_exists("modified", $data)){
    //         //     $data['modified'] = date("Y-m-d H:i:s");
    //         // }
            
    //         // Insert member data
    //         $insert = $this->db->insert($this->table, $data);
            
    //         // Return the status
    //         return $insert?$this->db->insert_id():false;
    //     }
    //     return false;
    // }

    


   


   
    // public function model($model_id)
    // {
    //     $this->db->select('a.model_name');
    //     $this->db->where('a.model_id', $model_id);
    //     $this->db->join('model_product as b', 'a.model_id = b.model_id', 'left');
    //     return $this->db->get('model as a')->row_array();     
    // }
}

 ?>