<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/7/9
 * Time: 上午11:31
 */

if (!function_exists('check_auth')) {
    function check_auth($auth_id)
    {
        $CI = &get_instance();
        if (isset($CI->session->role_id) && $auth_id > 0) {
            if ($CI->session->role_id == 1) {
                return true;
            }
            if (isset($CI->session->auths) && in_array($auth_id, $CI->session->auths)) {
                return true;
            }
            return false;
        }
    }
}

if (!function_exists('json_out_put')) {
    function json_out_put($arr)
    {
        $CI = &get_instance();
        $CI->output->set_header('Cache-Control: no-cache');
        $CI->output->set_header('Content-type:application/json;charset=utf-8');
        $CI->output->set_output(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
}