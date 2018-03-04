<?php

/**
 * 钉钉模块
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Dingtalk_model extends HX_Model
{
    private $appid = 'dingoa13doiljtowaexzbj';
    private $appsecret = '2jYpcZeHicrowSCn4If6hdlGpt3SNxknjZ-EGGyxD-9xcXSnYVe4vEf-X9nq48lt';

    //code
    private function get_asscess_token()
    {
        //获取access_token
        $get_token_url = "https://oapi.dingtalk.com/sns/gettoken?appid={$this->appid}&appsecret={$this->appsecret}";
        $ret = $this->send_get($get_token_url);
        return $ret['access_token'];
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

    /*
     *
     * */
    public function get_user_info($code)
    {
        $asscess_token = $this->get_asscess_token();
        $url = 'https://oapi.dingtalk.com/user/getuserinfo?access_token=' . $asscess_token . '&code=' . $code;
        return $this->send_get($url);
    }
    /*
     * 获取用户列表
     * */
    public function get_user_list_by_depid($dep_id = 0)
    {
        $asscess_token = $this->get_asscess_token();
        $url = 'https://oapi.dingtalk.com/user/simplelist?access_token=' . $asscess_token . '&department_id=' . $dep_id;
        return $this->send_get($url);
    }


}