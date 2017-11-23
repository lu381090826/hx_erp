<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Api extends CI_Controller {
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
        $this->load->model('sell/form/Form_model',"m_form",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
        $this->load->model('sell/form/FormSpu_model',"m_spu",true);
        $this->load->model('sell/form/FormSku_model',"m_sku",true);
        $this->load->model('goods/Goods_model',"m_goods",true);
        $this->load->model('goods/Sku_model',"m_good_sku",true);
        $this->load->model('admin/User_model',"m_user",true);
    }

    /**
     * 添加客户接口
     */
    public function add_client(){
        //参数检测
        $this->apiresult->checkApiParameter(['name','phone'],-1);

        //查重复
        $check = $this->m_client->searchAll(["name"=>$_REQUEST["name"]]);
        if($check->count > 0)
            $this->apiresult->sentApiError(1,"exist");

        //查询
        $model = $this->m_client;
        $model->load($_REQUEST);

        //保存
        if($model->save())
            $this->apiresult->sentApiSuccess($model);
        else
            $this->apiresult->sentApiError(-1,"fail");
    }

    /**
     * 保存客户信息接口
     */
    public function save_client(){
        //参数检测
        $this->apiresult->checkApiParameter(['id'],-1);
        $id = $_REQUEST["id"];

        //获得用户
        $model = $this->m_client->get($id);
        $model->load($_REQUEST);

        //保存用户
        if($model->save())
            $this->apiresult->sentApiSuccess($model);
        else
            $this->apiresult->sentApiError(-1,"fail");
    }

    /**
     * 查询用户接口
     */
    public function search_client(){
        //参数检测
        $this->apiresult->checkApiParameter(['key'],-1);
        $key = $_REQUEST["key"];

        //查询
        $model = $this->m_client;
        $result = $model->searchLikeAll($key);

        //输出
        $this->apiresult->sentApiSuccess($result->list);
    }

    /**
     * 接口销售单
     */
    public function search_sell(){
        //查询
        $model = $this->m_form;
        $param = $_REQUEST;
        $start_date = isset($param["start_date"])?$param["start_date"]:null;
        $end_date = isset($param["end_date"])?$param["end_date"]:null;
        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];

        //设置时区
        date_default_timezone_set('Asia/Shanghai');

        //拼凑查询条件
        $condition = [];
        if($start_date)
            $condition["create_at >"] = strtotime($start_date);
        if($end_date)
            $condition["create_at <="] = strtotime($end_date)+86400;

        //查询
        $result = $model->searchAll($condition,$sort);

        //遍历处理
        $list = [];
        foreach($result->list as $item){
            //获取客户
            $Mclient = $this->m_client->_new();
            $client = $Mclient->get($item->client_id);

            //添加数据
            $item->client = $client;
            $item->status_name = $item->getStatusName();
            $item->date = date("Y/m/d",$item->create_at);

            //加入列表
            $list[] = $item;
        }

        //输出
        $this->apiresult->sentApiSuccess($list);
    }

    /**
     * 查询商品
     */
    public function search_goods(){
        //参数检测
        $this->apiresult->checkApiParameter(['key'],-1);
        $key = $_REQUEST["key"];

        //搜索出列表
        $list = array();
        $goods = $this->m_goods->search_goods(["goods_id"=>$key]);
        foreach($goods["result_rows"] as $good){
            //转化商品spu为表单spu对象
            $item = $this->m_spu->_new();
            $item->spu_id = $good["goods_id"];
            $item->snap_price = $good["price"];
            $item->snap_pic = $good["pic"];
            $item->snap_pic_normal = $good["pic_normal"];

            //获取sku
            $skus = array();
            $skus_list = $this->m_good_sku->get_sku_list_info_by_goods_id($good["goods_id"]);
            foreach($skus_list["result_rows"] as $sku){
                //转化商品sku为表单sku对象
                $sku_item = $this->m_sku->_new();
                $sku_item->sku_id = $sku["sku_id"];
                $sku_item->color = $sku["color_info"]["name"];
                $sku_item->size = $sku["size_info"]["size_info"];
                $sku_item->num = 0;

                $skus[] = $sku_item;
            }
            $item->skus = $skus;
            $list[] = $item;
        }

        //返回结果
        $this->apiresult->sentApiSuccess($list);
    }

    /**
     * 获取当前用户
     */
    public function now_user(){
        if(isset($this->session->uid) && isset($this->session->userdata)){
            $uid = $this->session->uid;
            $user = $this->m_user->get_user_info($uid);
            $this->apiresult->sentApiSuccess($user);
        }
        else{
            $this->apiresult->sentApiError(-1,"not logged in");
        }
    }
}