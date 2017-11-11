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
        $this->_controller->layout = "layout/amaze/main";

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('sell/allocate/Allocate_model',"model",true);
        $this->load->model('sell/allocate/AllocateItem_model',"m_item",true);
        $this->load->model('sell/form/Form_model',"m_form",true);
        $this->load->model('sell/form/FormSpu_model',"m_spu",true);
        $this->load->model('sell/form/FormSku_model',"m_sku",true);
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
     * @param $form_id
     */
    public function index($form_id){
        //获取销售单信息
        $form = $this->m_form->get($form_id);
        $form->create_date = date("Y-m-d",$form->create_at);

        //获取列表
        $list = array();
        $allocates = $this->model->searchAll(["form_id"=>$form_id])->list;
        foreach($allocates as $item){
            $item->create_data = date("Y-m-d",strtotime($item->create_at));
            $item->statusName = $item->getStatusName();
            $list[] = $item;
        }

        //页面显示
        $this->show("list",[
            "form"=>$form,
            "list"=>$list
        ]);
    }

    /**
     * add
     * @param $form_id
     */
    public function add($form_id){
        //获取销售单信息
        $form = $this->m_form->get($form_id);

        //获取已配数量
        $allocated = $this->m_item->getAllocateStatus($form_id);

        //生成配货单号
        $order_num = $this->model->createOrderNum();

        //获取所有spu
        $form_spus = $this->m_spu->searchAll(["form_id"=>$form_id])->list;
        $form_skus = $this->m_sku->searchAll(["form_id"=>$form_id])->list;

        //获取
        $list = array();
        foreach($form_spus as $form_spu){
            foreach($form_skus as $form_sku){
                //过滤
                if($form_spu->id != $form_sku->form_spu_id)
                    continue;

                //设置项目
                $item = $this->m_item->_new();
                $item->form_id = $form->id;
                $item->form_spu_id = $form_spu->id;
                $item->form_sku_id = $form_sku->id;
                $item->spu_id = $form_spu->spu_id;
                $item->sku_id = $form_sku->sku_id;
                $item->num = 0;
                $item->status = 0;
                $item->spu = $form_spu;
                $item->sku = $form_sku;

                //设置可配数量
                $item->num_sum = (int)$form_sku->num;

                //设置已经配置数量
                $item->num_end = isset($allocated[$form_sku->id])?(int)$allocated[$form_sku->id]:0;

                //添加到列表
                $list[] = $item;
            }
        }

        //页面显示
        $this->show("allocateform",[
            "form"=>$form,
            "list"=>$list,
            "order_num"=>$order_num,
        ]);
    }

    /**
     * look
     * @param $allocate_id
     */
    public function look($allocate_id){
        //获取配货单信息
        $allocate = $this->model->get($allocate_id);

        //获取所有项目
        $allocate_items = $this->m_item->searchAll(["allocate_id"=>$allocate_id])->list;

        //获取销售单
        $form = $this->m_form->get($allocate->form_id);

        //获取销售单SKU信息
        $form_items = $this->m_sku->searchAll(["form_id"=>$allocate->form_id])->list;

        //组装列表
        $list = array();
        foreach($form_items as $iform){
            foreach($allocate_items as $iallocate){
                if($iform->id == $iallocate->form_sku_id){
                    $item = $iallocate;
                    //拼凑显示信息
                    $item->size = $iform->size;
                    $item->color = $iform->color;
                    $item->num_sum = $iform->num;
                    //添加到列表
                    $list[] = $item;
                }
            }
        }

        //var_dump($list);
        //die;

        //页面显示
        $this->show("look",[
            "allocate"=>$allocate,
            "form"=>$form,
            "list"=>$list,
        ]);
    }

    /**
     * add_api
     */
    public function add_api(){
        //获取参数
        $data = $_REQUEST;

        //建立表单
        $bool = $this->model->createForm($data);

        //返回处理结果
        if($bool)
            $this->apiresult->sentApiSuccess();
        else
            $this->apiresult->sentApiError(-1,"fail");
    }
}
    