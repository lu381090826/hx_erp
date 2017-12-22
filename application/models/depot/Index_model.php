<?php

class Index_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }

    public function test(){
    	$query = $this->db->get_where('t_test',array('Fid' =>'1'));
    	$data = $query->row_array();
    	return $data;
    }
    
    public function get_group()
    {
    	$query = $this->db->get('t_group');
    	return $query->result_array();
    }
    
    public function get_list($gid = FALSE)
    {
    	if(!$gid){
    		return false;
    	}
    	$query = $this->db->get_where('t_group_list',array('Fgid' => $gid));
    	return $query->result_array();
    }
    

    public function count_db($dbname,$sql = false){
    	if(!$sql){
    		$query = $this->db->get($dbname);
    		return $query->num_rows();
    	}
    	else{
    		$query = $this->db->query($sql);
    		return $query->num_rows();
    	}
    }
}
