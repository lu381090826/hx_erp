<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<input type="button" name="Submit" class="am-btn am-btn-default"
       onclick="location.href='/sku/action_edit_sku/<?= $goods_id ?>'" value="编辑">
<!--<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="删除">-->
<form class="am-form">
    <fieldset>
        <legend>款号 <strong><?= $goods_id ?></strong></legend>

        <div class="am-form-group">
            <label>成本</label>
            ¥<?= $cost ?>
        </div>

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
                            <td>删除</td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                </tbody>
            </table>
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

<?php
$this->load->view('footer');
?>
