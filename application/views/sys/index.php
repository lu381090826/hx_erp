<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" type="text/css" href="/assets/css/amazeui.page.css">
<br>
<!--功能选项-->
<select data-am-selected id="sys_select" title="选择功能">
    <option value="get_users" selected>用户管理</option>
    <option value="get_roles">角色管理</option>
    <option value="get_auths">权限管理</option>
</select>
<br>
<br>
<div id="from_buttons">

</div>
<hr>
<!--表格子-->
<table class="am-table">
    <thead id="from_thead">
    </thead>
    <tbody id="from_contant">
    </tbody>
</table>
<br>

<!--分页-->
<div id="page"></div>

<?php $this->load->view('footer'); ?>

<script type="text/javascript" src="/assets/js/amazeui.page.js"></script>
<script>
    var controller = 'sys';
    var method = $('#sys_select').val();
    var from_thead = $('#from_thead');
    var from_contant = $('#from_contant');
    var from_buttons = $('#from_buttons');

    function getContentUrl() {
        return '/' + controller + '/' + method + '/';
    }

    //清空表
    function fromClean() {
        from_thead.empty();
        from_contant.empty();
        from_buttons.empty();
    }

    $(document).ready(function () {
        fromLoad();
    });

    //展示分页
    function fromLoad() {
        $.get(getContentUrl() + "1", function (result) {
            if (result.pages > 1) {
                $("#page").page({
                    pages: result.pages,
                    first: "首页", //设置false则不显示，默认为false
                    last: "尾页", //设置false则不显示，默认为false
                    prev: '<', //若不显示，设置false即可，默认为上一页
                    next: '>', //若不显示，设置false即可，默认为下一页
                    groups: 3, //连续显示分页数
                    jump: function (context, first) {
                        eval(method + '(' + context.option.curr + ')');
                    }
                })
            } else {
                eval(method + '(1)');
            }
        }, 'JSON');
    }


    //下拉框选择方法
    $(function () {
        var $selected = $('#sys_select');
        $selected.on('change', function () {
            fromClean();
            method = $(this).val();
            //用字符串执行方法
            eval(method + '(1)');
        });
    });

    function get_users(curr) {
        var content = "<tr> <th>id</th> <th>用户名</th> <th>手机号</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
                    "<td>" + o.uid + "</td>" +
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.mobile + "</td>" +
                    "<td><a href='/user/user_detail/" + o.uid + "'>查询详情</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
        content = "<button type=\"button\" class=\"am-btn am-btn-success\" onclick=\"window.location.href='/sys/action_add_user'\">添加用户 </button>";
        from_buttons.append(content)
    }

    function get_roles(curr) {
        var content = "<tr> <th>id</th> <th>角色名</th> <th>创建时间</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                var content = "<tr>" +
                    "<td>" + o.id + "</td>" +
                    "<td>" + o.role_name + "</td>" +
                    "<td>" + o.create_time + "</td>" +
                    "<td><a href='/role/role_detail/" + o.id + "'>查询详情</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }
</script>