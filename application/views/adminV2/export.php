<?php $this->load->view('adminV2/head'); ?>
<!-- 内容区域 -->
<div class="tpl-content-wrapper">
    <div class="row-content am-cf">
        <div class="row am-cf">
            <div class="am-u-sm-12">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">SKU导出</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-8 widget-margin-bottom-lg">
                        <!--正文-->
                        <form class="am-form" id="export_sku_form">

                            <div class="am-form-group">
                                <label class="am-form-label">款号</label>
                                <input type="text" id="search_goods_id" name="goods_id"/ >
                            </div>
                            <div class="am-form-group">
                                <label class="am-form-label">品牌</label>
                                <input type="text" id="search_brand" name="brand" / >
                            </div>


                            <div class="am-form-group">
                                <label class="am-form-label" for="search_status">状态</label>
                                <select id="search_status" name="status">
                                    <option value="">全部</option>
                                    <option value="1">上架中</option>
                                    <option value="2">下架中</option>
                                </select>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label" for="search_category_id">分类</label>
                                <select id="search_category_id" name="category_id">
                                    <option value="">全部</option>
                                    <?php foreach ($category_list as $key => $category): ?>
                                        <option value="<?= $key ?>"><?= $category ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label" for="search_color_id">颜色</label>
                                <div class="am-u-sm-12">
                                    <select id="search_color_id" name="color_id"
                                            data-am-selected="{searchBox: 1,maxHeight:200}">
                                        <option value="">全部</option>
                                        <?php foreach ($color_list as $row): ?>
                                            <option
                                                value="<?= $row['id'] ?>"><?= $row['color_num'] ?> <?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label" for="search_size_id">尺码</label>
                                <div class="am-u-sm-12">
                                    <select id="search_size_id" name="size"
                                            data-am-selected="{searchBox: 1,maxHeight:200}">
                                        <option value="">全部</option>
                                        <?php foreach ($size_list as $row): ?>
                                            <option
                                                value="<?= $row['id'] ?>"><?= $row['size_num'] ?> <?= $row['size_info'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label">成本范围</label>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-padding-left-0">
                                        <input type="text" name="cost_min">
                                    </div>
                                    <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0" style="float: left">
                                        <input type="text" name="cost_max">
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label">价格范围</label>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-padding-left-0">
                                        <input type="text" name="price_min">
                                    </div>
                                    <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0" style="float: left">
                                        <input type="text" name="price_max">
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-form-label">库存范围</label>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-padding-left-0">
                                        <input type="text" name="stock_min">
                                    </div>
                                    <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0" style="float: left">
                                        <input type="text" name="stock_max">
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label>性别：</label>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-2 am-padding-left-0">
                                        <label>
                                            <input type="radio" name="doc-vld-sex" value="" checked>
                                            全部
                                        </label>
                                    </div>
                                    <div class="am-u-sm-2 am-padding-left-0">
                                        <label>
                                            <input type="radio" name="doc-vld-sex" value="1">
                                            男
                                        </label>
                                    </div>
                                    <div class="am-u-sm-2 am-padding-left-0 am-padding-right-8" style="float: left">
                                        <label>
                                            <input type="radio" name="doc-vld-sex" value="2">
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
                                                    <?php if ($i == 2017): ?>selected<?php endif; ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </label>
                            </div>

                            <div class="am-form-group">
                                <label for="doc-vld-brand_id">月份：</label>
                                <label>
                                    <select data-am-selected="{maxHeight: 200}" name="month">
                                        <option value="">全部</option>
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
                                        <option value="">全部</option>
                                        <option value="1">春</option>
                                        <option value="2">夏</option>
                                        <option value="3">秋</option>
                                        <option value="4">冬</option>
                                    </select>
                                </label>
                            </div>

                            <div class="am-form-group">
                                <div class="am-u-sm-12">
                                    <button type="button" class="am-btn am-btn-default"
                                            onclick="expotr()"><span class="am-icon-expand"></span>导出
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('adminV2/foot'); ?>
<script>
    function getFormJson(frm) {
        var o = {};
        var a = $(frm).serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    }
    function expotr() {
        var form = $("<form>");//定义一个form表单
        form.attr("style", "display:none");
        form.attr("target", "");
        form.attr("method", "post");
        form.attr("action", "/sku/action_export");
        var data = getFormJson($('#export_sku_form'));
        $.each(data, function (i, o) {
            var input = $("<input>");
            input.attr("type", "hidden");
            input.attr("name", i);
            input.attr("value", o);
            form.append(input);
        });

        $("body").append(form);//将表单放置在web中
        form.submit();//表单提交
    }
</script>