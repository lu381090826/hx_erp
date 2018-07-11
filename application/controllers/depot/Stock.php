<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');

class Stock extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/stock_model");
                
    }

    
    public function Stock_list_view(){  	 
    	//分页
    	$page_data = $this->get_page("stock_list","");
    
    	$data['page_data'] = $page_data['pageStr'];
    	 
    	if(@$_REQUEST['id']){
    		$data['stock_list'] = $this->stock_model->get_stock_list(@$_REQUEST['id']);
    		$this->load->view("depot/stock",$data);
    	}
    	else{
    	    //读取仓库
    	    $data['depot_data'] = $this->depot_model->get_all_depot();
    		//读取库存
    		$data['stock_data'] = $this->stock_model->get_stock_list($page_data['page']);
    		$this->load->view("depot/stock_list",$data);
    	}
    }
  
    //改变库位
    public function change_pos(){
         
    
        $update_result = $this->stock_model->change_pos();
    
    
        if($update_result){
            $this->return_msg(array("result"=>'1',"msg"=>"修改成功"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
        }
    }
    

    //查询库位
    public function check_pos(){
         
    
        $check_data = $this->stock_model->check_pos();
    
    
        if($check_data){
            $this->return_msg(array("result"=>'1',"msg"=>"获取成功","data"=>$check_data));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"获取失败"));
        }
    }
    
    //条件查询库存列表页
    public function stock_list_where_view(){
    
        //读取仓库
        $back_data = $this->stock_model->get_all_stock_where_list();
    
        $page_data = $this->get_page("stock_list",$back_data['get_count_sql']);
         
        $data['page_data'] = $page_data['pageStr'];

        $data['stock_data'] = $back_data['stock_data'];
    
        $data['search_data'] = $back_data['search_data'];
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
        
        $this->load->view("depot/stock_list",$data);
    }
    
    public function change_stock_view(){
        	//分页
    	$page_data = $this->get_page("stock_list","");
    
    	$data['page_data'] = $page_data['pageStr'];
    	 
    	if(@$_REQUEST['id']){
    		$data['stock_list'] = $this->stock_model->get_stock_list(@$_REQUEST['id']);
    		$this->load->view("depot/stock",$data);
    	}
    	else{
    	    //读取仓库
    	    $data['depot_data'] = $this->depot_model->get_all_depot();
    		//读取库存
    		$data['stock_data'] = $this->stock_model->get_stock_list($page_data['page']);
    		$this->load->view("depot/change_stock",$data);
    	}
    }
    
    //条件查询库存盘管列表页
    public function change_stock_where_view(){
    
        //读取仓库
        $back_data = $this->stock_model->get_all_stock_where_list();
    
        $page_data = $this->get_page("stock_list",$back_data['get_count_sql']);
         
        $data['page_data'] = $page_data['pageStr'];
    
        $data['stock_data'] = $back_data['stock_data'];
    
        $data['search_data'] = $back_data['search_data'];
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
    
        $this->load->view("depot/change_stock",$data);
    }
    
    public function change_stock(){
        
        $back_data = $this->stock_model->change_stock();
        if($back_data){
            $this->return_msg(array("result"=>'1',"msg"=>"修改成功"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"修改失败"));
        }
    }
}