$(function () {
  var $color_selected = $('#color-select');

  $color_selected.on('change', function () {
    var append_color_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
      var color_code = $(this).attr("data");
      var color_name = $(this).text();
      append_color_text += "<div><span style='color:#" + color_code + ";background: #" + color_code + "'>ccc</span>"
          + color_name
          + "</div>"
    });
    $('#color-select-info').html(append_color_text);
  });

  var $size_selected = $('#size-select');

  $size_selected.on('change', function () {
    var append_size_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
      append_size_text += "<div>" + $(this).text() + " </div>";
    });
    $('#size-select-info').html(append_size_text);
  });

  var $shop_selected = $('#shop-select');

  $shop_selected.on('change', function () {
    var append_shop_text = "已选：<br>";
    $(this).find("option:selected").each(function (i, o) {
      append_shop_text += "<div>" + $(this).text()+  "</div>";
    });
    if (append_shop_text != "") {
      $('#shop-select-info').html(append_shop_text);
    }
  });
});