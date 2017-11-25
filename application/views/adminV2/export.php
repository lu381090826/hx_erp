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
                            <form class="am-form" id="goods_search_form">

                                <div class="am-form-group">
                                    <label class="am-form-label">款号</label>
                                    <input type="text" id="search_goods_id" name="goods_id"/ >
                                </div>
                                <div class="am-form-group">
                                    <label class="am-form-label">品牌</label>
                                    <input type="text" id="search_brand" name="brand" / >
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
                                        -
                                        <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0">
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
                                        -
                                        <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0">
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
                                        -
                                        <div class="am-u-sm-5 am-padding-left-0 am-padding-right-0">
                                            <input type="text" name="stock_max">
                                        </div>
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-u-sm-12">
                                        <button type="button" class="am-btn am-btn-default"
                                                onclick="expotr()"><span class="am-icon-search"></span>导出
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
<script type="text/javascript" src="/assets/js/goodsjs.js"></script>