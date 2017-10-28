<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<input type="button" name="Submit" class="am-btn am-btn-default"
       onclick="location.href='/goods/goods_detail_edit/<?= $goods_id ?>'" value="编辑">
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
            <?= $category_list[$category_id] ?>
        </div>

        <div class="am-form-group">
            <label>小图</label>
            <img width="80px" src="<?= $pic ?>" />
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
