<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends HX_Controller
{
    public function user_detail($uid)
    {
        $this->load->model('user_model', 'm_user');
        $user_info = $this->m_user->get_row_by_uid($uid);

        $this->load->library('parser');
        $this->parser->parse('sys/user/detail', $user_info['result_rows']);
    }

    //添加用户页
    public function action_add_user()
    {
        $this->load->model('role_model', 'm_role');
        $data['role_list'] = $this->m_role->get_row_by_pid($this->session->role_id);

        $this->load->view('sys/user/addForm', $data);
    }

    public function add_users()
    {
        $this->load->model('user_model', 'm_user');
        $this->m_user->insert_user($this->input->post());
        $this->load->view('sys/index');
    }
}
