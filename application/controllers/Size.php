<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 尺码管理
 * */

class Size extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods/size_model', 'm_size');
    }

    public function action_add_size()
    {
        $this->load->view('goods/size/addForm');
    }

    public function add_size()
    {
        $post = $this->input->post();
        $this->m_size->insert_size($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function delete_size($id)
    {
        $this->m_size->size_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }
}
