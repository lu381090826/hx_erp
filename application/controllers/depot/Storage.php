<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');

class Storage extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/depot_model");
        $this->load->model("depot/index_model");
        $this->load->model("depot/storage_model");

    }

    public function get_spu_sku(){
    	$spu = @$_REQUEST['spu'];
    	$page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;   	
    	$page_count = ($page-1)*10;
    	
    	if(!$spu){
    		$this->return_msg(array("result"=>'0',"msg"=>"搜索内容不能为空！"));
    	}

    	//获取总条数
    	$get_count = "select a.Fgoods_id,a.Fsku_id,b.Fname,c.Fsize_info from t_sku a,t_color b,t_size c where a.Fcolor_id = b.Fid and a.Fsize_id=c.Fid and a.Fgoods_id like '%{$spu}%'";
    
    	//获取该页的数量
    	$sql = "select a.Fgoods_id,a.Fsku_id,b.Fname,c.Fsize_info from t_sku a,t_color b,t_size c where a.Fcolor_id = b.Fid and a.Fsize_id=c.Fid and a.Fgoods_id like '%{$spu}%' order by a.Fcreate_time desc limit {$page_count},10";
    	
    	$back_data = $this->index_model->get_query($sql);
    	if(empty($back_data)){
    		$this->return_msg(array("result"=>'0',"msg"=>"搜索错误！"));
    	}
    	else{
    		$page_data = $this->get_storage_page("t_sku",$get_count);   		 
    		
    		$this->return_msg(array("result"=>'1',"msg"=>"成功","data"=>$back_data,"page"=>$page_data));
    	}
    }
    
    public function add_storage_sku(){

    	$add_result = $this->storage_model->add_storage_sku();
    	
    	
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sql = "select * from t_storage_list_detail where Fstorage_sn='{$storage_sn}'";
    		
    	 
    	$page_data = $this->get_order_page("t_storage_list_detail",$sql);
    	   	
    	
    	if(empty($add_result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功","data"=>$add_result,"page"=>$page_data));
    	}
      	
    }
    
    public function get_order_list_page(){
    	 
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sql = "select * from t_storage_list_detail where Fstorage_sn='{$storage_sn}'";
    		
    	 
    	$page_data = $this->get_order_page("t_storage_list_detail",$sql);
        	 
    	 
    	$result = $this->storage_model->get_all_storage_sku($storage_sn);
    	 
    	if(empty($result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功","data"=>$result,"page"=>$page_data));
    	}
    	 
    }
    
    public function change_storage_sku(){
    
    	$change_result = $this->storage_model->change_storage_sku();

    	 
    	if(empty($change_result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"改变成功"));
    	}
    	 
    }
    public function change_storage_sku_beizhu(){
    
    	$change_result = $this->storage_model->change_storage_sku_beizhu();
    
    
    	if(empty($change_result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"改变成功"));
    	}
    
    }
    
    public function add_storage_view(){
    	
    	$page_data = $this->get_page("pos","");
    	 
    	$data['page_data'] = $page_data['pageStr'];
    	
    	if(@$_REQUEST['id']){
    		$data['storage_data'] = $this->stock_model->get_stock(@$_REQUEST['id']);
    		$this->load->view("depot/add_storage",$data);
    	}
    	else{
    		//读取仓库
    		$data['depot_data'] = $this->depot_model->get_all_depot();
    		//读取供应商
    		$data['supplier_data'] = $this->depot_model->get_all_supplier();
    		//系统自动生成订单号
    		$data['storage_sn'] = "sn".time();
    		$this->load->view("depot/add_storage",$data);
    	}   	
    }
    
    public function storage_list_view(){  	 
    	//分页
    	$page_data = $this->get_page("pos","");
    
    	$data['page_data'] = $page_data['pageStr'];
    	 
    	if(@$_REQUEST['id']){
    		$data['storage_data'] = $this->stock_model->get_stock(@$_REQUEST['id']);
    		$this->load->view("depot/storage_list",$data);
    	}
    	else{
    		//读取仓库
    		$data['storage_data'] = $this->depot_model->get_all_pos();

    		$this->load->view("depot/storage_list",$data);
    	}
    }
    
    //增 改
    public function add_storage(){

    	 
    	if(@$_REQUEST['id']){
    		$save_result = $this->storage_model->update_storage();
    	}
    	else{
    		$save_result = $this->storage_model->add_storage();
    	}
    
    
    
    	if($save_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"添加失败"));
    	}
    }
    
    
}