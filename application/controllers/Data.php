<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Data extends HX_Controller
{

    public function index()
    {
        $this->load->view('adminV2/index');
    }

    public function export()
    {
        $this->load->model('goods/category_model', 'category_m');
        $data['category_list'] = $this->category_m->category_cache();

        $this->load->model('goods/color_model', 'color_m');
        $data['color_list'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['size_list'] = $this->size_m->size_cache();

        $this->load->view('adminV2/export',$data);
    }

}
