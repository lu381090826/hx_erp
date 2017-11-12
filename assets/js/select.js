function extracted_color() {
    var append_color_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
        var color_code = $(this).attr("data");
        var color_name = $(this).text();
        append_color_text += "<div><span style='color:#" + color_code + ";background: #" + color_code + "'>ccc</span>"
            + color_name
            + "</div>"
    });
    $('#color-select-info').html(append_color_text);
}
function extracted_size() {
    var append_size_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
        append_size_text += "<div>" + $(this).text() + " </div>";
    });
    $('#size-select-info').html(append_size_text);
}
function extracted_shop() {
    var append_shop_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
        append_shop_text += "<div>" + $(this).text() + "</div>";
    });
    $('#shop-select-info').html(append_shop_text);
}

extracted_color.call($('#color-select'));
extracted_size.call($('#size-select'));
extracted_shop.call($('#shop-select'));

$(function () {
    $('#color-select').on('change', function () {
        extracted_color.call($(this));
    });

    $('#size-select').on('change', function () {
        extracted_size.call($(this))
    });

    $('#shop-select').on('change', function () {
        extracted_shop.call($(this))
    });
});