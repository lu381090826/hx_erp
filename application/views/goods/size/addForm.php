<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/size/add_size" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加尺码</legend>
        <div class="am-form-group">
            <label for="doc-vld-size-info">尺码信息：</label>
            <input type="text" id="doc-vld-size-info" minlength="1" placeholder="输入尺码信息" name="size_info"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-color-num">尺码号：</label>
            <input type="text" id="doc-vld-color-num" minlength="1" placeholder="输入尺码号" name="size_num"
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
