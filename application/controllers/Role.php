<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends HX_Controller
{
    //角色详情页
    public function role_detail($id)
    {
        $this->load->model('role_model', 'm_role');
        $role_info = $this->m_role->get_row_by_id($id);

        $this->load->model('admin/ra_model', 'm_ra');
        $auths = $this->m_ra->get_all_by_role_id($id);

        if (!empty($auths)) {
            $role_info['result_rows']['auths'] = $auths['result_rows'];
        }

        $this->load->view('sys/role/detail', $role_info['result_rows']);
    }

    //添加角色页
    public function action_add_role()
    {
        $this->load->model('admin/authority_model', 'm_authority');
        $data['auth_list'] = $this->m_authority->get_all_by_auth_pids($this->session->auths);

        $this->load->view('sys/role/addForm', $data);
    }

    //添加角色操作
    public function add_role()
    {
        $post = $this->input->post();
        //1为超级权限
        if ($this->session->role_id != 1 && array_diff($post['auth_ids'], $this->session->auths)) {
            show_error('非法请求');
        }
        $post['role_id'] = $this->session->role_id;
        $this->load->model('role_model', 'm_role');;
        $insert_id = $this->m_role->add_new_role($post);

        if ($insert_id) {
            $this->load->model('admin/ra_model', 'm_ra');
            $req['role_id'] = $insert_id;
            $req['auth_ids'] = empty($post['auth_ids']) ? [] : $post['auth_ids'];
            $this->m_ra->insert_new_ra($req);
        }

        $this->load->helper('url');
        redirect("success");
    }

}
