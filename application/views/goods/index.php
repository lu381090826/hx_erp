<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<br>
<!--功能选项-->
<select data-am-selected id="method_select" title="选择功能">
    <option value="get_info_list" selected>商品信息</option>
    <option value="get_category">商品设置</option>
    <!--    <option value="get_auths">权限管理</option>-->
</select>
<br>
<br>
<div class="other-select div-get_users">
    <button type="button" class="am-btn am-btn-success"
            onclick="window.location.href='/user/action_add_user'">新建商品
    </button>
</div>
<div class="other-select div-get_roles">
    <button type="button" class="am-btn am-btn-success"
            onclick="window.location.href='/role/action_add_role'">新建分类
    </button>
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
<script type="text/javascript" src="/assets/js/common/from.js"></script>
<script>
    $(document).ready(function () {
        fromLoad('goods');

    });

    function get_info_list(curr) {
        var content = "<tr> <th>商品名</th> <th>手机号</th> <th>角色</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
//                    "<td>" + o.uid + "</td>" +
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.mobile + "</td>" +
                    "<td>" + o.role_name + "</td>" +
                    "<td><a href='/user/user_detail/" + o.uid + "'>查询详情</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }

    function get_category(curr) {
        var content = "<tr> <th>用户名</th> <th>手机号</th> <th>角色</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
//                    "<td>" + o.uid + "</td>" +
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.mobile + "</td>" +
                    "<td>" + o.role_name + "</td>" +
                    "<td><a href='/user/user_detail/" + o.uid + "'>查询详情</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }
</script>
