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
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
    }

    /**
     * index
     */
    public function index()
    {
        $this->getArrivalSpu();
    }

    /**
     * 检测SPU/SKU是否有下单
     */
    public function checkExist(){
        //查询SPU是否下单
        $result = $this->m_spu->checkSpuExist("A1245");
        var_dump($result);
        //查询SKU是否下单
        $result = $this->m_sku->checkSkuExist("A12450305");
        var_dump($result);
    }

    /**
     * 根据SPU数组获取上新时间
     */
    public function getArrivalTime(){
        $resut = $this->m_spu->getArrivalTime(array("A1245","test2017102901"));
        var_dump($resut);
    }

    /**
     * 根据SPU数组获取上新Spu
     */
    public function getArrivalSpu(){
        $resut = $this->m_spu->getArrivalSpu("1511419480","1511419490");
        var_dump($resut);
    }
}