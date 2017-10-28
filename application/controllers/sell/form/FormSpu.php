<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class FormSpu extends BaseController {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller->views = "sell/form/FormSpu";
        $this->_controller->layout = "layout/amaze/main";

        //加载模型
        $this->load->model('sell/form/FormSpu_model',"model",true);
    }

    /**
     * index
     */
    public function index()
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
}
    