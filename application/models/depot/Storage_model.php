<?php

class Storage_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
  
    public  function add_storage_sku(){
    	$data['Fstorage_sn'] = $storage_sn = @$_REQUEST['storage_sn'];
    	$data['Fsku_id'] = $sku_id = @$_REQUEST['sku_id'];
    	$data['Fcount'] = $count = @$_REQUEST['count'];
    	
    	$sql = "select a.Fgoods_id,a.Fsku_id,b.Fname,c.Fsize_info from t_sku a,t_color b,t_size c where a.Fcolor_id = b.Fid and a.Fsize_id=c.Fid and a.Fsku_id='{$sku_id}'";
    	$query = $this->db->query($sql);
    	$back_data = $query->row_array();

    	$data['Fcolor'] = $back_data['name'];
    	$data['Fsize'] = $back_data['size_info'];
    	
    	$data['Fgoods_id'] = $back_data['goods_id'];
    	$data['Faddtime'] = date("Y-m-d H:i:s",time());
    	
    	$check_data = $this->check_storage_sku($storage_sn,$sku_id);

    	if($check_data){
    		$this->db->set('Fcount', 'Fcount+'.$count, FALSE);
    		$this->db->where("Fsku_id",$sku_id);
    		$this->db->where("Fstorage_sn",$storage_sn);
    		$this->db->update('storage_list_detail');   		    		
    	}
    	else{
    		$this->db->insert('storage_list_detail', $data);
    		$id = $this->db->insert_id();
    	}
    	
    	
    	$get_data = $this->get_all_storage_sku($storage_sn);
    	
    	return $get_data; 
    }
    
    public function change_storage_sku(){
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sku_id = @$_REQUEST['sku_id'];
    	$count = @$_REQUEST['count'];
    	
    	$this->db->set('Fcount', $count);
    	$this->db->where("Fsku_id",$sku_id);
    	$this->db->where("Fstorage_sn",$storage_sn);
    	$data = $this->db->update('storage_list_detail');
    	return $data;
    }
    
    public function change_storage_sku_beizhu(){
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sku_id = @$_REQUEST['sku_id'];
    	$beizhu = @$_REQUEST['beizhu'];
    	 
    	$this->db->set('Fbeizhu', $beizhu);
    	$this->db->where("Fsku_id",$sku_id);
    	$this->db->where("Fstorage_sn",$storage_sn);
    	$data = $this->db->update('storage_list_detail');
    	return $data;
    }
    
    public function check_storage_sku($storage_sn,$sku_id){
    	$query = $this->db->get_where('storage_list_detail',array("Fsku_id"=>$sku_id,"Fstorage_sn"=>$storage_sn));
    	$data = $query->row_array();
    	return $data ;
    }
    
    public  function get_all_storage_sku($storage_sn){

    	$page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
    	$page_count = ($page-1)*3;
    	
    	

    	$sql1 = "select * from t_storage_list_detail where Fstorage_sn='{$storage_sn}' order by Faddtime desc limit {$page_count},3";
    	$query = $this->db->query($sql1);
    	$data = $query->result_array();

    	return $data ;
    }
    
    
    //添加入库
    
    public function add_storage(){
    	$save_data = array();
    	$save_data['Fstorage_sn'] = $storage_sn = @$_REQUEST['storage_sn'];
    	$save_data['Fsn'] = $sn = @$_REQUEST['sn'];
    	$save_data['Fstorage_date'] = $storage_date = @$_REQUEST['storage_date'];
    	$save_data['Fstorage_type'] = $namestorage_type = @$_REQUEST['storage_type'];
    	$save_data['Fenter_depot'] = $enter_depot = @$_REQUEST['enter_depot'];
    	$save_data['Fname'] = $name = @$_REQUEST['name'];
    	$save_data['Fsupplier'] = $supplier = @$_REQUEST['supplier'];
    	$save_data['Fsource_depot'] = $source_depot = @$_REQUEST['source_depot'];
    	$save_data['Fbeizhu'] = $beizhu = @$_REQUEST['beizhu'];
    	$save_data['Faddtime'] = $this->addtime;
    	
    	$this->db->insert('storage_list', $save_data);
    	$id = $this->db->insert_id();
    	return $id;
    }
}
