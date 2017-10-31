<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ViewComponent
{
    /**
     * @param $_controller
     * @param $searched
     * @param $fields
     * @return string
     */
    static function DataGrid($_controller,$searched,$fields)
    {
        //设置Html开头
        $html = '<table class="am-table am-table-striped am-table-hover table-main">' .
            '<thead>' .
            '<tr>';

        //设置标题
        foreach ($fields as $field) {
            $field_name = $searched->model->getLabel($field);
            $sort_result = self::url_set_sort(current_url(),$_GET,$field);
            $sort_url = $sort_result->url;
            $sort_icon="";
            if($sort_result->status=="ASC")
                $sort_icon = '<i class="am-icon-sort-desc"></i>';
            else if($sort_result->status=="DESC")
                $sort_icon = '<i class="am-icon-sort-asc"></i>';
            $html .= "<th class='table-$field'>".
                "<a href='$sort_url'>$field_name $sort_icon</a>"
                ."</th>";
        }
        $html .= '<th class="table-set">操作</th>';

        //其余Html内容
        $html .= '</tr></thead><tbody>';

        //行内容
        foreach ($searched->list as $key => $item) {
            $html .= "<tr>";

            //字段
            foreach ($fields as $field) {
                $html .= "<td>".$item->{$field}."</td>";
            }

            //按钮
            /*$html .= '<td>'.
                '<div class="am-btn-toolbar">'.
                '<div class="am-btn-group am-btn-group-xs">'.
                '<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="'.UrlComponent::update($_controller,$item).'"><span class="am-icon-pencil-square-o"></span> 编辑 </a>'.
                //'<a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" href="'.UrlComponent::delete($_controller,$item).'"><span class="am-icon-trash-o"></span> 删除 </a>'.
                '<a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" href="javascript:if(window.confirm(\'确定删除数据?\')) window.location.href=\''.UrlComponent::delete($_controller,$item).'\'"><span class="am-icon-trash-o"></span> 删除 </a>'.
                '</div>'.
                '</div>';*/
            $html .= '<td>'.
                '<a href="'.UrlComponent::update($_controller,$item).'"> 编辑 </a>'.
                '<a href="javascript:if(window.confirm(\'确定删除数据?\')) window.location.href=\''.UrlComponent::delete($_controller,$item).'\'"> 删除 </a>'.
                '</td>';


            $html .= "</tr>";
        }

        //余下其他内容
        $html .= '</tbody></table>';

        return $html;
    }

    /**
     * @param $model
     * @param $filed
     * @return string
     */
    static function FieldInput($model, $filed)
    {
        //设置变量
        $label = $model->getLabel($filed);
        $value = $model->{$filed};
        $error = "";

        //设置html
        $html =
            <<<html
                        <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    $label
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input type="text" class="am-input-sm" name="$filed" value="$value">
                </div>
                <div class="am-hide-sm-only am-u-md-6">$error</div>
            </div>
html;

        //返回
        return $html;
    }

    /**
     * @param $page
     * @param $size
     * @param $total
     * @return string
     */
    static function PagesBar($page, $size, $total)
    {
        //计算最大页码
        $max = (($total / $size) > (int)($total / $size))?(int)($total / $size) +1:(int)($total / $size);
        $show_count = 3;

        //设置头html
        $html = '<div class="am-cf">' .
            "共 $total 条记录" .
            '<div class="am-fr">' .
            '<ul class="am-pagination">';

        //第一页
        if ($page > 1)
            $html .= '<li><a href="' . self::pages_url(current_url(), $_GET, 1) . '">«</a></li>';

        //前面页
        for ($i = $page - $show_count; $i < $page; $i++) {
            if ($i < 1) continue;
            $html .= '<li><a href="' . self::pages_url(current_url(), $_GET, $i) . '">' . $i . '</a></li>';
        }

        //当前页
        $html .= '<li class="am-active"><a>' . $page . '</a></li>';

        //后面页
        for ($i = $page + 1; $i <= $max; $i++) {
            if ($i - $page > $show_count) break;
            $html .= '<li><a href="' . self::pages_url(current_url(), $_GET, $i) . '">' . $i . '</a></li>';
        }

        //最后页
        if ($page < $max)
            $html .= '<li><a href="' . self::pages_url(current_url(), $_GET, $max) . '">»</a></li>';

        //设置尾html
        $html .= "</ul></div></div>";

        //返回
        return $html;
    }

    /**
     * @param $url
     * @param $param
     * @param null $page
     * @param null $size
     * @return string
     */
    static private function pages_url($url, $param, $page = null, $size = null)
    {
        //替换参数
        if ($page)
            $param['page'] = $page;
        if ($size)
            $param['size'] = $size;

        //拼凑参数
        if (is_array($param) && count($param) > 0) {
            $url .= "?";
            $isFirst = true;
            foreach ($param as $key => $value) {
                //加添&
                if ($isFirst)
                    $isFirst = false;
                else
                    $url .= "&";

                //参数
                $url .= "$key=$value";
            }
        }

        //返回
        return $url;
    }

    /**
     * @param $url
     * @param $param
     * @param $field
     * @param null $type
     * @return string
     */
    static private function url_set_sort($url, $param, $field, $type=null, $isCover=true){
        //获取排序参数
        $sort = isset($param['sort'])?(array)json_decode($param['sort']):[];

        //获取旧状态
        $status = isset($sort[$field])?strtoupper($sort[$field]):null;

        //如果存在该排序字段
        if(isset($sort[$field])){
            //如果指定了排序类型
            if($type)
                $sort[$field] = $type;
            //如果没指定，则翻转
            else
            {
                if(strtolower($sort[$field]) == "asc")
                    $sort[$field] = "DESC";
                else
                    $sort[$field] = "ASC";
            }
        }
        //如果不存在该排序字段
        else
        {
            //如果指定了排序类型
            if($type)
                $sort[$field] = $type;
            //如果没指定，则为ASC
            else
                $sort[$field] = "ASC";
        }

        //是否覆盖掉其他参数
        if($isCover)
            $sort = array($field=>$sort[$field]);

        //替换参数
        $param["sort"] = json_encode($sort);

        //拼凑参数
        if (is_array($param) && count($param) > 0) {
            $url .= "?";
            $isFirst = true;
            foreach ($param as $key => $value) {
                //加添&
                if ($isFirst)
                    $isFirst = false;
                else
                    $url .= "&";

                //参数
                $url .= "$key=$value";
            }
        }

        //返回
        $result = (object)array();
        $result->url = $url;
        $result->status = $status;
        return $result;
    }
}