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
        $this->load->model('sell/order/Order_model',"m_order",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
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
     * 查询销售单
     */
    public function search_sell(){
        //查询
        $model = $this->m_order;
        $param = $_REQUEST;
        $start_date = isset($param["start_date"])?$param["start_date"]:null;
        $end_date = isset($param["end_date"])?$param["end_date"]:null;
        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];
        $shop_id = isset($param["shop_id"])?$param["shop_id"]:null;

        //设置时区
        date_default_timezone_set('Asia/Shanghai');

        //拼凑查询条件
        $condition = [];
        if($start_date != null)
            $condition["create_at >"] = strtotime($start_date);
        if($end_date != null)
            $condition["create_at <="] = strtotime($end_date)+86400;
        if($shop_id != null)
            $condition["shop_id"] = $shop_id;
        $condition["type"] = 0;

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
     * 模糊查询订单接口
     */
    public function search_sell_like(){
        //查询
        $model = $this->m_order;
        $param = $_REQUEST;
        $start_date = isset($param["start_date"])?$param["start_date"]:null;
        $end_date = isset($param["end_date"])?$param["end_date"]:null;
        $key = isset($param["key"])?$param["key"]:null;
        $status = isset($param["status"])?$param["status"]:null;
        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];
        $isReceipted = isset($param["isReceipted"])?$param["isReceipted"]:null;
        $shop_id = isset($param["shop_id"])?$param["shop_id"]:null;

        //设置时区
        date_default_timezone_set('Asia/Shanghai');

        //拼凑查询条件
        $condition = [];
        if($start_date != null)
            $condition["create_at >"] = strtotime($start_date);
        if($end_date != null)
            $condition["create_at <="] = strtotime($end_date)+86400;
        if($isReceipted != null)
            $condition["isReceipted"] = $isReceipted;
        if($shop_id != null)
            $condition["shop_id"] = $shop_id;
        if($status != null)
            $condition["status"] = $status;
        $condition["type"] = 0;

        //查询
        $result = $model->searchLikeJoinAll($key,$condition,$sort);

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
     * 获取用户信息
     */
    public function user(){
        //参数检测
        $this->apiresult->checkApiParameter(['id'],-1);
        $id = $_REQUEST["id"];

        //获取用户信息
        $user = $this->m_user->get_user_info($id);
        $this->apiresult->sentApiSuccess($user);
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

    /**
     * 单上传文件(Base64字符串)
     * 必须存在$_POST['base64']
     */
    public function upload_base64(){
        if (count($_POST)) {
            $img = explode('|', $_POST['base64']);
            //上传文件(遍历获取，但只取第一个)
            for ($i = 0; $i <= count($img) - 1; $i++) {
                $path = $this->__upload_base64($img[$i]);
                break;
            }
            //返回数据
            $this->apiresult->sentApiSuccess($path);
        }
        else
            $this->apiresult->sentApiError(0,"image could not be saved.",null);
    }

    //region 报表方法

    //客户精选查询
    public function search_report_client(){
        $param = $_REQUEST;

        $list = $this->m_order->getReportClient($param);
        $this->apiresult->sentApiSuccess($list);
    }

    //客户精选查询
    public function search_report_date(){
        $param = $_REQUEST;

        $list = $this->m_order->getReportDate($param);
        $this->apiresult->sentApiSuccess($list);
    }

    //endregion

    //region 辅助方法

    /**
     * 按照路径创建文件夹
     * @param $path
     * @return bool
     */
    private function __mkdirs($path){
        //获取父路径和文件名
        $info = pathinfo($path);
        $dirname = $info['dirname'];

        //判断父路径是否存在,不存在则递归
        if(!is_dir($dirname))
            self::__mkdirs($dirname);

        //检测文件夹是否存在,不存在则新建
        if(!is_dir($path))
            mkdir($path);

        //返回
        return true;
    }

    /**
     * 上传Base64函数
     * @param $base64
     * @return null|string
     */
    private function __upload_base64($base64){
        if (strpos($base64, 'data:image/jpeg;base64,') === 0) {
            $base64 = str_replace('data:image/jpeg;base64,', '', $base64);
            $ext = '.jpeg';
        }
        if (strpos($base64, 'data:image/png;base64,') === 0) {
            $base64 = str_replace('data:image/png;base64,', '', $base64);
            $ext = '.png';
        }

        //获取图片文件内容
        $base64 = str_replace(' ', '+', $base64);
        $data = base64_decode($base64);

        //生成文件路径
        $datepath = date("Ymd",time());                             //生成日期
        $filename =  uniqid().$ext;                                 //文件名
        $relativePath = "upload/$datepath";                         //相对路径
        $absolutePath = FCPATH."/$relativePath";                    //绝对路径
        $this->__mkdirs($absolutePath);                               //建立文件夹(如果不存在)

        //获取访问地址
        $baseurl = "";                                              //获取web的访问路径(相对)
        $filepath = $absolutePath."/$filename";                     //文件绝对路径
        $url = "$baseurl/$relativePath/$filename";                  //上传后文件访问地址

        //保存图片,并输出结果
        if (file_put_contents($filepath, $data)) {
            return $url;
        } else {
            return null;
        }
    }

    //endregion;
}