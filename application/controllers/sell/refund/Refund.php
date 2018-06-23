<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Refund extends BaseController {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller->views = "sell/refund/Refund";
        $this->_controller->controller = "sell/refund/Refund";
        //$this->_controller->layout = "layout/amaze/main";
        $this->_controller->layout = "layout/amaze/hx";

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('admin/User_model',"m_user",true);
        $this->load->model('sell/order/Order_model',"m_order",true);
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
        $this->load->model('sell/refund/Refund_model',"model",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
    }

    /**
     * 列表页
     */
    public function index()
    {
        $model = $this->model;
        $param = $_REQUEST;
        $page = isset($param["page"])?$param["page"]:1;
        $size = isset($param["size"])?$param["size"]:20;
        $condition = isset($param["condition"])?(array)json_decode($param["condition"]):[];
        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"DESC"];

        //联表查询
        $result = $model->searchLinkSell($page,$size,$condition,$sort);

        //处理显示数据
        foreach($result->list as $key=>$value){
            $result->list[$key]->statusName = $result->list[$key]->getStatusName();
        }

        $this->show("index",[
            "searched"=>$result,
            "page"=>$page,
            "size"=>$size,
        ]);
    }

    /**
     * 退货单页
     * @param $order_id
     */
    public function order($order_id){
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
        $this->show("order",[
            "order"=>$order,
            "list"=>$list,
            "seller"=>$seller,
            "client"=>$client,
            "goods"=>$goods
        ]);
    }

    /**
     * 添加退货单
     * @param $order_id
     */
    public function add($order_id){
        //获取销售单信息
        $order = $this->m_order->get($order_id);

        //获取配货列表
        $list = $order->getRefundSkuList();

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //生成配货单号
        $order_num = $this->model->createOrderNum();

        //设置初始填写的num数量
        foreach($list as $key=>$item){
            $num = $item->num_order - $item->num_refund;
            $list[$key]->num = $num>0?$num:0;
        }

        //页面显示
        $this->show("refund",[
            "id"=>null,
            "order"=>$order,
            "list"=>$list,
            "order_num"=>$order_num,
            "seller"=>$seller,
            "client"=>$client,
        ]);
    }

    /**
     * 修改退货单
     * @param $refund_id
     */
    public function modify($order_id, $refund_id){
        //获取订单信息
        $order = $this->m_order->get($order_id);
        $refund = $this->model->get($refund_id);

        //判断是否可以退货
        if($refund->status != 0){
            redirect($this->_controller->views."/look/$refund_id");
            return;
        }

        //获取退货列表
        $list = $refund->getSkuList();

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //生成配货单号
        $order_num = $refund->order_num;

        //页面显示
        $this->show("refund",[
            "id"=>$refund_id,
            "order"=>$order,
            "list"=>$list,
            "order_num"=>$order_num,
            "seller"=>$seller,
            "client"=>$client,
        ]);
    }

    /**
     * 查看退货单
     * @param $refund_id
     */
    public function look($refund_id){
        //获取配货单信息
        $refund = $this->model->get($refund_id);

        //获取配货列表
        $list = $refund->getSkuList(true,true);

        //获取销售单
        $order = $this->m_order->get($refund->order_id);

        //获取销售员和客户
        $seller = $this->m_user->get_user_info($order->user_id);
        $client = $this->m_client->get($order->client_id);

        //获取审核人
        $review = null;
        if($refund->review_user_id != null)
            $review = $this->m_user->get_user_info($refund->review_user_id);

        //页面显示
        $this->show("look",[
            "refund"=>$refund,
            "order"=>$order,
            "list"=>$list,
            "seller"=>$seller,
            "client"=>$client,
            "review"=>$review,
        ]);
    }

    /**
     * 删除退货单
     * @param $id
     */
    public function delete($id){
        $model = $this->model->get($id);
        $bool = $model->delete();

        if($bool)
            redirect($this->_controller->views."/index");
    }

    /**
     * 异步提交:添加/修改
     */
    public function submit(){
        //获取参数
        $data = $_REQUEST;

        //路由成建立或者修改
        if(isset($data['id']) && $data['id']){
            //判断是否可以更新
            $refund = $this->model->get($data['id']);
            if($refund->status == 0){   //只有未审核才允许修改
                //修改表单
                $bool = $this->model->updateOrder($data);
            }
            else{
                $bool = false;
            }
        }
        else{
            //建立表单
            $bool = $this->model->createOrder($data);
        }

        //更改订单状态
        $order_id = $_REQUEST["order_id"];
        $order = $this->m_order->get($order_id);
        if($order){
            $order->changeStatus(4);
            $order->save();
        }

        //返回处理结果
        if($bool)
            $this->apiresult->sentApiSuccess();
        else
            $this->apiresult->sentApiError(-1,"修改失败，退货单可能存在并发操作等.");
    }

    /**
     * 审核
     */
    public function review($id){
        $model = $this->model->get($id);
        $model->status = 1;
        $model->review_at = time();
        $model->review_user_id = $this->session->uid;
        $model->save();
        redirect($this->_controller->views."/look/$id");
    }
}
    