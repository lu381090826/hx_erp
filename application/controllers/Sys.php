<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys extends HX_Controller
{
    public function index()
    {
        $this->load->view('sys/index');
    }

    public function get_users($page = 0)
    {
        $this->load->model('user_model', 'm_user');
        $result = $this->m_user->get_user_list($page);

        $this->output->set_output(json_encode($result));
    }

    public function get_roles($page = 0)
    {
        $this->load->model('role_model', 'm_role');
        $result = $this->m_role->get_role_list($page);
        $this->output->set_output(json_encode($result));
    }

    //检查手机号是否可用
    public function check_mobile_available()
    {
        $this->load->model('user_model', 'm_user');
        $result = $this->m_user->check_mobile_available($this->input->get());

        $this->output->set_output(json_encode($result));
    }
}
