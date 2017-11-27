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
        $this->load->model('sell/form/FormSpu_model',"m_spu",true);
        $this->load->model('sell/form/FormSku_model',"m_sku",true);
    }

    /**
     * index
     */
    public function index()
    {
        $result = $this->m_sku->checkSkuExist("A124503052");
        var_dump($result);
    }
}