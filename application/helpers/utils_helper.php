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

if (!function_exists('json_ajax_out_put')) {
    function json_ajax_out_put($code = 0, $msg = '', $arr = [])
    {
        $ret['code'] = $code;
        $ret['msg'] = $msg;
        $ret['data'] = $arr;

        $CI = &get_instance();
        $CI->output->set_header('Cache-Control: no-cache');
        $CI->output->set_header('Content-type:application/json;charset=utf-8');
        $CI->output->set_output(json_encode($ret, JSON_UNESCAPED_UNICODE));
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
//log_error
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
//adminV2View
if (!function_exists('adminV2View')) {
    function view_adminV2($path, $params)
    {
        $CI = &get_instance();
        $CI->load->view('adminV2/' . $path, $params);
    }
}

if (!function_exists('upload_file')) {
    /**
     * @param string $file_param_name 文件字段名
     * @return array
     */
    function upload_file($file_param_name = 'pic')
    {
        $file_path = '/sku_pic_uploads/' . date("Ymd") . '/';
        if (!file_exists(FCPATH . 'sku_pic_uploads/')) {
            mkdir(FCPATH . 'sku_pic_uploads/');
        }
        $config['upload_path'] = FCPATH . 'sku_pic_uploads/' . date("Ymd") . '/';
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $CI = &get_instance();
        $CI->load->library('upload', $config);
        if (!$CI->upload->do_upload($file_param_name)) {
            $error = array('error' => $CI->upload->display_errors());

            show_error($error);
        } else {
            $data = array('upload_data' => $CI->upload->data());
            $real_path = FCPATH . $file_path . $data['upload_data']['file_name'];
            $new_real_path = resize_image($real_path, 80, 100, $data['upload_data']['file_name'], $config['upload_path']);
            return ['small_path' => $new_real_path, 'normal_path' => $file_path . $data['upload_data']['file_name']];
        }
    }
}

/*
     * 切割图片
     * */
if (!function_exists('resize_image')) {
    function resize_image($uploadfile, $maxwidth, $maxheight, $name, $path)
    {
        $uploadedfile = $uploadfile;
        $src = imageCreateFromAny($uploadedfile);

        list($width, $height) = getimagesize($uploadedfile);

        $newwidth = $maxwidth;
        $newheight = ($height / $width) * $maxheight;
        $tmp = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $newPath = $path . "small_images/";
        if (!file_exists($newPath)) {
            mkdir($newPath);
        }
        $filename = $newPath . $name;
        imagejpeg($tmp, $filename, 100);
        imagedestroy($src);
        imagedestroy($tmp);

        return '/sku_pic_uploads/' . date("Ymd") . '/small_images/' . $name;
    }
}

if (!function_exists('imageCreateFromAny')) {
    function imageCreateFromAny($filepath)
    {
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            1,  // [] gif
            2,  // [] jpg
            3,  // [] png
            6   // [] bmp
        );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        $im = null;
        switch ($type) {
            case 1 :
                $im = imageCreateFromGif($filepath);
                break;
            case 2 :
                $im = imageCreateFromJpeg($filepath);
                break;
            case 3 :
                $im = imageCreateFromPng($filepath);
                break;
//            case 6 :
//                $im = imageCreateFromBmp($filepath);
//                break;
        }
        return $im;
    }
}

