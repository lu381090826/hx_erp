var __CONTROLLER__ = '';
var method = $('#method_select').val();
var from_thead = $('#from_thead');
var from_contant = $('#from_contant');
var other_select_div = $('.div-' + method);
var other_select = $('.other-select');
var page = $('#page');

function getContentUrl() {
    return '/' + __CONTROLLER__ + '/' + method + '/';
}

//清空表
function fromClean() {
    from_thead.empty();
    from_contant.empty();
    page.empty();
    other_select.hide();
}
function tableClean() {
    from_thead.empty();
    from_contant.empty();
}

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

//展示分页
var curr_page = 1;
var all_pages = 1;

function fromLoad(controller,function_name) {
    fromClean();

    other_select_div = $('#div-' + method);
    other_select_div.show();
    __CONTROLLER__ = controller;

    if (function_name) {
        method = function_name;
    }

    eval(method + "(1)");

    $('#page').page({
        pages: all_pages,
        curr: curr_page,
        groups: 5,
        prev: "上一页",
        next: "下一页",
        jump: function (context, first) {
            if(!first)
            eval(method+"(" + context.option.curr + ")");
        }
    });
}

//下拉框选择方法
$(function () {
    var $selected = $('#method_select');
    $selected.on('change', function () {
        fromClean();
        method = $(this).val();
        other_select_div = $('#div-' + method);
        other_select_div.show();
        fromLoad(__CONTROLLER__)
    });
});
