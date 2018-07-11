<?php

class Depot_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }

    //增
    public function add_depot($data){
    	
    	//如果为默认仓库，将所有仓库设置为普通
        if($data['Ftype']==1){
        	$this->db->set('Ftype', '0');
        	$this->db->where("Ftype",'1');
        	$this->db->update('depot');
        }
    	$this->db->insert('depot', $data);
    	$id = $this->db->insert_id();
    	return $id;
    }
    //删
    public function delete_depot($id){
    	return $this->db->delete('depot', array('Fid' => $id));
    }
    //改
    public function update_depot($data,$id){
    	//如果为默认仓库，将所有仓库设置为普通
    	if($data['Ftype']==1){
    		$this->db->set('Ftype', '0');
    		$this->db->where("Ftype",'1');
    		$this->db->update('depot');
    	}
    	$this->db->where("Fid",$id);
    	$this->db->update('depot',$data);
    	
    	return true;
    }
    //查
    public function get_depot($id){
    	$query = $this->db->get_where('depot',array("Fid"=>$id));
    	$data = $query->row_array();
    	return $data ;
    }
    
    //删
    public function delete_pos($id){
    	return $this->db->delete('pos', array('Fid' => $id));
    }
    
    public function delete_all_pos($did){
    	return $this->db->delete('pos', array('Fdid' => $did));
    }
    
    public function get_all_depot($page_number = 1){
    	$page_count = ($page_number-1)*15;
    	$this->db->order_by('Ftype', "DESC");
    	$query = $this->db->get('depot','15',$page_count);
    	$data = $query->result_array();
    	return $data ;
    }
    
    public function add_pos($data){
    
    	$this->db->insert('pos', $data);
    	$id = $this->db->insert_id();
    	return $id;
    }
    
    public function get_pos($id){
    	$query = $this->db->get_where('pos',array("Fid"=>$id));
    	$data = $query->row_array();
    	return $data ;
    }
    
    public function get_all_pos($page_number = 1,$search = false){
    	$page_count = ($page_number-1)*15;
    	if($search){
    		$this->db->order_by('Fid', 'DESC');
    		$this->db->or_like('Fpos_name',$search);
    		$this->db->or_like('Fpos_code', $search);
    		$query = $this->db->get('pos','15',$page_count);
    		$data = $query->result_array();
    	}
    	else{
    		$this->db->order_by('Fid', 'DESC');
    		$query = $this->db->get('pos','15',$page_count);
    		$data = $query->result_array();
    	}
    	


    	foreach($data as $k=>$v){
    		$depot_data = $this->get_depot($data[$k]['did']);
    		$data[$k]['did'] = $depot_data['depot_name'];
    	}
    	return $data ;
    }

    
    //改
    public function update_pos($data,$id){

    	$this->db->where("Fid",$id);
    	$this->db->update('pos',$data);
    	 
    	return true;
    }
    
    public function add_supplier($data){
    
    	$this->db->insert('supplier', $data);
    	$id = $this->db->insert_id();
    	return $id;
    }
    
    //删
    public function delete_supplier($id){
    	return $this->db->delete('supplier', array('Fid' => $id));
    }
    
    //查
    public function get_supplier($id){
    	$query = $this->db->get_where('supplier',array("Fid"=>$id));
    	$data = $query->row_array();
    	return $data ;
    }
    
    //改
    public function update_supplier($data,$id){
    
    	$this->db->where("Fid",$id);
    	$this->db->update('supplier',$data);
    
    	return true;
    }
    public function get_all_supplier($page_number = 1,$search = false){
    	$page_count = ($page_number-1)*15;
    	if($search){
    		$this->db->order_by('Fid', 'DESC');
    		$this->db->or_like('Fsupplier_name',$search);
    		$this->db->or_like('Fname', $search);
    		$query = $this->db->get('supplier','15',$page_count);
    		$data = $query->result_array();
    	}
    	else{
    		$this->db->order_by('Fid', 'DESC');
    		$query = $this->db->get('supplier','15',$page_count);
    		$data = $query->result_array();
    	}
    	return $data ;
    }
    
    public function check_supplier($search){

        if($search){
            $this->db->order_by('Fid', 'DESC');
            $this->db->select('Fsupplier_name');
            $this->db->select('Fid');
            $this->db->or_like('Fsupplier_name',$search);
            $this->db->or_like('Fname', $search);
            $query = $this->db->get('supplier');
            $data = $query->result_array();
            
        }
        else{
            return false;
        }
        return $data ;
    }
    
    //查出默认仓库的ID
    public function get_moren_depot(){
        $query = $this->db->get_where('depot',array("Ftype"=>'1'));
        $data = $query->row_array();
        return $data ;
    }
}
