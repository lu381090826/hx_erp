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

    public function get_category($page = 1)
    {
        $this->load->model('category_model', 'm_cagegory');
        $result = $this->m_cagegory->get_list($page);

        $this->output->set_output(json_encode($result));
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

    public function goods_detail($goods_id)
    {
        $this->load->model('goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_by_goods_id($goods_id);

        $this->load->view('/goods/goods/detail', $data['result_rows']);
    }

    public function get_goods($page = 0)
    {
        $this->load->model('goods_model', 'goods_m');
        $result = $this->goods_m->get_goods_list($page);

        $this->output->set_output(json_encode($result));
    }
}
