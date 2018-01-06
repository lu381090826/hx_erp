<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');
class Depot extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/depot_model");
                
    }

    public function add_depot_view(){
    	if(@$_REQUEST['id']){
    		$data['depot_data'] = $this->depot_model->get_depot(@$_REQUEST['id']);
    		$this->load->view("depot/add_depot",$data);
    	}
    	else{
    		$this->load->view("depot/add_depot");
    	}   	
    }
    
    public function add_pos_view(){
    	if(@$_REQUEST['id']){
    		//读取仓库
    		$data['pos_data'] = $this->depot_model->get_pos(@$_REQUEST['id']);
	    	//读取仓库
	    	$data['depot_data'] = $this->depot_model->get_all_depot();
	    	
	    	$this->load->view("depot/add_pos",$data);
    	}
    	else{
    		//读取仓库
    		$data['depot_data'] = $this->depot_model->get_all_depot();
    		 
    		$this->load->view("depot/add_pos",$data);
    	}

    }
    
    //增 改
    public function add_depot(){

    	$save_data = array();
    	$save_data['Fdepot_name'] = $depot_name = @$_REQUEST['depot_name'];
    	$save_data['Fdepot_address'] = $depot_address = @$_REQUEST['depot_address'];
    	$save_data['Fname'] = $name = @$_REQUEST['name'];
    	$save_data['Fmobile'] = @$mobile = @$_REQUEST['mobile'];
    	$save_data['Ftype'] = @$type = @$_REQUEST['type'];
    	$save_data['Fbeizhu'] = @$beizhu = @$_REQUEST['beizhu'];
    	$save_data['Faddtime'] = $this->addtime;

    	if(!$depot_name||!$depot_address||!$depot_name){
    		$this->return_msg(array("result"=>'0',"msg"=>"仓库名字、仓库地址和仓库联系人必填"));
    	} 
    	
    	if(@$_REQUEST['id']){
    		$save_result = $this->depot_model->update_depot($save_data,@$_REQUEST['id']);
    	}
    	else{
    		$save_result = $this->depot_model->add_depot($save_data);
    	}

    	
    	if($save_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"添加失败"));
    	}
    }
    
    //删
    public function delete_depot(){
    	$id = @$_REQUEST['id'];
    	$delete_result = $this->depot_model->delete_depot($id);
    	
    	//删除旗下所有库位
    	$delete_all_pos = $this->depot_model->delete_all_pos($id);
    	
    	if($delete_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"删除失败"));
    	}
    }
    
    //查
    public function depot_list_view(){
    	$page_data = $this->get_page("depot","");
    	
    	$data['page_data'] = $page_data['pageStr'];
    	//读取仓库
    	$data['depot_data'] = $this->depot_model->get_all_depot();
    	$this->load->view("depot/depot_list",$data);
    }
    
    //增 改
    public function add_pos(){
    	$save_data = array();
    	$save_data['Fpos_name'] = $pos_name = @$_REQUEST['pos_name'];
    	$save_data['Fpos_code'] = $pos_code = @$_REQUEST['pos_code'];
    	$save_data['Fbeizhu'] = $beizhu = @$_REQUEST['beizhu'];
    	$save_data['Fdid'] = $did = @$_REQUEST['did'];
    	$save_data['Faddtime'] = $this->addtime;
    
    	if(!$pos_name||!$pos_code||!$did){
    		$this->return_msg(array("result"=>'0',"msg"=>"库位名字、库位代号和仓库名字必填"));
    	}
    	
    	if(@$_REQUEST['id']){
    		$save_result = $this->depot_model->update_pos($save_data,@$_REQUEST['id']);
    	}
    	else{
    		$save_result = $this->depot_model->add_pos($save_data);
    	}
    	 

    	 
    	if($save_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"添加失败"));
    	}
    }

    //删
    public function delete_pos(){
    	$id = @$_REQUEST['id'];
    	$delete_result = $this->depot_model->delete_pos($id);
    	 
    	if($delete_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"删除失败"));
    	}
    }
    
    //查
    public function pos_list_view(){
    	
        $search = @$_REQUEST['search'];
        
        if($search){
        	$sql = "select Fid from t_pos where Fpos_name like '%{$search}%' or Fpos_code like '%{$search}%'";
        	$page_data = $this->get_page("pos",$sql);
        	
        	$data['page_data'] = $page_data['pageStr'];
        	//读取仓库
        	$data['depot_data'] = $this->depot_model->get_all_pos($page_data['page'],$search);
        	$data['search'] =  $search;
        	$this->load->view("depot/pos_list",$data);
        }
        else{        	
        	$page_data = $this->get_page("pos","");
        	$data['page_data'] = $page_data['pageStr'];
        	
        	//读取仓库
        	$data['depot_data'] = $this->depot_model->get_all_pos($page_data['page']);
        	
        	$this->load->view("depot/pos_list",$data);
        }

    }
    
    public function add_supplier_view(){
    	if(@$_REQUEST['id']){
    		//读取供应商
    		$data['supplier_data'] = $this->depot_model->get_supplier($_REQUEST['id']);
    
    		$this->load->view("depot/add_supplier",$data);
    	}
    	else{
    		 
    		$this->load->view("depot/add_supplier");
    	}
    
    }
    //增 改
    public function add_supplier(){
    	$save_data = array();
    	$save_data['Fsupplier_name'] = $supplier_name = @$_REQUEST['supplier_name'];
    	$save_data['Fsupplier_address'] = $supplier_address = @$_REQUEST['supplier_address'];
    	$save_data['Fsupplier_bank_number'] = $supplier_bank_number = @$_REQUEST['supplier_bank_number'];
    	$save_data['Fname'] = $name = @$_REQUEST['name'];
    	$save_data['Fmobile'] = $mobile = @$_REQUEST['mobile'];
    	$save_data['Fbeizhu'] = $beizhu = @$_REQUEST['beizhu'];
    	$save_data['Faddtime'] = $this->addtime;
    
    	if(!$supplier_name||!$supplier_address||!$name||!$mobile){
    		$this->return_msg(array("result"=>'0',"msg"=>"内容不能为空"));
    	}
    	 
    	if(@$_REQUEST['id']){
    		$save_result = $this->depot_model->update_supplier($save_data,@$_REQUEST['id']);
    	}
    	else{
    		$save_result = $this->depot_model->add_supplier($save_data);
    	}
    
    
    
    	if($save_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"添加成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"添加失败"));
    	}
    }
    
    //删
    public function delete_supplier(){
    	$id = @$_REQUEST['id'];
    	$delete_result = $this->depot_model->delete_supplier($id);
    
    	if($delete_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"删除失败"));
    	}
    }
    
    //查
    public function supplier_list_view(){
    	 
    	$search = @$_REQUEST['search'];
    
    	if($search){
    		$sql = "select Fid from t_supplier where Fsupplier_name like '%{$search}%' or Fname like '%{$search}%'";
    		$page_data = $this->get_page("supplier",$sql);
    		 
    		$data['page_data'] = $page_data['pageStr'];
    		//读取仓库
    		$data['supplier_data'] = $this->depot_model->get_all_supplier($page_data['page'],$search);
    		$data['search'] =  $search;
    		$this->load->view("depot/supplier_list",$data);
    	}
    	else{
    		$page_data = $this->get_page("supplier","");
    		$data['page_data'] = $page_data['pageStr'];
    		 
    		//读取所有供应商数据
    		$data['supplier_data'] = $this->depot_model->get_all_supplier($page_data['page']);
    		 
    		$this->load->view("depot/supplier_list",$data);
    	}
    
    }
    
}