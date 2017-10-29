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
            <input type="text" id="doc-vld-name" minlength="1" placeholder="输入店铺名称" name="name"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" minlength="1" value="无" placeholder="输入备注" name="memo"
                   class="am-form-field"
                   required/>
        </div>

        <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
