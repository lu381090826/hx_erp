<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/user/add_users" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加角色</legend>
        <div class="am-form-group">
            <label for="doc-vld-name">角色名：</label>
            <input type="text" id="doc-vld-name" minlength="3" placeholder="输入角色名" name="name" class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <div class="am-u-sm-6">
                <h3>勾选权限</h3>
                <label class="am-checkbox">
                    <input type="checkbox" value="" data-am-ucheck> 没有选中
                </label>
            </div>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
<script>
    $(function () {
        $('#doc-vld-msg').validator({
            onValid: function (validity) {
                $(validity.field).closest('.am-form-group').find('.am-alert').hide();
            },

            onInValid: function (validity) {
                var $field = $(validity.field);
                var $group = $field.closest('.am-form-group');
                var $alert = $group.find('.am-alert');
                // 使用自定义的提示信息 或 插件内置的提示信息
                var msg = $field.data('validationMessage') || this.getValidationMessage(validity);
                if (validity.field.id == "doc-vld-mobile") {
                    msg = "请填写正确格式的手机号";
                }
                if (!$alert.length) {
                    $alert = $('<div class="am-alert am-alert-danger"></div>').hide().appendTo($group);
                }

                $alert.html(msg).show();
            }
        });
    });
</script>