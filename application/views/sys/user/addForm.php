<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/user/add_users" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加用户</legend>
        <div class="am-form-group">
            <label for="doc-vld-name">姓名：</label>
            <input type="text" id="doc-vld-name" minlength="2" placeholder="输入姓名" name="name" class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-mobile">手机号：</label>
            <input type="text" id="doc-vld-mobile" name="mobile" minlength="3" placeholder="输入手机号"
                   class="am-form-field"
                   pattern="^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-password">密码：</label>
            <input type="text" id="doc-vld-password" minlength="6" name="password" placeholder="输入密码"
                   class="am-form-field" required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-emai">邮箱：</label>
            <input type="email" id="doc-vld-emai" name="email" placeholder="输入邮箱" class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <div class="am-form-group">
                <label for="doc-select-1">角色</label>
                <select id="doc-select-1" name="role_id" required>
                    <?php foreach ((array)$role_list as $r) { ?>
                        <option value="<?=$r['id'];?>"><?=$r['role_name'];?></option>
                    <?php } ?>
                </select>
                <span class="am-form-caret"></span>
            </div>
        </div>

        <div class="am-form-group">
            <div class="am-form-group">
                <label for="doc-select-1">店铺</label>
                <select id="doc-select-1" name="shop_id" data-am-selected="{searchBox: 1,maxHeight:200}">
                    <option></option>
                    <?php foreach ((array)$shop_list as $r) { ?>
                        <option value="<?=$r['id'];?>"><?=$r['name'];?></option>
                    <?php } ?>
                </select>
                <span class="am-form-caret"></span>
            </div>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
<script>
    //校验逻辑
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