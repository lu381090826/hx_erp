<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends HX_Controller
{
    public function user_detail($uid)
    {
        $this->load->model('admin/user_model', 'user_m');
        $user_info = $this->user_m->get_row_by_uid($uid);

        $this->load->library('parser');
        $this->parser->parse('sys/user/detail', $user_info['result_rows']);
    }

    //添加用户页
    public function action_add_user()
    {
        $this->load->model('admin/role_model', 'role_m');
        $data['role_list'] = $this->role_m->get_row_by_pid($this->session->role_id);

        $this->load->view('sys/user/addForm', $data);
    }

    public function add_users()
    {
        $this->load->model('admin/user_model', 'user_m');
        $this->user_m->insert_user($this->input->post());

        $this->load->helper('url');
        redirect("success");
    }

    public function edit_users()
    {
        $this->load->model('admin/user_model', 'user_m');
        $this->user_m->update_user($this->input->post());

        $this->load->helper('url');
        redirect("success");
    }
}
