<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="编辑">
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="删除">
<table class="am-table">

    <thead>
    </thead>

    <tbody>
    <tr>
        <td>id</td>
        <td><?= $id ?></td>
    </tr>
    <tr>
        <td>角色名</td>
        <td><?= $role_name ?></td>
    </tr>
    <tr>
        <td>权限</td>
        <td>
            <?php if (!empty($auths)) {
                foreach ($auths as $row) {
                    ?>
                    <label class="am-checkbox">
                        <input type="checkbox" checked="checked" value="<?= $row['id'] ?>" data-am-ucheck disabled
                               checked>
                        <?= $row['auth_name'] ?>
                    </label>
                    <?php
                }
            } ?>
        </td>
    </tr>
    <tr>
        <td>创建时间</td>
        <td><?= $create_time ?></td>
    </tr>
    <tr>
        <td>修改时间</td>
        <td><?= $modify_time ?></td>
    </tr>

    </tbody>
</table>

<?php
$this->load->view('footer');
?>
