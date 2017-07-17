<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
    <input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
           value="返回上一页">
    <form action="/sku/add_sku" class="am-form" id="doc-vld-msg" method="post">
        <fieldset>
            <legend>添加商品</legend>
            <div class="am-form-group">
                <label for="doc-vld-name">商品名：</label>
                <input type="text" id="doc-vld-name" minlength="1" placeholder="输入" name="name" class="am-form-field"
                       required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-product_number">商品编号：</label>
                <input type="text" id="doc-vld-product_number" minlength="1" placeholder="输入" name="product_number"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-bar_code">条形码：</label>
                <input type="text" id="doc-vld-bar_code" minlength="1" placeholder="输入" name="bar_code"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-record_number">备案号：</label>
                <input type="text" id="doc-vld-record_number" minlength="1" placeholder="输入" name="record_number"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-brand_id">品牌：</label>
                <input type="text" id="doc-vld-brand_id" minlength="1" placeholder="输入" name="brand_id"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-category_id">商品分类：</label>
                <input type="text" id="doc-vld-category_id" minlength="1" placeholder="输入" name="category_id"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-property_id">属性：</label>
                <input type="text" id="doc-vld-property_id" minlength="1" placeholder="输入" name="property_id"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-price">价格：</label>
                <input type="text" id="doc-vld-price" minlength="1" placeholder="输入" name="price" class="am-form-field"
                       required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-source_area">生产地：</label>
                <input type="text" id="doc-vld-source_area" minlength="1" placeholder="输入" name="source_area"
                       class="am-form-field" required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-import">进口：</label>
                <input type="text" id="doc-vld-import" placeholder="输入" name="import" class="am-form-field"
                       required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-unit">单位：</label>
                <input type="text" id="doc-vld-unit" minlength="1" placeholder="输入" name="unit" class="am-form-field"
                       required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-weight">重量：</label>
                <input type="text" id="doc-vld-weight" minlength="1" placeholder="输入" name="weight"
                       class="am-form-field"
                       required/>
            </div>
            <div class="am-form-group">
                <label for="doc-vld-pic">小图：</label>
                <input type="text" id="doc-vld-pic" minlength="1" placeholder="输入" name="pic" class="am-form-field"
                       required/>
            </div>
            <!--            <div class="am-form-group">-->
            <!--                <label for="doc-vld-color_id">颜色：</label>-->
            <!--                <input type="text" id="doc-vld-color_id" minlength="1" placeholder="输入" name="color_id"-->
            <!--                       class="am-form-field" required/>-->
            <!--            </div>-->
            <!--            <div class="am-form-group">-->
            <!--                <label for="doc-vld-size_id">尺码：</label>-->
            <!--                <input type="text" id="doc-vld-size_id" minlength="1" placeholder="输入" name="size_id"-->
            <!--                       class="am-form-field"-->
            <!--                       required/>-->
            <!--            </div>-->
            <div class="am-form-group">
                <label for="doc-vld-memo">备注：</label>
                <input type="text" id="doc-vld-memo" minlength="1" placeholder="输入" name="memo" class="am-form-field"
                       required/>
            </div>


            <button class="am-btn am-btn-secondary" type="submit" id="submit">提交</button>
        </fieldset>
    </form>

<?php $this->load->view('footer'); ?>