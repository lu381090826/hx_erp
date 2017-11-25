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

if (!function_exists('config_load')) {
    function config_load($config, $name = '')
    {
        $CI = &get_instance();
        $CI->config->load($config, $config);
        if ($name != '') {
            return $CI->config->item($config)[$name];
        } else {
            return $CI->config->item($config);
        }
    }
}

if (!function_exists('json_out_put')) {
    function json_out_put($arr)
    {
        $CI = &get_instance();
        $CI->output->set_header('Cache-Control: no-cache');
        $CI->output->set_header('Content-type:application/json;charset=utf-8');
        $CI->output->set_output(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}

//根据二维数组的建值去重
if (!function_exists('arr_sort')) {
    function arr_sort($array, $key, $order = "asc")
    {
        $arr_nums = $arr = array();
        foreach ($array as $k => $v) {
            $arr_nums[$k] = $v[$key];
        }
        if ($order == 'asc') {
            asort($arr_nums);
        } else {
            arsort($arr_nums);
        }
        foreach ($arr_nums as $k => $v) {
            $arr[$k] = $array[$k];
        }
        return $arr;
    }
}

//log_in
if (!function_exists('log_in')) {
    function log_in($request = '')
    {
        $CI = &get_instance();
        $in['uri'] = $CI->uri->rsegments;
        $in['benchmark'] = $CI->benchmark;
        $in['in_info'] = $request;
        $in['user_session'] = isset($CI->session) ? $CI->session : '';
        log_message('INFO', json_encode($in, JSON_UNESCAPED_UNICODE));
    }
}
//log_out
if (!function_exists('log_out')) {
    function log_out($request = '')
    {
        $CI = &get_instance();
        $in['benchmark'] = $CI->benchmark;
        $in['out_info'] = $request;
        $in['user_session'] = isset($CI->session) ? $CI->session : '';
        log_message('INFO', json_encode($in, JSON_UNESCAPED_UNICODE));
    }
}
//log_out
if (!function_exists('log_error')) {
    function log_error($request = '')
    {
        $CI = &get_instance();
        $in['uri'] = $CI->uri->rsegments;
        $in['benchmark'] = $CI->benchmark;
        $in['error_info'] = $request;
        $in['user_session'] = isset($CI->session) ? $CI->session : '';
        log_message('ERROR', json_encode($in, JSON_UNESCAPED_UNICODE));
    }
}
//log_out
if (!function_exists('adminV2View')) {
    function view_adminV2($path, $params)
    {
        $CI = &get_instance();
        $CI->load->view('adminV2/' . $path, $params);
    }
}


