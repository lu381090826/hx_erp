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
        $this->load->model('goods/category_model', 'm_cagegory');
        $result = $this->m_cagegory->get_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_color($page = 0)
    {
        $this->load->model('goods/color_model', 'color_m');
        $result = $this->color_m->get_color_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_size($page = 0)
    {
        $this->load->model('goods/size_model', 'size_m');
        $result = $this->size_m->get_size_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_sku($page = 0)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_sku_list_info($goods_id)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list_info_by_goods_id($goods_id);

        $this->output->set_output(json_encode($result));
    }

    public function goods_detail($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_by_goods_id($goods_id);

        $this->load->view('/goods/goods/detail', $data['result_rows']);
    }

    public function get_goods($page = 0)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $request['page'] = $page;
        $result = $this->goods_m->get_goods_list($request);

        $this->output->set_output(json_encode($result));
    }
}
