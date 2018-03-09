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

    //从钉钉添加用户
    public function action_add_user_from_dingtalk()
    {
        //获取钉钉部门
        $this->load->model('admin/dingtalk_model', 'dingtalk_m');
        $ret = $this->dingtalk_m->get_dep_list();
        if ($ret['errmsg'] != 0) {
            throw new Exception($ret['errmsg'], $ret['errcode']);
        }

        $dep_list = [];
        if (!empty($ret['department'])) {
            foreach ($ret['department'] as $row) {
                $dep['dep_id'] = $row['id'];
                $dep['dep_name'] = $row['name'];
                $dep_list[] = $dep;
            }
        }
        $data['dep_list'] = $dep_list;


        //获取角色
        $this->load->model('admin/role_model', 'role_m');
        $data['role_list'] = $this->role_m->get_row_by_pid($this->session->role_id);


        $this->load->view('sys/user/addFormFromDingtalk', $data);
    }

    //从钉钉添加用户
    public function get_user_list_by_dep_id($dep_id = 0)
    {
        //获取钉钉部门
        $this->load->model('admin/dingtalk_model', 'dingtalk_m');
        $ret = $this->dingtalk_m->get_user_list_by_depid($dep_id);
        if ($ret['errmsg'] != 0) {
            throw new Exception($ret['errmsg'], $ret['errcode']);
        }

        $user_list = [];

        if (!empty($ret['userlist'])) {
            foreach ($ret['userlist'] as $row) {
                $user['userid'] = $row['userid'];
                $user['email'] = isset($row['email']) ? $row['email'] : "";
                $user['name'] = $row['name'];
                $user['mobile'] = $row['mobile'];
                $user_list[] = $user;
            }
        }

        json_ajax_out_put($ret['errcode'], $ret['errmsg'], $user_list);
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

    public function delete_user($uid)
    {
        $this->load->model('admin/user_model', 'user_m');
        $arr = [
            'status' => 3,
            'uid' => $uid,
            'memo' => sprintf("被%s禁用", $this->session->name),
        ];
        $this->user_m->update_user($arr);

        $this->load->helper('url');
        redirect("success");
    }

    public function test()
    {
        echo 123;
    }
}
