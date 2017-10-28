<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UrlComponent {
    static function create($_controller){
        //设置
        $url = site_url("$_controller->views/create");

        //返回
        return $url;
    }

    static function update($_controller,$model)
    {
        //设置
        $url = site_url("$_controller->views/update/".$model->{$model->getPk()});

        //返回
        return $url;
    }

    static function delete($_controller,$model)
    {
        //设置
        $url = site_url("$_controller->views/delete/".$model->{$model->getPk()});

        //返回
        return $url;
    }
}