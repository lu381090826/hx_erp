<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" href="/assets/css/Utils/colpick.css" type="text/css"/>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<form action="/category/add_category" class="am-form" id="doc-vld-msg" method="post">
    <fieldset>
        <legend>添加</legend>

        <div class="am-form-group">
            <label for="doc-vld-category-name">类名：</label>
            <input type="text" id="doc-vld-category-name" minlength="1" value="" placeholder="输入类名"
                   name="category_name"
                   class="am-form-field"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">父类</label>
            <select id="doc-select-1" name="pid">
                <option value="0">无</option>
                <?php foreach ($category_list as $k => $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['type'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="am-form-caret"></span>
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

<script>
    $('#picker').colpick({

        layout: 'hex',

        submit: 0,

        categoryScheme: 'hex',

        onChange: function (hsb, hex, rgb, el, bySetColor) {

            $(el).css('border-category', '#' + hex);

            // Fill the text box just if the category was set using the picker, and not the colpickSetColor function.

            if (!bySetColor) $(el).val(hex);

        }

    }).keyup(function () {

        $(this).colpickSetColor(this.value);

    });
</script>
