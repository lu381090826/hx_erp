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
        $this->load->model('admin/user_model', 'user_m');
        $r['seller_list'] = $this->user_m->get_seller(null, true)['result_rows'];

        $this->load->view('goods/shop/addForm', $r);
    }

    public function shop_add()
    {
        $post = $this->input->post();
        $file_param_name = 'alipay_code_img';
        if (!empty($_FILES[$file_param_name]['size'])) {
            $post['alipay_code_img'] = upload_file($file_param_name)['normal_path'];
        }
        $file_param_name = 'wx_code_img';
        if (!empty($_FILES[$file_param_name]['size'])) {
            $post['wx_code_img'] = upload_file($file_param_name)['normal_path'];
        }

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

        $this->load->model('admin/user_model', 'user_m');
        $r['result_rows']['seller_list'] = $this->user_m->get_seller($id)['result_rows'];

        $this->load->view('goods/shop/detail', $r['result_rows']);
    }

    public function shop_detail_edit($id)
    {
        $r = $this->shop_m->shop_detail_by_id($id);

        $this->load->model('admin/user_model', 'user_m');
        $r['result_rows']['seller_list'] = $this->user_m->get_seller($id)['result_rows'];

        $sellers = $this->user_m->get_seller_shop($id);

        foreach ($r['result_rows']['seller_list'] as &$row) {
            if (array_keys($sellers, ['seller_id' => $row['uid']])) {
                $row['is_check'] = true;
            } else {
                $row['is_check'] = false;
            }
        }
        $this->load->view('goods/shop/editForm', $r['result_rows']);
    }

    public function shop_modify()
    {
        $post = $this->input->post();

        $file_param_name = 'alipay_code_img';
        if (!empty($_FILES[$file_param_name]['size'])) {
            $post['alipay_code_img'] = upload_file($file_param_name)['normal_path'];
        }
        $file_param_name = 'wx_code_img';
        if (!empty($_FILES[$file_param_name]['size'])) {
            $post['wx_code_img'] = upload_file($file_param_name)['normal_path'];
        }

        $this->shop_m->update_shop($post);

        $this->load->helper('url');
        redirect("success");
    }
}
