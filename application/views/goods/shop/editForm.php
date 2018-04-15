<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default"
       onclick="javascript:history.back(-1);method='get_size';" value="返回上一页">
<form action="/shop/shop_modify" class="am-form" id="doc-vld-msg" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>修改店铺</legend>
        <input type="hidden" value="<?= $id ?>" name="id">
        <div class="am-form-group">
            <label for="doc-vld-name">店铺名：</label>
            <input type="text" id="doc-vld-name" minlength="1"
                   placeholder="店铺名称" name="name"
                   value="<?= $name ?>"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner">负责人姓名：</label>
            <input type="text" id="doc-vld-owner" placeholder="姓名" name="owner"
                   class="am-form-field"
                   value="<?= $owner ?>"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner_mobile">负责人电话：</label>
            <input type="text" id="doc-vld-owner_mobile" placeholder="电话"
                   value="<?= $owner_mobile ?>"
                   name="owner_mobile"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-phone">座机：</label>
            <input type="text" id="doc-vld-phone" placeholder="座机" name="phone"
                   value="<?= $phone ?>"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-address">地址：</label>
            <input type="text" id="doc-vld-address" placeholder="地址" name="address"
                   value="<?= $address ?>"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-email">邮箱：</label>
            <input type="text" id="doc-vld-email" placeholder="邮箱" name="email"
                   value="<?= $email ?>"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-web_home">官网：</label>
            <input type="text" id="doc-vld-web_home" placeholder="官网" name="web_home"
                   value="<?= $web_home ?>"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-code_img">上传二维码：</label>
            <input type="file" id="doc-vld-code_img" name="code_img"
                   class="am-form-field" autocomplete="off" value="<?= $code_img ?>"/>
            <img src="<?= $code_img ?>" style="width: 100px">
        </div>

        <div class="am-form-group">
            <label for="doc-vld-bank_account">银行账号：</label>
            <input type="text" id="doc-vld-bank_account" name="bank_account"
                   class="am-form-field" autocomplete="off" value="<?= $bank_account ?>"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-bank_name">开户名：</label>
            <input type="text" id="doc-vld-bank_name" name="bank_name"
                   class="am-form-field" autocomplete="off" value="<?= $bank_name ?>"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-bank_deposit">开户行：</label>
            <input type="text" id="doc-vld-bank_deposit" name="bank_deposit"
                   class="am-form-field" autocomplete="off" value="<?= $bank_deposit ?>"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-alipay_account">支付宝账号：</label>
            <input type="text" id="doc-vld-alipay_account" name="alipay_account"
                   class="am-form-field" autocomplete="off" value="<?= $alipay_account ?>"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-alipay_name">支付宝户名：</label>
            <input type="text" id="doc-vld-alipay_name" name="alipay_name"
                   class="am-form-field" autocomplete="off" value="<?= $alipay_name ?>"/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">销售:</label>
            <select multiple name="seller_id[]" data-am-selected="{searchBox: 1,maxHeight:200}">
                <?php foreach ($seller_list as $s): ?>
                    <option value="<?= $s['uid'] ?>"
                            <?php if ($s['is_check']): ?>selected<?php endif; ?>><?= $s['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" value="" placeholder="备注" name="memo"
                   value="<?= $memo ?>"
                   class="am-form-field"/>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
