<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 店铺管理
 * */

class shop extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods/shop_model', 'shop_m');
    }

    public function action_add_shop()
    {
        $this->load->view('goods/shop/addForm');
    }

    public function shop_add()
    {
        $post = $this->input->post();
        $this->shop_m->insert_shop($post);

        $this->load->helper('url');
        redirect("success");
    }

    public function shop_delete($id)
    {
        $this->shop_m->shop_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }

    public function shop_detail($id)
    {
        $r = $this->shop_m->shop_detail_by_id($id);

        $this->load->view('goods/shop/detail',$r['result_rows']);
    }
}
