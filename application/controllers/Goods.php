<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Goods extends HX_Controller
{

    public function index()
    {
        $this->load->model('goods/category_model', 'm_category');
        $data['category_list'] = $this->m_category->get_all_list()['result_rows'];

        $this->load->view('goods/index',$data);
    }

    public function get_category($page = 1)
    {
        $this->load->model('goods/category_model', 'm_cagegory');
        $result = $this->m_cagegory->get_list($page);

        json_out_put($result);
    }

    public function get_color($page = 0)
    {
        $this->load->model('goods/color_model', 'color_m');
        $result = $this->color_m->get_color_list($page);

        json_out_put($result);
    }

    public function get_size($page = 0)
    {
        $this->load->model('goods/size_model', 'size_m');
        $result = $this->size_m->get_size_list($page);

        json_out_put($result);
    }

    public function get_shop($page = 0)
    {
        $this->load->model('goods/shop_model', 'shop_m');
        $result = $this->shop_m->get_shop_list($page);

        json_out_put($result);
    }

    public function get_sku($page = 0)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list($page);

        json_out_put($result);
    }

    public function get_sku_list_info($goods_id)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list_info_by_goods_id($goods_id);

        json_out_put($result);
    }

    public function goods_detail($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_info_by_goods_id($goods_id)['result_rows'];
        $data['result_rows']['color_list'] = arr_sort($data['result_rows']['sku_list'],'color_id');

        $this->load->model('goods/color_model', 'color_m');
        $data['result_rows']['color_cache'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['result_rows']['size_cache'] = $this->size_m->size_cache();

        $this->load->model('goods/category_model', 'category_m');
        $data['result_rows']['category_list'] = $this->category_m->category_cache();

        $this->load->view('/goods/goods/detail', $data['result_rows']);
    }

    public function goods_detail_edit($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_by_goods_id($goods_id);

        $this->load->model('goods/category_model', 'category_m');
        $data['result_rows']['category_list'] = $this->category_m->category_cache();

        $this->load->view('/goods/goods/detail_edit', $data['result_rows']);
    }

    public function get_goods($page = 0)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $request=$this->input->post();
        $request['page'] = $page;

        $res = $this->goods_m->search_goods($request);
        json_out_put($res);
    }

    public function search_goods(){
        $this->load->model('goods/goods_model', 'goods_m');

        $request = $this->input->post();

        $result = $this->goods_m->search_goods($request);

        json_out_put($result);
    }
}
