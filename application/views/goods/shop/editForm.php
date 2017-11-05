<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);method='get_size';" value="返回上一页">
<form action="/shop/shop_modify" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>修改店铺</legend>
        <input type="hidden" value="<?=$id?>" name="id">
        <div class="am-form-group">
            <label for="doc-vld-name">店铺名：</label>
            <input type="text" id="doc-vld-name" minlength="1"
                   placeholder="店铺名称" name="name"
                   value="<?=$name?>"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner">负责人姓名：</label>
            <input type="text" id="doc-vld-owner" placeholder="姓名" name="owner"
                   class="am-form-field"
                   value="<?=$owner?>"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner_mobile">负责人电话：</label>
            <input type="text" id="doc-vld-owner_mobile" placeholder="电话"
                   value="<?=$owner_mobile?>"
                   name="owner_mobile"
                   class="am-form-field"
            required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-phone">座机：</label>
            <input type="text" id="doc-vld-phone" placeholder="座机" name="phone"
                   value="<?=$phone?>"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-address">地址：</label>
            <input type="text" id="doc-vld-address" placeholder="地址" name="address"
                   value="<?=$address?>"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-email">邮箱：</label>
            <input type="text" id="doc-vld-email" placeholder="邮箱" name="email"
                   value="<?=$email?>"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-web_home">官网：</label>
            <input type="text" id="doc-vld-web_home" placeholder="官网" name="web_home"
                   value="<?=$web_home?>"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" value="" placeholder="备注" name="memo"
                   value="<?=$memo?>"
                   class="am-form-field"/>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
