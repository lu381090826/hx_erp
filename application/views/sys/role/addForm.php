<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/role/add_role" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加角色</legend>
        <div class="am-form-group">
            <label for="doc-vld-name">角色名：</label>
            <input type="text" id="doc-vld-name" minlength="3" placeholder="输入角色名" name="role_name" class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
                <h3>勾选权限</h3>
                <?php if (isset($auth_list['result_rows']))
                    foreach ($auth_list['result_rows'] as $r) { ?>
                        <label class="am-checkbox">
                            <input type="checkbox" name="auth_ids[]" value="<?= $r['id']; ?>" data-am-ucheck> <?= $r['auth_name']; ?>
                        </label>
                    <?php } ?>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>