<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" type="text/css" href="/assets/css/amazeui.page.css">
<br>
<!--功能选项-->
<select data-am-selected id="method_select" title="选择功能">
    <option value="user">用户管理</option>
    <option value="role" selected>角色管理</option>
    <!--    <option value="get_auths">权限管理</option>-->
</select>
<br>
<br>
<div>
    <button type="button" class="am-btn am-btn-success"
            onclick="window.location.href='/role/action_add_role'">添加角色
    </button>
</div>
<hr>
<!--表格子-->
<table class="am-table">
    <thead id="from_thead">
    <tr>
        <th>
            角色ID
        </th>
        <th>
            角色名
        </th>
        <th>
            备注
        </th>
        <th>
            创建时间
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($result_rows as $key => $vale): ?>
        <tr>
            <td>
                <?php echo $vale['id'] ?>
            </td>
            <td>
                <?php echo $vale['role_name'] ?>
            </td>
            <td>
                <?php echo $vale['memo'] ?>
            </td>
            <td>
                <?php echo $vale['create_time'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br>

<!--分页-->
<div id="page"></div>

<?php $this->load->view('footer'); ?>

<script type="text/javascript" src="/assets/js/amazeui.page.js"></script>
<script type="text/javascript" src="/assets/js/sys.js"></script>