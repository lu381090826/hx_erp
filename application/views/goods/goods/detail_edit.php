<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
    <div class="am-g">
        <div class="am-u-lg-12">

            <form class="am-form" method="post" action="/goods/update_goods">
                <fieldset>
                    <legend>款号 <strong><?= $goods_id ?></strong></legend>

                    <div class="am-form-group">
                        <label>成本</label>
                        ¥<input type="text" name="cosr" value="<?= $cost ?>">
                    </div>

                    <div class="am-form-group">
                        <label>价格</label>
                        ¥<input type="text" name="price" value="<?= $price ?>">
                    </div>

                    <div class="am-form-group">
                        <label>品牌</label>
                        <input type="text" name="brand" value="<?= $brand ?>">
                    </div>

                    <div class="am-form-group">
                        <label>分类</label>
                        <?= $category_list[$category_id] ?>
                    </div>

                    <div class="am-form-group">
                        <label>小图</label>
                        <img width="80px" src="<?= $pic ?>"/>
                    </div>

                    <div class="am-form-group">
                        <label>备注</label>
                        <input type="text" name="memo" value="<?= $memo ?>">
                    </div>
                </fieldset>
                <input type="submit" class="am-btn am-btn-default" value="提交">
            </form>
        </div>
    </div>
<?php
$this->load->view('footer');
?>
