<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<table class="am-table">
    <thead>
    </thead>

    <tbody>
    <tr>
        <td>id</td>
        <td><?= $id ?></td>
    </tr>
    <!--    <tr>-->
    <!--        <td>商品id</td>-->
    <!--        <td>--><? //= $goods_id ?><!--</td>-->
    <!--    </tr>-->
    <tr>
        <td>商品编号</td>
        <td><?= $sku_id ?></td>
    </tr>
    <tr>
        <td>商品名</td>
        <td><?= $name ?></td>
    </tr>
    <tr>
        <td>商品代码</td>
        <td><?= $product_number ?></td>
    </tr>
    <tr>
        <td>条形码</td>
        <td><?= $bar_code ?></td>
    </tr>
    <tr>
        <td>备案号</td>
        <td><?= $record_number ?></td>
    </tr>
    <tr>
        <td>品牌</td>
        <td><?= $brand_id ?></td>
    </tr>
    <tr>
        <td>分类</td>
        <td><?= $category_id ?></td>
    </tr>
    <tr>
        <td>属性</td>
        <td><?= $property_id ?></td>
    </tr>
    <tr>
        <td>价格（元）</td>
        <td><?= $price ?></td>
    </tr>
    <tr>
        <td>原产地</td>
        <td><?= $source_area ?></td>
    </tr>
    <tr>
        <td>是否进口</td>
        <td><?= $import ?></td>
    </tr>
    <tr>
        <td>重量单位</td>
        <td><?= $unit ?></td>
    </tr>
    <tr>
        <td>重量</td>
        <td><?= $weight ?></td>
    </tr>
    <tr>
        <td>图片</td>
        <td><img src="<?= $pic_normal ?>"></td>
    </tr>
    <tr>
        <td>备注</td>
        <td><?= $memo ?></td>
    </tr>
    <tr>
        <td>创建时间</td>
        <td><?= $create_time ?></td>
    </tr>
    <tr>
        <td>修改时间</td>
        <td><?= $modify_time ?></td>
    </tr>

    </tbody>
</table>

<?php
$this->load->view('footer');
?>
