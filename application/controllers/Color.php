<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Color extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('color_model', 'm_color');
    }

    public function action_add_color()
    {
        $this->load->view('goods/color/addForm');
    }

    public function add_color()
    {
        $post = $this->input->post();
        $this->m_color->insert_color($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function delete_color($id)
    {
        $this->m_color->color_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }
}
