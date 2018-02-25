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
        //获取access_token
        $code = $this->input->get('code');
        $get_token_url = 'https://oapi.dingtalk.com/sns/gettoken?appid=dingoa13doiljtowaexzbj&appsecret=2jYpcZeHicrowSCn4If6hdlGpt3SNxknjZ-EGGyxD-9xcXSnYVe4vEf-X9nq48lt';
        $ret = $this->send_get($get_token_url);
        $access_token = $ret['access_token'];

        //获取openid，unionid
        $get_persistent_code = 'https://oapi.dingtalk.com/sns/get_persistent_code?access_token=' . $access_token;
        $data = ["tmp_auth_code" => $code];
        $ret = $this->send_post($get_persistent_code, $data);
        var_dump($ret);
        //钉钉的uid
        $unionid = $ret['unionid'];
        //钉钉的openid
        $openid = $ret['openid'];
        $persistent_code = $ret['persistent_code'];

        //获取SNS_TOKEN
        $url = 'https://oapi.dingtalk.com/sns/get_sns_token?access_token=' . $access_token;
        $data = ['openid' => $openid, 'persistent_code' => $persistent_code];
        $ret = $this->send_post($url, $data);
        $sns_token = $ret['sns_token'];

        //获取个人信息
        $url = 'https://oapi.dingtalk.com/sns/getuserinfo?sns_token=' . $sns_token;
        $ret = $this->send_get($url);
        $user_info = $ret['user_info'];
        var_dump($user_info);
    }

    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    private function send_post($url, $post_data)
    {
        $params = json_encode($post_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params)
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    /**
     * @param $get_token_url
     * @return mixed
     */
    private function send_get($get_token_url)
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $ret = json_decode(file_get_contents($get_token_url, false, stream_context_create($arrContextOptions)), true);
        return $ret;
    }
}
