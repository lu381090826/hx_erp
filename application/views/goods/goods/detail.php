<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<?php if (check_auth(10)): ?>
    <input type="button" name="Submit" class="am-btn am-btn-default"
           onclick="location.href='/sku/action_edit_sku/<?= $goods_id ?>'" value="编辑">
    <?php if ($status == config_load('goods_status', 'goods_sell_off')): ?>
        <input type="button" name="Submit" class="am-btn am-btn-warning"
               onclick="sell_state_on('<?= $goods_id ?>')" value="上架">
    <?php endif; ?>
    <?php if ($status == config_load('goods_status', 'goods_sell_on')): ?>
        <input type="button" name="Submit" class="am-btn am-btn-warning"
               onclick="sell_state_off('<?= $goods_id ?>')" value="下架">
    <?php endif; ?>
    <input type="button" name="Submit" class="am-btn am-btn-danger"
           onclick="sku_delete('<?= $goods_id ?>')" value="删除">
<?php endif; ?>
<form class="am-form">
    <fieldset>
        <legend>款号 <strong><?= $goods_id ?></strong></legend>

        <div class="am-form-group">
            <label>状态</label>
            <?php if ($status == config_load('goods_status', 'goods_sell_off')): ?>
                已下架
            <?php endif;?>
            <?php if ($status == config_load('goods_status', 'goods_sell_on')): ?>
                上架中
            <?php endif;?>
        </div>

        <?php if (check_auth(11)) : ?>
            <div class="am-form-group">
                <label>成本</label>
                ¥<?= $cost ?>
            </div>
        <?php endif; ?>
        <div class="am-form-group">
            <label>价格</label>
            ¥<?= $price ?>
        </div>

        <div class="am-form-group">
            <label>品牌</label>
            <?= $brand ?>
        </div>

        <div class="am-form-group">
            <label>分类</label>
            <?= isset($category_list[$category_id]) ? $category_list[$category_id] : "" ?>
        </div>

        <div class="am-form-group">
            <label>小图</label>
            <img width="80px" src="<?= $pic ?>"/>
        </div>

        <div class="am-form-group">
            <label>SKU：</label>
            <table class="am-table">
                <thead>
                <tr>
                    <th>sku_id</th>
                    <th>颜色</th>
                    <th>尺码</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sku_list as $row): ?>
                    <?php if (isset($color_cache[$row['color_id']]) && isset($size_cache[$row['size_id']])): ?>
                        <tr>
                            <td><?= $row['sku_id'] ?></td>
                            <td><span
                                    style="color: #<?= $color_cache[$row['color_id']]['color_code'] ?>;background: #<?= $color_cache[$row['color_id']]['color_code'] ?>;">
                    ccc</span><?= $color_cache[$row['color_id']]['name'] ?></td>
                            <td><?= $size_cache[$row['size_id']]['size_info'] ?></td>
                            <td>
                                <?php if ($row['status'] == config_load('goods_status', 'goods_sell_off')): ?>
                                    已下架
                                <?php endif;?>
                                <?php if ($row['status'] == config_load('goods_status', 'goods_sell_on')): ?>
                                    上架中
                                <?php endif;?>
                            </td>
                            <?php if (check_auth(10)): ?>
                                <td><a href="javascript:;" onclick="sku_id_delete('<?= $row['sku_id'] ?>')">删除</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>


        <div class="am-form-group">
            <label>性别</label>
            <?php if ($sex == 1) { ?>
                男
            <?php } else if($sex == 2) { ?>
                女
            <?php } ?>
        </div>

        <div class="am-form-group">
            <label>年份</label>
            <?= $year ?>年
        </div>
        <div class="am-form-group">
            <label>月份</label>
            <?= $month ?>月
        </div>
        <div class="am-form-group">
            <label>季节</label>
            <?php switch ($season) {
                case 1:
                    echo "春";
                    break;
                case 2:
                    echo "夏";
                    break;
                case 3:
                    echo "秋";
                    break;
                case 4:
                    echo "冬";
                    break;
                default:
                    break;
            } ?>
        </div>
        <div class="am-form-group">
            <label>备注</label>
            <?= $memo ?>
        </div>

        <div class="am-form-group">
            <label>操作人</label>
            <?= $op_uid ?>
        </div>

        <div class="am-form-group">
            <label>添加时间</label>
            <?= $create_time ?>
        </div>
    </fieldset>
</form>
<div class="am-modal am-modal-confirm" tabindex="-1" id="sku_id-remove-confirm">
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

<div class="am-modal am-modal-confirm" tabindex="-1" id="sell-off">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示</div>
        <div class="am-modal-bd">
            确定要下架吗？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>

<div class="am-modal am-modal-confirm" tabindex="-1" id="sell-on">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示</div>
        <div class="am-modal-bd">
            确定要上架吗？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>

<div class="am-modal am-modal-confirm" tabindex="-1" id="goods-remove-confirm">
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
<?php
$this->load->view('footer');
?>
<script>
    var delete_id = 0;
    function sku_id_delete(id) {
        delete_id = id;
        $('#sku_id-remove-confirm').modal({
            relatedTarget: this,
            onConfirm: function (options) {
                $.post('/sku/action_delete_sku/' + delete_id);
                setTimeout(function () {
                    window.location.reload(true);
                }, 300);
            }
        });
    }

    //商品下架
    var goods_id = 0;
    function sell_state_off(id) {
        goods_id = id;
        $('#sell-off').modal({
            relatedTarget: this,
            onConfirm: function (options) {
                $.post('/goods/action_sell_state_off/' + goods_id);
                setTimeout(function () {
                    window.location.reload(true);
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
                    window.location.reload(true);
                }, 300);
            }
        });
    }
    //删除
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
                    window.location.reload(true);
                }, 300);
            }
        });
    }

</script>
