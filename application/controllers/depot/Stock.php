<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');

class Stock extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/depot_model");
                
    }

    
    public function Stock_list_view(){  	 
    	//分页
    	$page_data = $this->get_page("pos","");
    
    	$data['page_data'] = $page_data['pageStr'];
    	 
    	if(@$_REQUEST['id']){
    		$data['stock_list'] = $this->stock_model->get_stock(@$_REQUEST['id']);
    		$this->load->view("depot/stock_list",$data);
    	}
    	else{
    		//读取仓库
    		$data['storage_data'] = $this->depot_model->get_all_pos();
    		$this->load->view("depot/stock_list",$data);
    	}
    }
  

    
}