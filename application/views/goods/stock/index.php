<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<style>
    .remove_sock {
        color: white;
        background: #dd514c;
    }

    .color_td {
        width: 120px;
    }

    .size_td {
        width: 120px;
    }
</style>
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf" onclick="javascript:history.back(-1);method='get_size';">
        <strong class="am-text-primary am-text-lg">库存配置</strong> /
        <small>款号： <?= $sku_info['product_number'] ?></small>
    </div>
</div>
<hr>
<div style="width: 100%;text-align: center"><img src="<?= $sku_info['pic'] ?>"></div>
<hr>
<div class="am-btn-group am-btn-group-xs">
    <button type="button" id="add_stock_button" class="am-btn am-btn-default" onclick="add_stock_row()"><span
            class="am-icon-plus"></span> 新增
    </button>
    <button type="button" class="am-btn am-btn-default" onclick="submit_stock()"><span class="am-icon-save"></span> 保存
    </button>
</div>
<div style="display: none" id="error_msg" class="am-alert am-alert-danger"></div>
<div class="am-tab-panel am-fade am-active am-in" id="tab2">
    <form class="am-form" action="/stock/modify_stock" id="stock_form" method="post">
        <table class="am-table am-table-striped am-table-hover table-main am-text-nowrap">
            <thead>
            <tr>
                <th class="table-id">颜色</th>
                <th class="table-title">尺码</th>
                <th class="table-type">数量</th>
                <th class="table-set">操作</th>
            </tr>
            </thead>
            <tbody id="stock_body">
            <?php foreach ($stock_list['result_rows'] as $stock): ?>
                <tr>
                    <td class="color_td"><?= $stock['color_name'] ?>-<?= $stock['color_num'] ?> <span
                            class="am-badge"
                            style="background: #<?= $stock['color_code'] ?>;color: #<?= $stock['color_code'] ?>">c</span>
                    </td>
                    <td class="size_td"><?= $stock['size_info'] ?>-<?= $stock['size_num'] ?>
                    </td>
                    <td><input type="number" value="<?= $stock['stock_num'] ?>"
                               name="update_stock[<?= $stock['stock_id'] ?>][num]">
                        <input type="hidden" value="<?= $stock['stock_id'] ?>"
                               name="update_stock[<?= $stock['stock_id'] ?>][stock_id]">
                    </td>
                    <td>
                    </td>
                </tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="am-pagination">
            <?= $pagination ?>
        </div>
        <hr>
    </form>
</div>

<div id="color_select" style="display: none">
    <select class="am-form-group color_selecter" name="color[]">
        <?php foreach ($color_list as $color): ?>
            <option value="<?= $color['id'] ?>"><?= $color['name'] ?>-<?= $color['color_num'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div id="size_select" style="display: none">
    <select class="am-form-group size_selecter" name="size[]">
        <?php foreach ($size_list as $size): ?>
            <option value="<?= $size['id'] ?>"><?= $size['size_info'] ?>-<?= $size['size_num'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<?php $this->load->view('footer'); ?>
<script>
    var newNum = 0;
    $("body").delegate('.remove_sock', 'click', function () {
        $(this).parent().parent().remove();
        newNum--;
        console.log(newNum);
        $('#error_msg').hide()
    });
    function add_stock_row() {
        newNum++;
        if (newNum > 5) {
            newNum = 5;
            $('#error_msg').html("请每次提交不要超过5条");
            $('#error_msg').show();
            return
        }
        $('#color_select').find('.color_selecter').attr("name", "stock[" + newNum + "][color_id]");
//        console.log($('#color_select').find('.color_selecter').html())
        $('#size_select').find('.size_selecter').attr("name", "stock[" + newNum + "][size_id]");
        $('#stock_body').append("<tr> " +
            "<td class='color_td'>" + $('#color_select').html() + "</td> " +
            "<td class='size_td'>" + $('#size_select').html() + "</td> " +
            "<td><input type='hidden' name=stock[" + newNum + "][sku_id] value='<?=$sku_info['sku_id']?>'><input type='number' class='am-form-group' min='1' value='0' name=stock[" + newNum + "][num] required></td> " +
            "<td><button type='button' class='am-btn am-btn-default am-btn-xs am-text-secondary remove_sock'>删除</button></td>" +
            "</tr>");
    }

    function submit_stock() {
        $('#stock_form').submit()
    }
</script>
