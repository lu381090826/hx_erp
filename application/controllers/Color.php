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
        $this->load->model('goods/color_model', 'color_m');
    }

    public function action_add_color()
    {
        $this->load->view('goods/color/addForm');
    }

    public function add_color()
    {
        $post = $this->input->post();
        $this->color_m->insert_color($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function delete_color($id)
    {
        $this->color_m->color_delete_by_id($id);
    }
}
