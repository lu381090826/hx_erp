<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
       value="返回上一页">
<form action="/role/add_role" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加角色</legend>
        <div class="am-form-group">
            <label for="doc-vld-name">角色名：</label>
            <input type="text" id="doc-vld-name" minlength="2" placeholder="输入角色名" name="role_name"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <h3>勾选权限</h3>
            <ul class="am-list am-list-border am-list-striped">
                <?php foreach ($auth_list['result_rows'] as $key => $vale): ?>
                    <?php if ($vale['pid'] == 1): ?>
                        <li>
                            <div class="am-g">

                                <div class="am-u-sm-4">
                                    <?= $vale['auth_name']; ?>
                                </div>
                                <div class="am-u-sm-8">
                                    <label class="am-checkbox pauth">
                                        <input type="checkbox" class="parent_<?= $vale['id']; ?>" name="auth_ids[]"
                                               value="<?= $vale['id']; ?>"
                                               data-am-ucheck onclick="change(<?= $vale['id']; ?>)"> 所有权限
                                    </label>
                                    <?php foreach ($auth_list['result_rows'] as $k => $r): ?>
                                        <?php if ($r['pid'] == $vale['id']): ?>
                                            <label class="am-checkbox">
                                                <input type="checkbox" class="cauth child_<?= $vale['id']; ?>"
                                                       name="auth_ids[]"
                                                       value="<?= $r['id']; ?>"
                                                       data-am-ucheck
                                                       onclick="child_change(<?= $r['pid']; ?>,<?= $r['pid']; ?>)"
                                                > <?= $r['auth_name']; ?>
                                            </label>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
<script>
    //切换状态
    function change(id) {
        if ($('.parent_' + id).is(':checked')) {
            $('.child_' + id).uCheck('check')
        } else {
            $('.child_' + id).uCheck('uncheck')
        }
    }
    function child_change(pid, id) {
        if ($('.child_' + id).is(':checked')) {
            $('.parent_' + pid).uCheck('uncheck')
        }
    }
</script>
