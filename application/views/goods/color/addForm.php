<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" href="/assets/css/Utils/colpick.css" type="text/css"/>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/color/add_color" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加颜色</legend>
        <div class="am-form-group">
            <label for="doc-vld-color-num">请选择颜色：</label>
            <input type="text" id="picker" name="color_code"/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-name">颜色名：</label>
            <input type="text" id="doc-vld-name" minlength="1" placeholder="输入颜色名，如：酒红色" name="name"
                   class="am-form-field"
                   required/>
        </div>
        <div class="am-form-group">
            <label for="doc-vld-color-num">颜色代码：</label>
            <input type="text" id="doc-vld-color-num" minlength="1" placeholder="输入颜色号码" name="color_num"
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
<script src="/assets/js/common/colorUtils/colpick.js" type="text/javascript"></script>

<script>
    $('#picker').colpick({

        layout: 'hex',

        submit: 0,

        colorScheme: 'hex',

        onChange: function (hsb, hex, rgb, el, bySetColor) {

            $(el).css('border-color', '#' + hex);

            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.

            if (!bySetColor) $(el).val(hex);

        }

    }).keyup(function () {

        $(this).colpickSetColor(this.value);

    });
</script>
