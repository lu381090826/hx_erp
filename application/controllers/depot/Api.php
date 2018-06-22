<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/XMG_Controller.php');
class Api extends XMG_Controller {

	
	public $pageStr;
    public function __construct()
    {
        parent::__construct();

        $this->load->model("depot/api_model");
                
    }

    //获取sku库存 和该sku目前总已配数量和 当前所有销售单该sku未配数量
    public function get_sku_id_data(){
        $sku_id = @$_REQUEST['sku_id'];
        $data = $this->api_model->get_sku_id_data($sku_id);
        
         echo json_encode(array("result"=>1,"msg"=>"获取成功","data"=>$data));exit;
    }
}