<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');

class Storage extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/depot_model");
                
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
    	
    	if($delete_result){
    		$this->return_msg(array("result"=>'1',"msg"=>"删除成功"));
    	}
    	else{
    		$this->return_msg(array("result"=>'0',"msg"=>"删除失败"));
    	}
    }
    
    //查
    public function depot_list_view(){
    	$page_data = $this->get_page("depot","","depot_model");
    	
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
        	$page_data = $this->get_page("pos",$sql,"depot_model");
        	 
        	$data['page_data'] = $page_data['pageStr'];
        	//读取仓库
        	$data['depot_data'] = $this->depot_model->get_all_pos($page_data['page'],$search);
        	$data['search'] =  $search;
        	$this->load->view("depot/pos_list",$data);
        }
        else{        	
        	$page_data = $this->get_page("pos","","depot_model");
        	 
        	$data['page_data'] = $page_data['pageStr'];
        	//读取仓库
        	$data['depot_data'] = $this->depot_model->get_all_pos($page_data['page']);
        	
        	$this->load->view("depot/pos_list",$data);
        }

    }
    

    
}