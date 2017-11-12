<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
       value="返回上一页">
<form action="/sku/add_sku" class="am-form" id="doc-vld-msg" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>添加商品</legend>
        <div class="am-form-group">
            <label for="doc-vld-goods_id">款号<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-goods_id" minlength="1" placeholder="输入" name="goods_id"
                   class="am-form-field"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">品牌：</label>
            <input type="text" id="doc-vld-brand_id" placeholder="输入" name="brand"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">分类:</label>
            <select id="doc-select-1" name="category_id">
                <?php foreach ($category_list as $k => $category): ?>
                    <option value="<?= $k ?>"><?= $category ?></option>
                <?php endforeach; ?>
            </select>
            <span class="am-form-caret"></span>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-price">价格（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-price" minlength="1" placeholder="输入" name="price" class="am-form-field"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-cost">成本（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-cost" minlength="1" placeholder="输入" name="cost" class="am-form-field"
                   required/>
        </div>

        <div class="am-form-group">
            <label>店铺：</label>
            <select id="shop-select" multiple data-am-selected="{searchBox: 1,maxHeight: 200}" name="shop_id[]">
                <?php foreach ($shop_list as $shop): ?>
                    <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div id="shop-select-info"></div>
        </div>

        <div class="am-form-group">
            <label>颜色：</label>
            <select id="color-select" multiple data-am-selected="{searchBox: 1,maxHeight: 200}" name="color[]">
                <?php foreach ($color_list as $row): ?>
                    <option data="<?= $row['color_code'] ?>" value="<?= $row['id'] ?>">
                        <?= $row['name'] ?> <?= $row['color_num'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="color-select-info" style="border: black"></div>
        </div>

        <div class="am-form-group">
            <label>尺码：</label>
            <select id="size-select" multiple data-am-selected="{searchBox: 1,maxHeight: 200}" name="size[]">
                <?php foreach ($size_list as $row): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['size_info'] ?> <?= $row['size_num'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="size-select-info"></div>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-pic">上传小图：</label>
            <input type="file" id="doc-vld-pic" name="pic" class="am-btn am-btn-default am-btn-sm"/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" placeholder="输入" name="memo" class="am-form-field"/>
        </div>
        <button class="am-btn am-btn-secondary" id="submit" type="submit">提交</button>
        <input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
               value="返回">
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
<script>
    $(function () {
        var $color_selected = $('#color-select');

        $color_selected.on('change', function () {
            var append_color_text = "";
            $(this).find("option:selected").each(function (i, o) {
                var color_code = $(this).attr("data");
                var color_name = $(this).text();
                append_color_text += "<div><span style='color:#" + color_code + ";background: #" + color_code + "'>ccc</span>"
                    + color_name
                    + "</div>"
            });
            if (append_color_text != "") {
                $('#color-select-info').html("已选：<br>" + append_color_text);
            }
        });

        var $size_selected = $('#size-select');

        $size_selected.on('change', function () {
            var append_size_text = "";
            $(this).find("option:selected").each(function (i, o) {
                append_size_text += "<div>" + $(this).text() + " </div>";
            });
            if (append_size_text != "") {
                $('#size-select-info').html("已选：<br>" + append_size_text);
            }
        });

        var $shop_selected = $('#shop-select');

        $shop_selected.on('change', function () {
            var append_shop_text = "";
            $(this).find("option:selected").each(function (i, o) {
                append_shop_text += "<div>" + $(this).text()+  "</div>";
            });
            if (append_shop_text != "") {
                $('#shop-select-info').html("已选：<br>" + append_shop_text);
            }
        });
    });
</script>