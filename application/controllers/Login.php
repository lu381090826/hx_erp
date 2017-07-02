<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->view('login');
    }

    public function confirm()
    {
        $this->load->model('user_model', 'user');
        $res = $this->user->get_user_info();

        if (!$res) {
            show_error('登录失败，请检查用户名和密码');
        }

        $this->session->set_userdata(
            [
                'name' => $res->name,
                'mobile' => $res->mobile,
            ]
        );

        $this->load->helper('url');
        redirect('');
    }

    public function login_out()
    {
        $this->load->helper('url');
        $this->session->sess_destroy();
        redirect('');
    }
}
