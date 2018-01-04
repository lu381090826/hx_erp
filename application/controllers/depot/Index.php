<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Index extends CI_Controller {

	public $url;
	public $back_data;//返回数据
	
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');   
        $this->load->helper('url');
        $this->url = $this->config->item('url');
        
        
        $this->back_data = array(
        		"url"=>$this->url,
        );
    }
    public function index(){
    	$this->load->model('depot/index_model');
    	 
    	$get_group = $this->index_model->get_group();
    	 
    	
    	$zuheid = '';
    	foreach($get_group as $k=>$v){
    		$get_list = $this->index_model->get_list($get_group[$k]['id']);
    		$get_group[$k]['list'] = $get_list;
    	}
    	$this->back_data['data'] = $get_group;

    	$this->load->view("depot/index",$this->back_data);
    }

}