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
        $auth_ids = $this->getAuthIds($res['role_id']);

        $expire_time = 86400;
        if (!empty($this->input->post('remember_me'))) {
            $expire_time = 86400 * 15;
        }

        $this->session->set_tempdata(
            [
                'name' => $res['name'],
                'mobile' => $res['mobile'],
                'uid' => $res['uid'],
                'role_id' => $res['role_id'],
                'auths' => $auth_ids,
            ]
            , null, $expire_time);

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
        $this->load->model('admin/user_model', 'user_m');
        $res = $this->user_m->get_user_info_by_password();

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
        $this->load->model('admin/ra_model', 'ra_m');
        $auths = $this->ra_m->get_all_by_role_id($role_id);

        $auth_ids = [];
        if (isset($auths['result_rows'])) {
            foreach ($auths['result_rows'] as $row) {
                $auth_ids[] = $row['id'];
            }
            return $auth_ids;
        }
        return $auth_ids;
    }

    public function checkLogin()
    {
        $code = $this->input->get('code');
        $get_token_url = 'https://oapi.dingtalk.com/sns/gettoken?appid=dingoa13doiljtowaexzbj&appsecret=2jYpcZeHicrowSCn4If6hdlGpt3SNxknjZ-EGGyxD-9xcXSnYVe4vEf-X9nq48lt';
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $ret = file_get_contents($get_token_url,false,stream_context_create($arrContextOptions));
        log_out($ret);
        $token = (array)$ret['access_toke'];

        $get_persistent_code = 'https://oapi.dingtalk.com/sns/get_persistent_code?access_token=' . $token;
        $data = ["tmp_auth_code" => $code];
        log_in($data);
        $ret = $this->send_post($get_persistent_code, $data);
        log_out($ret);
        sprintf($ret);
    }

    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    private function send_post($url, $post_data)
    {

        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
}
