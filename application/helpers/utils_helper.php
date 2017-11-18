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

if (!function_exists("export_xlsx")) {
    function export_xlsx($arr)
    {
        $CI = &get_instance();
//加载PHPExcel的类
        $CI->load->library('phpexcel');
        $CI->load->library('phpexcel/iofactory');
//创建PHPExcel实例
        $excel = $CI->phpexcel;
//下面介绍项目中用到的几个关于excel的操作
//为单元格赋值
        $excel->getActiveSheet()->setCellValue('A1', 'aaa');
//合并单元格
        $excel->getActiveSheet()->mergeCells('A1:A2');
//设置单元格内文字垂直居中
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//设置单元格内文字自动换行
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
//为单元格添加注释
        $excel->getActiveSheet()->getComment('A1')->getText()->createTextRun('hello');
//设置单元格文字颜色
        $excel->getActiveSheet()->getStyle('A1')->getFont()->getColor->setARGB(PHPExcel_Style_Color::COLOR_RED);
//输出到浏览器
        $write = new PHPExcel_Writer_Excel2007($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="test.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }
}


