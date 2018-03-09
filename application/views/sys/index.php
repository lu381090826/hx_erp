<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" type="text/css" href="/assets/css/amazeui.page.css">
<br>

<!--功能选项-->
<select data-am-selected id="method_select" title="选择功能">
    <option value="user" selected>用户管理</option>
    <option value="role">角色管理</option>
    <option value="auth">权限管理</option>
    <!--    <option value="get_auths">权限管理</option>-->
</select>
<br>
<br>
<div>
    <button type="button" class="am-btn am-btn-success"
            onclick="window.location.href='/user/action_add_user'">添加用户
    </button>
    <button type="button" class="am-btn am-btn-primary"
            onclick="window.location.href='/user/action_add_user_from_dingtalk'">从钉钉通信录添加用户
    </button>
</div>
<hr>
<!--表格子-->
<table class="am-table am-table-compact am-text-nowrap">
    <thead id="from_thead">
    <tr>
        <th>
            用户名
        </th>
        <th>
            角色
        </th>
        <th>
            手机号
        </th>
        <th>
            邮箱
        </th>
        <th>
            操作
        </th>
    </tr>
    </thead>
    <tbody id="from_contant">
    <?php foreach ($result_rows as $key => $vale): ?>
        <tr>
            <td>
                <?php echo $vale['name'] ?>
            </td>
            <td>
                <?php echo $vale['role_name'] ?>
            </td>
            <td>
                <?php echo $vale['mobile'] ?>
            </td>
            <td>
                <?php echo $vale['email'] ?>
            </td>
            <td>
                <a href="/user/user_detail/<?php echo $vale['uid'] ?>">修改</a>
                <?php if (!empty($vale['status'])): ?>
                    <a href="#" onclick="remove_user(<?php echo $vale['uid'] ?>)">禁用</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br>
<ul class="am-pagination">
    <?= $pagination ?>
</ul>
<div class="am-modal am-modal-confirm" tabindex="-1" id="user-remove-confirm">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示</div>
        <div class="am-modal-bd">
            确定要禁用这个用户吗（将无法登录）？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
<?php $this->load->view('footer'); ?>

<script type="text/javascript" src="/assets/js/sys.js"></script>
<script type="text/javascript">
    var delete_id = 0;
    function remove_user(id) {
        delete_id = id;
//        $('#user-remove-confirm').modal('close');
        $('#user-remove-confirm').modal({
            relatedTarget: this,
            onConfirm: function (options) {
                $.post('/user/delete_user/' + delete_id);
                setTimeout(function () {
                    location.reload();
                }, 300);
            }
        });
    }
</script>