//下拉框选择方法
$(function () {
    var $selected = $('#method_select');
    $selected.on('change', function () {
        method = $(this).val();
        location.href = '/sys/' + method;

    });
});