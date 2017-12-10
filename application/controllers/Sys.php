<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys extends HX_Controller
{
    public function index($page = 1)
    {
        $this->load->model('admin/user_model', 'user_m');
        $result = $this->user_m->get_user_list($page);
var_dump($result);die;
        $result['pagination'] = $this->pagination('/sys/user/',$result['total_num']);

        $this->load->view('sys/index', $result);
    }

    public function user($page = 1)
    {
        $this->load->model('admin/user_model', 'user_m');
        $result = $this->user_m->get_user_list($page);

        $result['pagination'] = $this->pagination('/sys/user/',$result['total_num']);

        $this->load->view('sys/index', $result);
    }

    public function get_users($page = 0)
    {
        $this->load->model('admin/user_model', 'user_m');
        $result = $this->user_m->get_user_list($page);

        json_out_put($result);
    }

    public function get_roles($page = 0)
    {
        $this->load->model('admin/role_model', 'role_m');
        $result = $this->role_m->get_role_list($page);
        json_out_put($result);
    }

    //检查手机号是否可用
    public function check_mobile_available()
    {
        $this->load->model('admin/user_model', 'user_m');
        $result = $this->user_m->check_mobile_available($this->input->get());

        json_out_put($result);
    }

    //角色详情页
    public function auth()
    {
        $this->load->model('admin/authority_model', 'm_auth');
        $auth_list = $this->m_auth->all_auth_list();

        $this->load->view('sys/auth/index', $auth_list);
    }


    public function role($page = 1)
    {
        $this->load->model('admin/role_model', 'role_m');
        $result = $this->role_m->get_role_list($page);

        $result['pagination'] = $this->pagination('/sys/role/',$result['total_num']);

        $this->load->view('sys/role/index', $result);
    }
}
