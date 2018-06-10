<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>

<input type="button" name="Submit" autocomplete="off" class="am-btn am-btn-default"
       onclick="javascript:history.back(-1);"
       value="返回上一页">
<form action="/sku/add_sku" class="am-form" id="doc-vld-msg" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>修改商品</legend>
        <div class="am-form-group">
            <label for="doc-vld-goods_id">款号<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-goods_id" minlength="1" placeholder="输入"
                   name="goods_id" readonly="readonly" class="am-form-field"
                   value="<?= $goods_info['goods_id'] ?>"
            />
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">品牌：</label>
            <input type="text" id="doc-vld-brand_id" placeholder="输入"
                   name="brand"
                   value="<?= $goods_info['brand'] ?>"
                   class="am-form-field"/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">分类:</label>
            <select id="doc-select-1" name="category_id">
                <?php foreach ($category_list as $k => $category): ?>
                    <option value="<?= $category['id'] ?>"
                        <?php if ($goods_info['category_id'] == $category['id']): ?>
                            selected="selected"
                        <?php endif; ?>>
                        <?= $category['type'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="am-form-caret"></span>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-price">价格（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-price" minlength="1" placeholder="输入"
                   name="price" class="am-form-field"
                   value="<?= $goods_info['price'] ?>"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-cost">成本（单位元）<span style="color: red">*</span>：</label>
            <input type="text" id="doc-vld-cost" minlength="1" placeholder="输入"
                   name="cost" class="am-form-field"
                   value="<?= $goods_info['cost'] ?>"
                   required/>
        </div>

        <div class="am-form-group">
            <label for="doc-select-1">店铺：</label>
            <select id="shop-select" multiple data-am-selected="{searchBox: 1,maxHeight:200}" name="shop_id[]">
                <?php foreach ($shop_list as $shop): ?>
                    <option value="<?= $shop['id'] ?>"
                            <?php if ($shop['is_check']): ?>selected<?php endif; ?>><?= $shop['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div id="shop-select-info"></div>
        </div>

        <div class="am-form-group">
            <label>颜色：</label>
            <select id="color-select" multiple data-am-selected="{searchBox: 1,maxHeight: 200}" name="color[]">
                <?php foreach ($color_list as $row): ?>
                    <option data="<?= $row['color_code'] ?>"
                        <?php if (!empty($row['is_select'])): ?>
                            selected
                        <?php endif; ?>
                            value="<?= $row['id'] ?>">
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
                    <option value="<?= $row['id'] ?>"
                        <?php if (!empty($row['is_select'])): ?>
                            selected
                        <?php endif; ?>>
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
                        <input type="radio" name="doc-vld-sex" value="1"
                               <?php if ($goods_info['sex'] == 1): ?>checked<?php endif; ?>>
                        男
                    </label>
                </div>
                <div class="am-u-sm-2 am-padding-left-0 am-padding-right-8" style="float: left">
                    <label>
                        <input type="radio" name="doc-vld-sex" value="2"
                               <?php if ($goods_info['sex'] == 2): ?>checked<?php endif; ?>>
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
                        <option value="<?= $i ?>"
                                <?php if ($i == $goods_info['year']): ?>selected<?php endif; ?>><?= $i ?></option>
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
                                <?php if ($i == $goods_info['month']): ?>selected<?php endif; ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-brand_id">季节：</label>
            <label>
                <select data-am-selected="{maxHeight: 200}" name="season">
                    <option value="1" <?php if (1 == $goods_info['season']): ?>selected<?php endif; ?>>春</option>
                    <option value="2" <?php if (2 == $goods_info['season']): ?>selected<?php endif; ?>>夏</option>
                    <option value="3" <?php if (3 == $goods_info['season']): ?>selected<?php endif; ?>>秋</option>
                    <option value="4" <?php if (4 == $goods_info['season']): ?>selected<?php endif; ?>>冬</option>
                </select>
            </label>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-pic">上传小图：</label>
            <input type="file" id="doc-vld-pic" name="pic" class="am-btn am-btn-default am-btn-sm"/>
            <?php if (!empty($goods_info['pic'])): ?><img style="width: 100px;" src="<?= $goods_info['pic'] ?>"><?php endif; ?>
        </div>

        <div class="am-form-group">
            <label for="doc-vld-memo">备注：</label>
            <input type="text" id="doc-vld-memo" value="<?= $goods_info['memo'] ?>" placeholder="输入" name="memo"
                   class="am-form-field"/>
        </div>
        <button class="am-btn am-btn-secondary" id="submit" type="submit">提交</button>
        <input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
               value="返回">
    </fieldset>
</form>

<?php $this->load->view('footer'); ?>
<script src="/assets/js/select.js"></script>