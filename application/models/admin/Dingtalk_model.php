<?php

/**
 * 钉钉模块
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Dingtalk_model extends HX_Model
{
    private $appId = 'dingoal1cfrgtqznvz0ete';
    private $appsecret = 'JjrBnmUtM2Odpvr3Tg35DErwHme8DXthMdUAsBNPI9guxYqsYgGJId9DQA7aKkeM';

//    private $corpId = 'dingc3c2773ee7e7819b35c2f4657eb6378f';
//    private $corpSecret = 'Ma035iAZDeXFsBMClECO1Km9JtRNqcmaNpsJqM6vYeBl58x0LxW5u3Xgd5RjyWKT';

    private $corpId = 'dingfdb993e29edfffef35c2f4657eb6378f';
    private $corpSecret = 'd3e_enwvw4HgPF8LEuZp2PTGihMdmKgEaUqaqjohGH_o0Fx_QhN2rHKssXizGcY1';

    private function get_asscess_token()
    {
        //获取access_token
        $get_token_url = 'https://oapi.dingtalk.com/gettoken';
        $params = [
            'corpid' => $this->corpId,
            'corpsecret' => $this->corpSecret,
        ];

        $ret = $this->send_get($get_token_url, $params);
        return $ret['access_token'];
    }

    public function get_user_info_by_code($code)
    {
        //获取access_token
        $get_token_url = 'https://oapi.dingtalk.com/sns/gettoken?appid=dingoa13doiljtowaexzbj&appsecret=2jYpcZeHicrowSCn4If6hdlGpt3SNxknjZ-EGGyxD-9xcXSnYVe4vEf-X9nq48lt';
        $ret = $this->send_get($get_token_url);
        $access_token = $ret['access_token'];

        //获取openid，unionid
        $get_persistent_code = 'https://oapi.dingtalk.com/sns/get_persistent_code?access_token=' . $access_token;
        $data = ["tmp_auth_code" => $code];
        $ret = $this->send_post($get_persistent_code, $data);
        if ($ret['errcode'] != 0) {
            throw new Exception($ret['errmsg'], $ret['errcode']);
        }
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

        log_out($user_info);
        return $user_info;
    }

    /**
     * 发送get请求
     * @param $get_token_url
     * @return mixed
     */
    private function send_get($get_token_url, $params = [])
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        if (!empty($params)) {
            $get_token_url = $get_token_url . '?' . $this->get_method_patams($params);
        }
        $ret = json_decode(file_get_contents($get_token_url, false, stream_context_create($arrContextOptions)), true);
        return $ret;
    }

    /*
     *
     * */
    public function get_dep_list()
    {
        $asscess_token = $this->get_asscess_token();

        $params['access_token'] = $asscess_token;
//        父部门id(如果不传，默认部门为根部门，根部门ID为1)
        $params['id'] = 1;

        $url = "https://oapi.dingtalk.com/department/list";

        return $this->send_get($url, $params);
    }

    /*
     * 获取用户列表
     * */
    public function get_user_list_by_depid($dep_id = 0)
    {
        $asscess_token = $this->get_asscess_token();

        $url = 'https://oapi.dingtalk.com/user/list';
        $params = [
            'access_token' => $asscess_token,
            'department_id' => $dep_id,
        ];
        return $this->send_get($url, $params);
    }

    //组装参数
    private function get_method_patams($array = [])
    {
        $ret = [];
        foreach ($array as $key => $val) {
            $ret[] = "$key=$val";
        }
        return implode("&", $ret);
    }

    public function get_user_info_detail($userid = '')
    {
        $asscess_token = $this->get_asscess_token();
        $params['access_token'] = $asscess_token;

        $url = 'https://oapi.dingtalk.com/user/get';
        $params['userid'] = $userid;

        return $this->send_get($url, $params);
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


}