<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->view('login');
    }

    public function testDK()
    {
        $this->load->model('admin/dingtalk_model', 'dingtalk_m');
        $ret = $this->dingtalk_m->get_user_list_by_depid();
        var_dump($ret);die;
    }
}
