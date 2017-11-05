<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);method='get_size';" value="返回上一页">
<form action="/shop/shop_add" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加店铺</legend>
        <div class="am-form-group">
            <label for="doc-vld-name">店铺名：</label>
            <input type="text" id="doc-vld-name" minlength="1" placeholder="店铺名称" name="name"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner">负责人姓名：</label>
            <input type="text" id="doc-vld-owner" placeholder="姓名" name="owner"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-owner_mobile">负责人电话：</label>
            <input type="text" id="doc-vld-owner_mobile" placeholder="电话" name="owner_mobile"
                   class="am-form-field"
            required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-phone">座机：</label>
            <input type="text" id="doc-vld-phone" placeholder="座机" name="phone"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-address">地址：</label>
            <input type="text" id="doc-vld-address" placeholder="地址" name="address"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-email">邮箱：</label>
            <input type="text" id="doc-vld-email" placeholder="邮箱" name="email"
                   class="am-form-field"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-web_home">官网：</label>
            <input type="text" id="doc-vld-web_home" placeholder="官网" name="web_home"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">销售:</label>
            <select multiple name="seller_id[]" data-am-selected="{searchBox: 1,maxHeight:200}">
                <?php foreach ($seller_list as $s):?>
                    <option value="<?=$s['uid']?>"><?=$s['name']?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" value="" placeholder="备注" name="memo"
                   class="am-form-field"/>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
