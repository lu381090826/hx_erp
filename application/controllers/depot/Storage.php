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
    
    public function test(){
        $this->load->view("depot/images");
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
    
    //加数量
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
        	 
    	$enter_depot = @$_REQUEST['enter_depot'];
    	$result = $this->storage_model->get_all_storage_sku($storage_sn,$enter_depot);
    	 
    	if(empty($result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功","data"=>$result,"page"=>$page_data));
    	}
    	 
    }
    
    //改变入库数量
    public function change_storage_sku(){
    
    	$change_result = $this->storage_model->change_storage_sku();

    	 
    	if(empty($change_result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"改变成功"));
    	}
    	 
    }
    //改变入库单详情备注
    public function change_storage_sku_beizhu(){
    
    	$change_result = $this->storage_model->change_storage_sku_beizhu();
    
    
    	if(empty($change_result)){
    		$this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
    	}
    	else{
    		$this->return_msg(array("result"=>'1',"msg"=>"改变成功"));
    	}
    
    }
    //改变入库单备注
    public function change_storage_beizhu(){
    
        $change_result = $this->storage_model->change_storage_beizhu();
    
    
        if(empty($change_result)){
            $this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
        }
        else{
            $this->return_msg(array("result"=>'1',"msg"=>"改变成功"));
        }
    
    }
    
    //删除某张入库单某条sku
    public function delete_storage_sku(){        
        $this->storage_model->delete_storage_sku();
        $this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
    }
    //添加入库单页
    public function add_storage_view(){
    		//读取仓库
    		$data['depot_data'] = $this->depot_model->get_all_depot();
    		//读取供应商
    		$data['supplier_data'] = $this->depot_model->get_all_supplier();
    		//系统自动生成订单号
    		$data['storage_sn'] = "sn".time();
    		$this->load->view("depot/add_storage",$data);  	
    }
    
    //入库单详情页
    public function storage_list_detail_view(){
        
        $storage_sn = @$_REQUEST['storage_sn'];
        
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
        //读取供应商
        $data['supplier_data'] = $this->depot_model->get_all_supplier();
        
        //读取单张入库单
        $data['storage_data'] = $this->storage_model->check_storage_list($storage_sn);
        
        //读取该订单所有详情
        $data['storage_detail_data'] = $this->storage_model->check_depot_storage_list_detail($storage_sn,$data['storage_data']['depot_id'],'1');

        $sql = "select * from t_storage_list_detail where Fstorage_sn='{$storage_sn}'";
        
        
        $data['page_data'] = $this->get_order_page("t_storage_list_detail",$sql);
        
        $this->load->view("depot/storage_list_detail",$data);
    }
    
    //全部入库单列表页
    public function storage_list_view(){  	 
    	
    	    //分页
    	    $page_data = $this->get_page("storage_list","");
    	    
    	    $data['page_data'] = $page_data['pageStr'];
    	    
    		//读取所有入库单
    		$data['data'] = $this->storage_model->get_all_storage_list($page_data['page']);
    		
    	    $this->load->view("depot/storage_list",$data);
    }
    
    //条件查询入库单列表页
    public function storage_list_where_view(){

        //读取仓库
        $back_data = $this->storage_model->get_all_storage_where_list();

        $page_data = $this->get_page("storage_list",$back_data['get_count_sql']);
         
        $data['page_data'] = $page_data['pageStr'];
        
        $data['data'] = $back_data;
        
        $this->load->view("depot/storage_list",$data);
    }
    
    //删
    public function delete_storage(){

        $delete_result = $this->storage_model->delete_storage();
    
        if($delete_result){
            $this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"删除失败"));
        }
    }
    
    //添加入库单
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
    		$this->return_msg(array("result"=>'0',"msg"=>"失败"));
    	}
    }
    
    //改变入库单图片
    public function update_storage_images(){
         

        $update_result = $this->storage_model->update_storage_images();

    
        if($update_result){
            $this->return_msg(array("result"=>'1',"msg"=>"修改成功"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
        }
    }
    
    //审核入库单
    public function check_add_sku(){
         
    
        $check_add_sku = $this->storage_model->check_add_sku();
    
    
        if($check_add_sku){
            $this->return_msg(array("result"=>'1',"msg"=>"审核完成"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"审核完成"));
        }
    }
    
}