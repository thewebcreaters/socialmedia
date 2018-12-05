<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
	
    public function get($table_name, $where = [], $offset = 0, $is_single = false, $is_total = false){
        $is_having_query = false;
        $this->db->from($table_name);
        
        if(!empty($where)){

            if(isset($where['search_query'])){
                foreach($where['search_query'] as $squery){
                    $this->db->where($squery);
                }
                unset($where['search_query']);
            }
            if(isset($where['having_query'])){
                foreach($where['having_query'] as $having_field => $having_val){
                    $this->db->having($having_field, $having_val);
                }
                unset($where['having_query']);
                $is_having_query = true;
            }

            $this->db->where($where);
            
            if($is_having_query == true && $is_total == true){
                return count($this->db->get()->result_array());
            }
        }
        
        if(!$is_total){
            if($is_single){
                    return $this->db->get()->row_array();
            } else {
                if($offset >= 0){
					//echo TOTAL_RECORD_PER_PAGE;
                    $this->db->limit(TOTAL_RECORD_PER_PAGE, $offset);
                }
				
                return $this->db->get()->result_array();
            }
        } else {
            return $this->db->count_all_results();
        }

    }
    
    public function count($table = '', $conditions = [])
    {
        $this->db->where($conditions);
        return $this->db->count_all_results($table);
    }
    
    public function save($table_name, $data, $where = []){
        if(empty($where)){
			//print_r($data);die();
            $this->db->insert($table_name, $data);
            return $this->db->insert_id();
        } else {
            $this->db->where($where);
            return $this->db->update($table_name, $data);
            //return $this->db->affected_rows();
        }
    }
}