<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Color extends HX_Controller
{

    public function action_add_color()
    {
        $this->load->view('goods/color/addForm');
    }

    public function add_color()
    {
        $post = $this->input->post();
        $this->load->model('color_model', 'm_color');
        $this->m_color->insert_color($post);

        $this->load->helper('url');
        redirect("Success");
    }
}
