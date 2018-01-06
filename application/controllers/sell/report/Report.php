<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Report extends BaseController {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller->api = "sell/Api";
        $this->_controller->views = "sell/report/Report";
        $this->_controller->controller = "sell/report/Report";
        $this->_controller->layout = "layout/amaze/hx";

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('sell/order/Order_model',"m_order",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
        $this->load->model('goods/Goods_model',"m_goods",true);
        $this->load->model('goods/Sku_model',"m_good_sku",true);
    }

    /**
     * index
     */
    public function index(){
        $this->show("index",[]);
    }
}