<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
       value="返回上一页">
<form action="/sku/add_sku" autocomplete="off" class="am-form" id="doc-vld-msg" method="post"
      enctype="multipart/form-data">
    <fieldset>
        <legend>添加商品</legend>
        <div class="am-form-group">
            <label for="doc-vld-goods_id">款号<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-goods_id" minlength="1" placeholder="输入" name="goods_id"
                   class="am-form-field" autocomplete="off"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">分类<span style="color: red">*</span>:</label>
            <select id="doc-select-1" name="category_id">
                <?php foreach ($category_list as $k => $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['type'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="am-form-caret"></span>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-price">价格（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-price" minlength="1" placeholder="输入" name="price"
                   class="am-form-field" autocomplete="off"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-cost">成本（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-cost" minlength="1" placeholder="输入" name="cost"
                   class="am-form-field" autocomplete="off"
                   required/>
        </div>


        <div class="am-form-group">
            <label for="doc-vld-brand_id">品牌：</label>
            <input type="text" id="doc-vld-brand_id" placeholder="输入" name="brand"
                   class="am-form-field" autocomplete="off"/>
        </div>

        <div class="am-form-group">
            <label>店铺：</label>
            <select id="shop-select" multiple data-am-selected="{searchBox: 1,maxHeight: 200}" name="shop_id[]">
                <option></option>
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
            <div id="color-select-info"></div>
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
            <label>性别：</label>
            <div class="am-u-sm-12">
                <div class="am-u-sm-2 am-padding-left-0">
                    <label>
                        <input type="radio" name="sex" value="1" checked>
                        男
                    </label>
                </div>
                <div class="am-u-sm-2 am-padding-left-0 am-padding-right-8" style="float: left">
                    <label>
                        <input type="radio" name="sex" value="2">
                        女
                    </label>
                </div>
            </div>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">年份： </label>

            <label>
                <select data-am-selected="{maxHeight: 200,searchBox: 1}" name="year">
                    <?php for ($i = 2000; $i < 2030; $i++): ?>
                        <option value="<?= $i ?>" <?php if ($i == 2017): ?>selected<?php endif; ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">月份：</label>
            <label>
                <select data-am-selected="{maxHeight: 200}" name="month">
                    <?php for ($i = 1; $i < 13; $i++): ?>
                        <option value="<?= $i ?>"
                                <?php if ($i == date('m')): ?>selected<?php endif; ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">季节：</label>
            <label>
                <select data-am-selected="{maxHeight: 200}" name="season">
                    <option value="1">春</option>
                    <option value="2">夏</option>
                    <option value="3">秋</option>
                    <option value="4">冬</option>
                </select>
            </label>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-pic">上传小图：</label>
            <input type="file" id="doc-vld-pic" name="pic" class="am-btn am-btn-default am-btn-sm" autocomplete="off"/>
        </div>

        <div class="am-form-group" style="display: none">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h3 class="am-panel-title">上传多图：</h3>
                </div>
                <div class="am-panel-bd">
                    <label>选择上传的图片</label>
                    <div class="file_list">
                        <div class="am-g am-g-fixed">
                            <input type="file" id="doc-vld-pic" name="file_list[]"
                                   class="am-btn am-btn-default am-btn-sm am-u-sm-10"/>
                            <input type="button"
                                   class="am-btn am-btn-danger am-btn-sm sub_upload_img"
                                   value="-">
                        </div>
                    </div>
                    <input type="button" class="am-btn am-btn-success" autocomplete="off" onclick="add_upload_img()" value="+">
                </div>

            </div>

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
<script src="/assets/js/select.js"></script>

<!--<script type="text/javascript">-->
<!--    function add_upload_img() {-->
<!--        $('.file_list').append('<div class="am-g am-g-fixed"> <input type="file" id="doc-vld-pic" name="file_list[]" class="am-btn am-btn-default am-btn-sm am-u-sm-10"/> <input type="button" class="am-btn am-btn-danger am-btn-sm sub_upload_img" value="-"> </div>');-->
<!--    }-->
<!---->
<!--    $('.file_list').delegate('.sub_upload_img','click',function(){　　　　//动态事件绑定  为页面所有的dd添加一个事件 包括新增的节点-->
<!--    console.log($(this))-->
<!--        $(this).parent().remove();-->
<!--    });-->
<!--</script>-->