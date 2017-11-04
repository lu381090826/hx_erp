<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<br>
<style>
    .am-table > tbody > tr > td {
        vertical-align: middle;
    }

    .pic {
        width: 80px;
        height: 100px;
    }

    .am-btn-group {
        vertical-align: baseline;
    }
</style>
<!--功能选项-->
<select data-am-selected id="method_select" class="am-u-sm-3" title="选择功能">
    <option value="get_goods">商品管理</option>
    <option value="get_category">分类管理</option>
    <option value="get_color">颜色管理</option>
    <option value="get_size">尺码管理</option>
    <option value="get_shop">店铺管理</option>
</select>
<div class="am-btn-group am-btn-group-xs">
    <div class="other-select" id="div-get_goods">
        <button type="button" class="am-btn am-btn-default"
                onclick="window.location.href='/sku/action_add_sku'"><span class="am-icon-plus"></span>新建商品
        </button>
        <button type="button" class="am-btn am-btn-default"
                data-am-collapse="{parent: '#accordion', target: '#goods_search'}"><span class="am-icon-search"></span>
        </button>
        <div class="am-panel-group" id="accordion">
            <div class="am-collapse" id="goods_search">
                <hr>
                <form class="am-form" id="goods_search_form">

                    <div class="am-form-group">
                        <label class="am-u-sm-3 am-form-label">款号</label>
                        <input type="text" id="search_goods_id" name="goods_id"/ >
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-sm-3 am-form-label">品牌</label>
                        <input type="text" id="search_brand" name="brand" / >
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-sm-3 am-form-label">分类</label>
                        <select id="search_category_id" name="category_id">
                            <option value="">全部</option>
                            <?php foreach ($category_list as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="am-form-group">
                        <label class="am-form-label"> 成本范围</label>
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
                        <label class="am-form-label"> 价格范围</label>
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
                        <label class="am-form-label"> 库存范围</label>
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
                                    onclick="search_goods()"><span class="am-icon-search"></span>搜索
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <div class=" other-select" id="div-get_category">
        <button type="button" class="am-btn am-btn-default"
                onclick="window.location.href='/category/action_add_category'"><span class="am-icon-plus"></span>添加分类
        </button>
    </div>
    <div class="other-select" id="div-get_color">
        <button type="button" class="am-btn am-btn-default"
                onclick="window.location.href='/color/action_add_color'"><span class="am-icon-plus"></span>添加颜色
        </button>
    </div>
    <div class="other-select" id="div-get_size">
        <button type="button" class="am-btn am-btn-default"
                onclick="window.location.href='/size/action_add_size'"><span class="am-icon-plus"></span>添加尺码
        </button>
    </div>
    <div class="other-select" id="div-get_shop">
        <button type="button" class="am-btn am-btn-default"
                onclick="window.location.href='/shop/action_add_shop'"><span class="am-icon-plus"></span>添加店铺
        </button>
    </div>
</div>
<!--表格子-->
<table class="am-table" id="from_table">
    <thead id="from_thead" class="am-text-nowrap">
    </thead>
    <tbody id="from_contant">
    </tbody>
</table>
<br>

<!--分页-->
<div id="page">
</div>


<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示</div>
        <div class="am-modal-bd">
            确定要删除这条记录吗？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
<?php $this->load->view('footer'); ?>
<script type="text/javascript" src="/assets/js/amazeui.page.js"></script>
<script type="text/javascript" src="/assets/js/common/from.js"></script>
<script>

    $(document).ready(function () {
        fromLoad('goods');
    });

    function search_goods() {
        api_result = null;
        get_goods(1);
    }


    function get_goods(curr) {
        tableClean();
        var fromThead = "<tr style='text-align: center'> <th style='width: 80px'>小图</th> <th>款号</th><th>价格</th><th>库存</th><th>发布时间</th> <th style='text-align: center;width: 80px' class='am-text-nowrap'>操作</th> </tr>";
        from_thead.append(fromThead);

        $.ajax(
            {
                async: false,
                url: getContentUrl() + curr,
                type: 'post',
                data: getFormJson($('#goods_search_form')),
                success: function (result) {
                    curr_page = curr;
                    all_pages = result.pages;
                    from_contant.append(goods_show(result));
                }
            })
    }

    function goods_show(result) {
        var goods_content = "";
        $.each(result.result_rows, function (i, o) {
            goods_content += "<tr>" +
                "<td><img class='pic' src='" + o.pic + "'></td>" +
                "<td>" + o.goods_id + "</td>" +
                "<td>¥" + o.price + "</td>" +
                "<td>" + 0 + "</td>" +
                "<td>" + o.create_time + "</td>" +
                "<td align='center' valign='middle' style='word-break:break-all'>" +
                "<div><a href='/goods/goods_detail/" + o.goods_id + "'>详情</a><div>" +
                "<div><a onclick=\"sku_delete('" + o.goods_id + "')\">删除</a></button></div>" +
                "</td>" +
                "</tr>";
        });
        return goods_content;
    }

    function get_category(curr) {
        tableClean();

        var content = "<tr> <th>id</th> <th>类别</th> <th>操作</th> </tr>";
        from_thead.append(content);

        $.ajax({
            type: 'get',
            async: false,
            url: getContentUrl() + curr,
            dateType: 'json',
            success: function (result) {
                var row = '';
                curr_page = curr;
                all_pages = result.pages;
                $.each(result.result_rows, function (i, o) {
                    row += "<tr>" +
                        "<td>" + o.id + "</td>" +
                        "<td>" + o.category_name + "</td>" +
                        "<td><a href='/category/delete_category/" + o.id + "'>删除</a></td>" +
                        "</tr>";
                });
                from_contant.append(row);
            }
        })
    }
    function get_color(curr) {
        tableClean();
        var content = "<tr> <th>颜色</th> <th>颜色代码</th> <th>颜色展示</th>  <th>操作</th> </tr>";
        from_thead.append(content);

        $.ajax({
            async: false,
            url: getContentUrl() + curr,
            type: 'get',
            dateType: 'json',
            success: function (result) {
                var row = '';
                $.each(result.result_rows, function (i, o) {
                    row += "<tr>" +
                        "<td>" + o.name + "</td>" +
                        "<td>" + o.color_num + "</td>" +
                        "<td><span class='am-badge'" + "style='background: #" + o.color_code + ";color: #" + o.color_code + "'>c</span></td>" +
                        "<td><a href='/color/delete_color/" + o.id + "'>删除</a></td>" +
                        "</tr>";
                });
                from_contant.append(row);
                curr_page = curr;
                all_pages = result.pages;
            }
        })

    }

    function get_size(curr) {
        tableClean();

        var content = "<tr> <th>尺码</th> <th>尺码代码</th>  <th>操作</th> </tr>";
        from_thead.append(content);

        $.ajax({
            async: false,
            url: getContentUrl() + curr,
            dateType: false,
            type: 'get',
            success: function (result) {
                var row = '';
                $.each(result.result_rows, function (i, o) {
                    row += "<tr>" +
                        "<td>" + o.size_info + "</td>" +
                        "<td>" + o.size_num + "</td>" +
                        "<td><a href='/size/delete_size/" + o.id + "'>删除</a></td>" +
                        "</tr>";
                });
                from_contant.append(row);
                curr_page = curr;
                all_pages = result.pages;
            }
        })
    }

    function get_shop(curr) {
        tableClean();

        var content = "<tr> <th>Id</th> <th>店名</th><th>负责人</th><th>负责电话</th><th>创建时间</th> <th style='text-align: center;width: 80px' class='am-text-nowrap'>操作</th> </tr>";
        from_thead.append(content);

        $.ajax({
            type: 'get',
            async: false,
            dateType: 'json',
            url: getContentUrl() + curr,
            success: function (result) {
                var row = '';
                $.each(result.result_rows, function (i, o) {
                    row += "<tr>" +
                        "<td>" + o.id + "</td>" +
                        "<td>" + o.name + "</td>" +
                        "<td>" + o.owner + "</td>" +
                        "<td>" + o.owner_mobile + "</td>" +
                        "<td>" + o.create_time + "</td>" +
                        "<td align='center' valign='middle' style='word-break:break-all'>" +
                        "<div><a href='/shop/shop_detail/" + o.id + "'>详情</a><div>" +
                        "<div><a onclick=\"shop_delete('" + o.id + "')\">删除</a></button></div>" +
                        "</td>" +
                        "</tr>";
                });
                from_contant.append(row)
                curr_page = curr;
                all_pages = result.pages;
            }
        })
    }

    function sku_delete(id) {
        $('#my-confirm').modal({
            relatedTarget: this,
            onConfirm: function (options) {
                $.get('/goods/delete_sku/' + id);
            }
        });
    }
    function shop_delete(id) {
        $('#my-confirm').modal({
            relatedTarget: this,
            onConfirm: function (options) {
                $.get('/shop/shop_delete/' + id);
                get_shop(1)
            }
        });
    }
</script>
