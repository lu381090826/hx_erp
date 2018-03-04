<?php

/**
 * 钉钉模块
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Dingtalk_model extends HX_Model
{
    private $appId = 'dingoa13doiljtowaexzbj';
    private $appsecret = '2jYpcZeHicrowSCn4If6hdlGpt3SNxknjZ-EGGyxD-9xcXSnYVe4vEf-X9nq48lt';

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

}