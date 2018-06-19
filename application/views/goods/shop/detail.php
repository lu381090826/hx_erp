<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<input type="button" name="Submit" class="am-btn am-btn-default"
       onclick="location.href='/shop/shop_detail_edit/<?= $id ?>'" value="编辑">
<!--<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="删除">-->
<form class="am-form">
    <fieldset>
        <legend>店铺名 <strong><?= $name ?></strong></legend>

        <div class="am-form-group">
            <label>店铺id</label>
            <?= $id ?>
        </div>
        <div class="am-form-group">
            <label>负责人</label>
            <?= $owner ?>
        </div>
        <div class="am-form-group">
            <label>负责人电话</label>
            <?= $owner_mobile ?>
        </div>
        <div class="am-form-group">
            <label>座机</label>
            <?= $phone ?>
        </div>
        <div class="am-form-group">
            <label>地址</label>
            <?= $address ?>
        </div>
        <div class="am-form-group">
            <label>邮箱</label>
            <?= $email ?>
        </div>
        <div class="am-form-group">
            <label>官网</label>
            <?= $web_home ?>
        </div>
        <div class="am-form-group">
            <label>官网</label>
            <?= $web_home ?>
        </div>
        <div class="am-form-group">
            <label>银行账号</label>
            <?= $bank_account ?>
        </div>
        <div class="am-form-group">
            <label>银行开户名</label>
            <?= $bank_name ?>
        </div>
        <div class="am-form-group">
            <label>银行开户行</label>
            <?= $bank_deposit ?>
        </div>
        <div class="am-form-group">
            <label>银行账号_2</label>
            <?= $bank_account_2 ?>
        </div>
        <div class="am-form-group">
            <label>银行开户名_2</label>
            <?= $bank_name_2 ?>
        </div>
        <div class="am-form-group">
            <label>银行开户行_2</label>
            <?= $bank_deposit_2 ?>
        </div>
        <div class="am-form-group">
            <label>支付宝账号</label>
            <?= $alipay_account ?>
        </div>
        <div class="am-form-group">
            <label>支付宝户名</label>
            <?= $alipay_name ?>
        </div>
        <div class="am-form-group">
            <label>支付宝二维码图片</label>
            <img src="<?= $alipay_code_img ?>" style="width:100px">
        </div>
        <div class="am-form-group">
            <label>微信账号</label>
            <?= $wx_account ?>
        </div>
        <div class="am-form-group">
            <label>微信户名</label>
            <?= $wx_name ?>
        </div>
        <div class="am-form-group">
            <label>微信二维码图片</label>
            <img src="<?= $wx_code_img ?>" style="width:100px">
        </div>
        <div class="am-form-group">
            <label>备注</label>
            <?= $memo ?>
        </div>

        <div class="am-form-group">
            <label>操作人</label>
            <?= $operator ?>
        </div>

        <div class="am-form-group">
            <label>添加时间</label>
            <?= $create_time ?>
        </div>
    </fieldset>
</form>

<?php
$this->load->view('footer');
?>
