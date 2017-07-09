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