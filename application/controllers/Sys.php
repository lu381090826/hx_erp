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
        $this->load->model('user_model', 'user');
        $this->user->insert_user($this->input->post());
        $this->load->view('sys/index');
    }

    public function get_users($page = 0)
    {
        $this->load->model('user_model', 'user');
        $result = $this->user->get_user_list($page);
        $this->output->set_output(json_encode($result));
    }

    public function check_mobile_available()
    {
        $this->load->model('user_model', 'user');
        $result = $this->user->check_mobile_available($this->input->get());

        $this->output->set_output(json_encode($result));
    }
}
