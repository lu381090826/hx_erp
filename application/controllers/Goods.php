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

    public function get_size($page = 0)
    {
        $this->load->model('size_model', 'm_size');
        $result = $this->m_size->get_size_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_sku($page = 0)
    {
        $this->load->model('sku_model', 'm_sku');
        $result = $this->m_sku->get_sku_list($page);

        $this->output->set_output(json_encode($result));
    }
}
