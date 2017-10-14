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
        $this->load->model('goods/category_model', 'm_category');
    }

    public function action_add_category()
    {
        $category_list = $this->m_category->get_all_list();
        $data['category_list'] = $category_list['result_rows'];
        $this->load->view('goods/category/addForm', $data);
    }

    public function add_category()
    {
        $post = $this->input->post();
        $this->m_category->insert_category($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function delete_category($id)
    {
        $this->m_category->category_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }
}
