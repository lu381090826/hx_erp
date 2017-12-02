<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Allocate extends BaseController {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller->views = "sell/allocate/Allocate";
        $this->_controller->controller = "sell/client/Allocate";
        $this->_controller->layout = "layout/amaze/hx";

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('sell/allocate/Allocate_model',"model",true);
        $this->load->model('sell/allocate/AllocateItem_model',"m_item",true);
        $this->load->model('sell/order/Order_model',"m_order",true);
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
        $this->load->model('admin/User_model',"m_user",true);
    }

    /**
     * index
     */
    public function index2()
    {
        $model = $this->model;
        $param = $_REQUEST;
        $page = isset($param["page"])?$param["page"]:1;
        $size = isset($param["size"])?$param["size"]:20;
        $condition = isset($param["condition"])?(array)json_decode($param["condition"]):[];
        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];

        $result = $model->search($page,$size,$condition,$sort);

        $this->show("index",[
            "searched"=>$result,
            "page"=>$page,
            "size"=>$size,
        ]);
    }

    /**
     * create
     */
    public function create(){
        $model = $this->model;
        $param = $_REQUEST;

        if (!empty($param) && $model->load($param) && $model->save()) {
            redirect($this->_controller->views."/index");
        } else {
            $this->show("create",[
                "model"=>$model,
            ]);
        }
    }

    /**
     * update
     */
    public function update($id){
        $model = $this->model->get($id);
        $param = $_REQUEST;

        if (!empty($param) && $model->load($param) && $model->save()) {
            redirect($this->_controller->views."/index");
        } else {
            $this->show("update",[
                "model"=>$model,
            ]);
        }
    }

    /**
     * delete
     */
    public function delete($id){
        $model = $this->model->get($id);
        $bool = $model->delete();

        if($bool)
            redirect($this->_controller->views."/index");
    }

    /**
     * view
     */
    public function view($id){
        $model = $this->model->get($id);

        var_dump($model);
    }

    /**
     * list
     * @param $order_id
     */
    public function index($order_id){
        //获取销售单信息
        $order = $this->m_order->get($order_id);
        $goods = $order->getGoods();
        $order->create_date = date("Y-m-d",$order->create_at);

        //获取列表
        $list = array();
        $allocates = $this->model->searchAll(["order_id"=>$order_id])->list;
        foreach($allocates as $item){
            $item->create_data = date("Y-m-d",$item->create_at);
            $item->statusName = $item->getStatusName();
            $list[] = $item;
        }

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //页面显示
        $this->show("list",[
            "order"=>$order,
            "list"=>$list,
            "seller"=>$seller,
            "client"=>$client,
            "goods"=>$goods
        ]);
    }

    /**
     * add
     * @param $order_id
     */
    public function add($order_id){
        //获取销售单信息
        $order = $this->m_order->get($order_id);

        //获取配货列表
        $list = $order->getSkuList();

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //生成配货单号
        $order_num = $this->model->createOrderNum();

        //页面显示
        $this->show("allocate",[
            "id"=>null,
            "order"=>$order,
            "list"=>$list,
            "order_num"=>$order_num,
            "seller"=>$seller,
            "client"=>$client,
        ]);
    }

    /**
     * modify
     * @param $allocate_id
     */
    public function modify($order_id,$allocate_id){
        //获取销售单信息
        $order = $this->m_order->get($order_id);

        //获取配货列表
        $allocate = $this->model->get($allocate_id);
        $list = $allocate->getSkuList(true,true);

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //生成配货单号
        $order_num = $this->model->createOrderNum();

        //页面显示
        $this->show("allocate",[
            "id"=>$allocate_id,
            "order"=>$order,
            "list"=>$list,
            "order_num"=>$order_num,
            "seller"=>$seller,
            "client"=>$client,
        ]);
    }

    /**
     * look
     * @param $allocate_id
     */
    public function look($allocate_id){
        //获取配货单信息
        $allocate = $this->model->get($allocate_id);

        //获取配货列表
        $list = $allocate->getSkuList(true,true);

        //获取销售单
        $order = $this->m_order->get($allocate->order_id);

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //页面显示
        $this->show("look",[
            "allocate"=>$allocate,
            "order"=>$order,
            "list"=>$list,
            "seller"=>$seller,
            "client"=>$client,
        ]);
    }

    /**
     * add_api
     */
    public function add_api(){
        //获取参数
        $data = $_REQUEST;

        //路由成建立或者修改
        if(isset($data['id']) && $data['id']){
            //修改表单
            $bool = $this->model->updateOrder($data);
        }
        else{
            //建立表单
            $bool = $this->model->createOrder($data);
        }

        //返回处理结果
        if($bool)
            $this->apiresult->sentApiSuccess();
        else
            $this->apiresult->sentApiError(-1,"fail");
    }
}
    