<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<br>
<!--功能选项-->
<select data-am-selected id="method_select" title="选择功能">
    <option value="get_sku">商品设置</option>
    <option value="get_color">颜色设置</option>
    <option value="get_size">尺码设置</option>
</select>
<br>
<br>
<button type="button" class="am-btn am-btn-success other-select div-get_sku"
        onclick="window.location.href='/sku/action_add_sku'">新建商品
</button>
<button type="button" class="am-btn am-btn-success other-select div-get_color"
        onclick="window.location.href='/color/action_add_color'">添加颜色
</button>
<button type="button" class="am-btn am-btn-success other-select div-get_size"
        onclick="window.location.href='/size/action_add_size'">添加尺码
</button>

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

    function get_sku(curr) {
        var content = "<tr> <th>小图</th> <th>商品名</th> <th>商品号</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
                    "<td><img style='width: 50px' src='" + o.pic + "'></td>" +
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.product_number + "</td>" +
                    "<td><a href='/suk/sku_detail/" + o.id + "'>查询详情</a></td>" +
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
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.mobile + "</td>" +
                    "<td>" + o.role_name + "</td>" +
                    "<td><a href='/user/user_detail/" + o.uid + "'>查询详情</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }
    function get_color(curr) {
        var content = "<tr> <th>颜色</th> <th>颜色代码</th> <th>颜色展示</th>  <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr, function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
                    "<td>" + o.name + "</td>" +
                    "<td>" + o.color_num + "</td>" +
                    "<td><span class='am-badge'" + "style='background: #" + o.color_code + "'>s</span></td>" +
                    "<td><a href='/color/delete_color/" + o.id + "'>删除</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }
    function get_size(curr) {
        var content = "<tr> <th>尺码</th> <th>尺码代码</th>  <th>操作</th> </tr>";
        from_thead.append(content);

        $.get(getContentUrl() + curr + '?v=' + Math.random(), function (result) {
            $.each(result.result_rows, function (i, o) {
                content = "<tr>" +
                    "<td>" + o.size_info + "</td>" +
                    "<td>" + o.size_num + "</td>" +
                    "<td><a href='/size/delete_size/" + o.id + "'>删除</a></td>" +
                    "</tr>";
                from_contant.append(content)
            });
        }, 'JSON');
    }
</script>
