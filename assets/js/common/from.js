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
    from_thead.empty()
    from_contant.empty()
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

function debugConsole(o) {
    console.log(o);
}
//展示分页
var api_result;
var current_page = 1 ;
function fromLoad(controller) {
    fromClean();
    __CONTROLLER__ = controller;
    $.get(getContentUrl() + 1, function (result) {
        api_result = result;
        if (result.pages > 1) {
            $("#page").page({
                pages: result.pages,
                first: "首页", //设置false则不显示，默认为false
                last: "尾页", //设置false则不显示，默认为false
                prev: '<', //若不显示，设置false即可，默认为上一页
                next: '>', //若不显示，设置false即可，默认为下一页
                groups: 3, //连续显示分页数
                jump: function (context, first) {
                    eval(method + '(' + context.option.curr + ')');
                }
            })
        } else {
            eval(method + '(' + 1 + ')');
        }
    }, 'JSON');

}

//下拉框选择方法
$(function () {
    var $selected = $('#method_select');
    $selected.on('change', function () {
        fromClean();
        method = $(this).val();
        other_select_div = $('#div-' + method);
        //用字符串执行方法
        // eval(method + '(1)');
        other_select_div.show();
        fromLoad(__CONTROLLER__)
    });
});
