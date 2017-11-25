<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Category extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods/category_model', 'category_m');
    }

    public function action_add_category()
    {
        $data['category_list'] = $this->category_m->category_cache_series();
        $this->load->view('goods/category/addForm', $data);
    }

    public function action_show_child($pid)
    {
        $category_list = $this->category_m->category_cache_tree();
        $data = $category_list[$pid]['childs'];
        json_out_put($data);
    }

    public function add_category()
    {
        $post = $this->input->post();
        $this->category_m->insert_category($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function delete_category($id)
    {
        $this->category_m->category_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }
}
