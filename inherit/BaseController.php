<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {
    /**
     * 环境变量
     * @var
     */
    public $_controller;

    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller = (object)array();
        $this->_controller->views = "";
        $this->_controller->layout = "layout/amaze/main";

        //加载模型与帮助类
        $this->load->helper('url');
    }

    /**
     * @param $path
     * @param array $param
     */
    function show($path,$param=[]){
        //设置环境变量
        if(isset($this->model)) {$this->_controller->describe = $this->model->describe();}
        $param["_controller"] = $this->_controller;

        //重构造参数
        $list = array(
            "page"=>$this->_controller->views."/$path",
            "param"=>$param
        );

        //调用视图
        $this->load->view($this->_controller->layout,$list);
    }
}
