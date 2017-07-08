<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys extends HX_Controller
{
    public function index()
    {
        $this->load->view('sys/index');
    }

    public function action_add_user()
    {
        $this->load->view('sys/user/addForm');
    }

    public function add_users()
    {
        $this->load->model('user_model', 'm_user');
        $this->m_user->insert_user($this->input->post());
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
        $this->load->model('role_model', 'm_roles');
        $result = $this->m_roles->get_role_list($page);
        $this->output->set_output(json_encode($result));
    }

    public function check_mobile_available()
    {
        $this->load->model('user_model', 'm_user');
        $result = $this->m_user->check_mobile_available($this->input->get());

        $this->output->set_output(json_encode($result));
    }
}
