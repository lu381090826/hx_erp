<?php
/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/7/8
 * Time: 下午3:47
 */

/** * * 后台权限拦截钩子 */
class ManageAuth
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    //控制器权限控制配置
    private static $auth_controller_config = [
        'login' => '',
        'sys' => '6',
    ];

    //方法权限控制配置
    private static $auth_method_config = [
        '/user/add_users' => '6',
    ];

    /***权限认证*/
    public function auth()
    {
        $controller = strtolower($this->CI->uri->segment(1));
        $method = '/' . $controller . '/' . strtolower($this->CI->uri->segment(2));
        $controller_check_ok = 0;
        $method_check_ok = 0;

        //登录操作不需要判断
        if ($controller == 'login') {
            return;
        }
        //判断是否登录
        $this->CI->load->library('session');
        if (!isset($this->CI->session->role_id) || !isset($this->CI->session->name) || !isset($this->CI->session->uid)) {
            $this->CI->load->helper('url');
            redirect('/Login');
        }

        //没有该配置就默认允许
        if (!isset(self::$auth_controller_config[$controller]) || empty(self::$auth_controller_config[$controller])) {
            $controller_check_ok = 1;
        }
        if (!isset(self::$auth_method_config[$method]) || empty(self::$auth_method_config[$controller])) {
            $method_check_ok = 1;
        }
        if ($controller_check_ok && $method_check_ok) {
            return;
        }


        //1为超级权限
        if ($this->CI->session->role_id == 1) {
            return;
        }

        //获取权限
        $auths = $this->CI->session->auths;

        //检查控制器和方法是否允许
        if (!empty($auths['result_rows'])) {
            foreach ($auths['result_rows'] as $row) {
                if ($controller_check_ok == 0 && self::$auth_controller_config[$controller] == $row['id']) {
                    $controller_check_ok = 1;
                    if ($controller_check_ok && $method_check_ok) {
                        return;
                    }
                }
                if ($method_check_ok == 0 && self::$auth_method_config[$method] == $row['id']) {
                    $method_check_ok = 1;
                    if ($controller_check_ok && $method_check_ok) {
                        return;
                    }
                }
            }
        }
        show_error("没有权限访问");
    }
}