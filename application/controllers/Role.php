<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends HX_Controller
{
    //角色详情页
    public function role_detail($id)
    {
        $this->load->model('role_model', 'm_role');
        $role_info = $this->m_role->get_row_by_id($id);

        $this->load->model('ra_model', 'm_ra');
        $auths = $this->m_ra->get_all_by_role_id($id);

        if (!empty($auths)) {
            $role_info['result_rows']['auths'] = $auths['result_rows'];
        }

        $this->load->view('sys/role/detail', $role_info['result_rows']);
    }

}
