<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" type="text/css" href="/assets/css/amazeui.page.css">

<div style="margin-top: 5%;margin-bottom: 5%;margin-left: 5%;">
    <select data-am-selected>
        <option value="a" selected>用户管理</option>
        <option value="b">角色管理</option>
        <option value="c">权限管理</option>
    </select>

    <button type="button" class="am-btn am-btn-success" onclick="window.location.href='/sys/action_add_user'">添加用户
    </button>
</div>


<table class="am-table">
    <thead>
    <tr>
        <th>id</th>
        <th>用户名</th>
        <th>手机号</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="from_contant">
    </tbody>
</table>
<div id="page"></div>

<?php $this->load->view('footer'); ?>
<script type="text/javascript" src="/assets/js/amazeui.page.js"></script>
<script>
    $(document).ready(function () {
        $.get("/sys/get_users/1", function (result) {
            if (result.pages > 1) {
                $("#page").page({
                    pages: result.pages,
                    first: "首页", //设置false则不显示，默认为false
                    last: "尾页", //设置false则不显示，默认为false
                    prev: '<', //若不显示，设置false即可，默认为上一页
                    next: '>', //若不显示，设置false即可，默认为下一页
                    groups: 3, //连续显示分页数
                    jump: function (context, first) {
                        getContent(context.option.curr)
                    }
                })
            } else {
                getContent(1)
            }
        }, 'JSON');

    });
    function getContent(curr) {
        $('#from_contant').empty();
        $.get("/sys/get_users/" + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                $("#from_contant").append("<tr><td>" + o.index + "</td><td>" + o.name + "</td><td>" + o.mobile + "</td><td>查询</td></tr>")
            })
        }, 'JSON')
    }


</script>