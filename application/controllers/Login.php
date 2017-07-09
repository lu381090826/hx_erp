<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
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

    //提交密码登录
    public function confirm()
    {
        //检查密码
        $res = $this->checkPassword();

        //获取全部的权限id
        $auth_ids = $this->getAuthIds($res->role_id);

        $this->session->set_userdata(
            [
                'name' => $res->name,
                'mobile' => $res->mobile,
                'uid' => $res->uid,
                'role_id' => $res->role_id,
                'auths' => $auth_ids,
            ]
        );

        $this->load->helper('url');
        redirect('');
    }

    //退出登录
    public function login_out()
    {
        $this->load->helper('url');
        $this->session->sess_destroy();
        redirect('');
    }

    /**
     * @return mixed
     */
    private function checkPassword()
    {
        $this->load->model('user_model', 'user');
        $res = $this->user->get_user_info_by_password();

        if (!$res) {
            show_error('登录失败，请检查用户名和密码');
            return $res;
        }
        return $res;
    }

    /**
     * @param $res
     * @return array
     */
    private function getAuthIds($role_id)
    {
        $this->load->model('ra_model', 'm_ra');
        $auths = $this->m_ra->get_all_by_role_id($role_id);

        $auth_ids = [];
        if (isset($auths['result_rows'])) {
            foreach ($auths['result_rows'] as $row) {
                $auth_ids[] = $row['id'];
            }
            return $auth_ids;
        }
        return $auth_ids;
    }
}
