<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');

class Offer extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/offer_model");
        $this->load->model('depot/depot_model');
    }

    //报货单列表视图
    public function offer_list_view(){  	 
    	//分页
    	$page_data = $this->get_page("sell_allocate_item","");
    
    	$data['page_data'] = $page_data['pageStr'];
    	 
    	if(@$_REQUEST['id']){
    		$data['offer_list'] = $this->offer_model->get_offer_list(@$_REQUEST['id']);
    		$this->load->view("depot/offer_list",$data);
    	}
    	else{
    		//读取报货单
    		$data['offer_data'] = $this->offer_model->get_offer_list($page_data['page']);
    		$this->load->view("depot/offer_list",$data);
    	}
    }

    //添加出库单视图
    public function add_odo_view(){   
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
        
        //读取报货单
        $data['odo_data'] = $this->offer_model->get_offer();
        
        //系统自动生成订单号
        $data['odo_sn'] = "ODO".time();
        
        $this->load->view("depot/add_odo",$data);
    }
    
    //添加出库单
    public function add_odo(){
         
        if(@$_REQUEST['id']){
            $save_result = $this->offer_model->update_odo();
        }
        else{
            $save_result = $this->offer_model->add_odo();
        }
    
        if($save_result){
            $this->return_msg(array("result"=>'1',"msg"=>"添加成功"));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"失败"));
        }
    }
    
    //出库单列表视图
    public function odo_list_view(){
        //分页
        $page_data = $this->get_page("odo","");
    
        $data['page_data'] = $page_data['pageStr'];
    
        if(@$_REQUEST['id']){
            $data['offer_list'] = $this->offer_model->get_odo_list(@$_REQUEST['id']);
            $this->load->view("depot/odo_list",$data);
        }
        else{
            //读取报货单
            $data['odo_data'] = $this->offer_model->get_odo_list($page_data['page']);
            $this->load->view("depot/odo_list",$data);
        }
    }
    
    //出库单详情视图
    public function odo_detail_view(){
         
        $odo_sn = @$_REQUEST['odo_sn'];
        
        if(!$odo_sn){
            $odo_sn = @$_GET['odo_sn'];
        }
        //读取入库单详情
        $data['data'] = $this->offer_model->get_odo_detail($odo_sn);
       
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
        
        $this->load->view("depot/odo_detail",$data);

    }
    
    //打印出库单
    public function print_list(){
         
        $odo_sn = @$_REQUEST['odo_sn'];
    
        if(!$odo_sn){
            $odo_sn = @$_GET['odo_sn'];
        }
        //读取入库单详情
        $data['data'] = $this->offer_model->get_odo_detail($odo_sn);
         
        //读取仓库
        $data['depot_data'] = $this->depot_model->get_all_depot();
    
        $this->load->view("depot/print_list",$data);
    
    }
    
    //更改出库单状态
    public function update_odo_status(){
         
        $status = @$_REQUEST['status'];
    
        if(!$status){
            $status = @$_GET['status'];
        }
        $odo_sn = @$_REQUEST['odo_sn'];
        
        if(!$odo_sn){
            $odo_sn = @$_GET['odo_sn'];
        }
        
        
        $result = $this->offer_model->update_odo_status($status,$odo_sn);
         
    
        if($result){
            $this->return_msg(array("result"=>'1',"msg"=>"更改成功","data"=>$result));
        }
        else{
            $this->return_msg(array("result"=>'0',"msg"=>"失败"));
        } 
    
    }
}