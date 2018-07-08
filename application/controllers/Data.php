<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Data extends HX_Controller
{

    public function index()
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data['goods_count'] = $this->goods_m->get_goods_count();

        $this->load->model('goods/sku_model', 'sku_m');
        $data['sku_count'] = $this->sku_m->get_sku_count();

        $this->load->view('adminV2/index',$data);
    }

    public function export()
    {
        $this->load->model('goods/category_model', 'category_m');
        $data['category_list'] = $this->category_m->category_cache();
        $data['category_parent_list'] = $this->category_m->category_cache_tree();

        $this->load->model('goods/color_model', 'color_m');
        $data['color_list'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['size_list'] = $this->size_m->size_cache();

        $this->load->view('adminV2/export',$data);
    }

}
