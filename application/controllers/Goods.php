<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Goods extends HX_Controller
{

    public function index()
    {
        $this->load->view('goods/index');
    }

    public function get_info_list($page)
    {
        $this->output->set_output(json_encode([]));
    }

    public function get_category()
    {
        $this->output->set_output(json_encode([]));
    }
    public function get_color($page = 0)
    {
        $this->load->model('color_model', 'm_color');
        $result = $this->m_color->get_color_list($page);

        $this->output->set_output(json_encode($result));
    }
}
