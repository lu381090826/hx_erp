$(document).ready(function () {
    fromLoad('goods');
});

function expotr() {
    var form = $("<form>");//定义一个form表单
    form.attr("style", "display:none");
    form.attr("target", "");
    form.attr("method", "post");
    form.attr("action", "/goods/action_export");
    var data = getFormJson($('#goods_search_form'));
    $.each(data, function (i, o) {
        var input = $("<input>");
        input.attr("type", "hidden");
        input.attr("name", i);
        input.attr("value", o);
        form.append(input);
    });

    $("body").append(form);//将表单放置在web中
    // form.ajaxSubmit({url: '/goods/action_export', type: 'post'})
    form.submit();//表单提交
}
function search_goods() {
    get_goods(1);
    $("#page").empty();
    $('#page').page({
        pages: all_pages,
        curr: curr_page,
        groups: 5,
        prev: "上一页",
        next: "下一页",
        jump: function (context, first) {
//                if(!first)
//                    eval(method+"(" + context.option.curr + ")");
        }
    });
    $('#goods_search').collapse('close')
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
        });

}

function goods_show(result) {
    var goods_content = "";
    $.each(result.result_rows, function (i, o) {
        if (o.status == 2) {
            var sell_state = "<div><a onclick=\"sell_state_on('" + o.goods_id + "')\">上架</a></button></div>";
        } else {
            var sell_state = "<div><a onclick=\"sell_state_off('" + o.goods_id + "')\">下架</a></button></div>";
        }
        goods_content += "<tr>" +
            "<td><img class='pic' src='" + o.pic + "'></td>" +
            "<td>" + o.goods_id + "</td>" +
            "<td>¥" + o.price + "</td>" +
            "<td>" + 0 + "</td>" +
            "<td>" + o.create_time + "</td>" +
            "<td align='center' valign='middle' style='word-break:break-all'>" +
            "<div><a href='/goods/goods_detail/" + o.goods_id + "'>详情</a><div>" +
            "<div><a onclick=\"sku_delete('" + o.goods_id + "')\">删除</a></div>" +
            sell_state +
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
            $.each(result, function (i, o) {
                row += "<tr>" +
                    "<td>" + o.id + "</td>" +
                    "<td>" + o.category_name + "</td>" +
                    "<td><a href='javascript:;' data-id=" + o.id + " onclick='category_delete(" + o.id + ")'>删除</a></td>" +
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
                    "<td><a data-id=" + o.id + " onclick=\"color_delete(" + o.id + ")\">删除</a></td>" +
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
                    "<td><a onclick=\"size_delete('" + o.id + "')\">删除</a></td>" +
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
            from_contant.append(row);
            curr_page = curr;
            all_pages = result.pages;
        }
    })
}

//避免模态框缓存，用全局变量
var delete_id = 0;
var goods_id = 0;
function color_delete(id) {
    delete_id = id;
    $('#color-remove-confirm').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/color/delete_color/' + delete_id);
            setTimeout(function () {
                fromLoad('goods', 'get_color');
            }, 300);
        }
    });
}

function size_delete(id) {
    delete_id = id;
    $('#size-remove-confirm').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/size/delete_size/' + delete_id);
            setTimeout(function () {
                fromLoad('goods', 'get_size');
            }, 300);
        }
    });
}
function sku_delete(id) {
    delete_id = id;
    $('#goods-remove-confirm').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            // $.post('/goods/delete_sku/' + delete_id);
            $.ajax({
                type: 'post',
                async: false,
                url: '/goods/delete_sku/' + delete_id,
                dateType: 'json',
                success: function (result) {
                    if (result.code != 0) {
                        alert(result.code + '|' + result.msg);
                    }
                }
            });
            setTimeout(function () {
                fromLoad('goods', 'get_goods');
            }, 300);
        }
    });
}
//商品下架
function sell_state_off(id) {
    goods_id = id;
    $('#sell-off').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/goods/action_sell_state_off/' + goods_id);
            setTimeout(function () {
                fromLoad('goods', 'get_goods');
            }, 300);
        }
    });
}
//商品上架
function sell_state_on(id) {
    goods_id = id;
    $('#sell-on').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/goods/action_sell_state_on/' + goods_id);
            setTimeout(function () {
                fromLoad('goods', 'get_goods');
            }, 300);
        }
    });
}
function shop_delete(id) {
    delete_id = id;
    $('#shop-remove-confirm').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/shop/shop_delete/' + delete_id);
            setTimeout(function () {
                fromLoad('goods', 'get_shop');
            }, 300);
        }
    });
}
function category_delete(id) {
    delete_id = id;
    $('#categoty-remove-confirm').modal({
        relatedTarget: this,
        onConfirm: function (options) {
            $.post('/category/delete_category/' + delete_id);
            setTimeout(function () {
                fromLoad('goods', 'get_category');
            }, 300);
        }
    });
}