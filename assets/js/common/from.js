var __CONTROLLER__ = '';

var method = $('#method_select').val();
var from_thead = $('#from_thead');
var from_contant = $('#from_contant');
var other_select_div = $('.div-' + method);
var other_select = $('.other-select');

function getContentUrl() {
    return '/' + __CONTROLLER__ + '/' + method + '/';
}

//清空表
function fromClean() {
    from_thead.empty();
    from_contant.empty();
    other_select.hide();
}

//展示分页
function fromLoad(controller) {
    fromClean();
    __CONTROLLER__ = controller;

    $.get(getContentUrl() + "1", function (result) {
        if (result.pages > 1) {
            $("#page").page({
                pages: result.pages,
                first: "首页", //设置false则不显示，默认为false
                last: "尾页", //设置false则不显示，默认为false
                prev: '<', //若不显示，设置false即可，默认为上一页
                next: '>', //若不显示，设置false即可，默认为下一页
                groups: 3, //连续显示分页数
                jump: function (context, first) {
                    from_thead.empty();
                    from_contant.empty();
                    eval(method + '(' + context.option.curr + ')');
                }
            })
        } else {
            eval(method + '(1)');
        }
    }, 'JSON');
    other_select_div.show()
}

//下拉框选择方法
$(function () {
    var $selected = $('#method_select');
    $selected.on('change', function () {
        fromClean();
        method = $(this).val();
        other_select_div = $('.div-' + method);
        //用字符串执行方法
        // eval(method + '(1)');
        other_select_div.show();
        fromLoad(__CONTROLLER__)
    });
});
