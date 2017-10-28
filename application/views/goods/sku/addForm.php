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
            <input type="text" id="doc-vld-goods_id" minlength="1" placeholder="输入" name="goods_id" class="am-form-field"
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
                <?php foreach ($category_list as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
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
            <label>颜色：</label>
            <?php foreach ($color_list as $row): ?>
                <div class="am-checkbox">
                    <label>
                        <input type="checkbox" name="color[]" value="<?= $row['id'] ?>"> <span
                            style="color: #<?= $row['color_code'] ?>;background: #<?= $row['color_code'] ?>;">
                    c</span><?= $row['name'] ?>-<?= $row['color_num'] ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="am-form-group">
            <label>尺码：</label>
            <?php foreach ($size_list as $row): ?>
                <div class="am-checkbox">
                    <label>
                        <input type="checkbox" name="size[]" value="<?= $row['id'] ?>"> <?= $row['size_info'] ?>
                        -<?= $row['size_num'] ?>
                    </label>
                </div>
            <?php endforeach; ?>
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
