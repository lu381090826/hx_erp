<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Test extends CI_Controller {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('goods/Goods_model',"m_goods",true);
        $this->load->model('goods/Sku_model',"m_sku",true);
    }

    /**
     * index
     */
    public function index()
    {
        $key = "123";

        //搜索出列表
        $list = array();
        $goods = $this->m_goods->search_goods(["goods_id"=>$key]);
        foreach($goods["result_rows"] as $good){
            $skus = $this->m_sku->get_sku_list_info_by_goods_id($good["goods_id"]);
            var_dump($skus);
        }
    }
}