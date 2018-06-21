<!-- 样式 -->
<style>
    #action{
        padding-left: 32px;
        padding-right: 32px;
        padding-top: 16px;
        padding-bottom: 32px;
    }
    #lodop{
        max-width: 400px;
        padding: 0px 32px;
        /*visibility: hidden;*/
    }
</style>

<!-- 加载lodop -->
<script src="http://<?=$lodop["ip"].":".$lodop["port"]?>/CLodopfuncs.js"></script>

<!-- 打印操作 -->
<div id="action">
    <form class="am-form-inline" role="form">
        <!--<div class="am-form-group">
            <select id="print-select" size="1" class="am-form-field am-input-sm"></select>
        </div>

        <button type="button" class="am-btn am-btn-primary am-btn-sm" onclick="orderPrint(false);">打印</button>-->
        <button type="button" class="am-btn am-btn-success am-btn-sm" onclick="orderPrint(true);">预览打印</button>
    </form>
</div>

<!-- 打印内容 -->
<?php //var_dump($shop) ?>
<div id="lodop">
    <div style="padding: 0pt 5pt">
        <div style="text-align: center;margin-bottom: 5pt">
            <div style="font-size: 22pt;font-weight: 700;">T · G</div>
            <div style="font-size: 14pt;font-weight: 700;">THX GIVING</div>
        </div>

        <table style="width: 100%;font-size: 9pt;margin-bottom: 9pt">
            <tr><td>单号：<?=$order->order_num?></td><td style="text-align: right">日期：<?=date("Y-m-d H:i:s",$order->create_at)?></td></tr>
            <tr><td colspan="2">地址：<?=isset($shop["address"])?$shop["address"]:""?></td></tr>
            <tr><td colspan="2">档口座机：<?=isset($shop["phone"])?$shop["phone"]:""?></td></tr>
            <tr><td colspan="2">手机/微信：<?=isset($shop["owner_mobile"])?$shop["owner_mobile"]:""?></td></tr>
        </table>

        <table style="font-size: 9pt;margin-bottom: 9pt">
            <tr><td>客户：<?=$client->name?>（<?=$client->phone?>）</td></tr>
        </table>

        <table width="100%" style="font-size: 9pt">
            <tr><td colspan="6">备注：</td></tr>
            <tr><td>款号</td><td>颜色</td><td>尺码</td><td>数量</td><td>单价</td><td>小计</td></tr>
            <?php foreach ($order->goods as $spu):?>
                <?php foreach ($spu->skus as $sku):?>
                    <?php if($sku->num > 0):?>
                    <tr>
                        <td><?=$spu->id?></td>
                        <td><?=$sku->color?></td>
                        <td><?=$sku->size?></td>
                        <td><?=$sku->num?></td>
                        <td><?=$spu->snap_price?></td>
                        <td><?=$sku->num * $spu->snap_price?></td>
                    </tr>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endforeach;?>
        </table>
        <table width="100%" style="font-size: 9pt;margin: 5pt 0pt">
            <tr><td>总数量：<?=$order->total_num?></td><td>总金额：<?=$order->total_price?></td></tr>
        </table>
        <table width="100%" style="font-size: 9pt;margin-bottom: 9pt">
            <tr><td>支付方式：<?=$order->getPaymentName()?></td><td>开单人：<?=$seller["name"]?></td></tr>
        </table>

        <table width="100%" style="font-size: 9pt;margin-bottom: 9pt">
            <tr><td>工行ICBC：<?=$shop["bank_account"]?></td></tr>
            <tr><td>农行ICBC：<?=$shop["bank_account_2"]?></td></tr>
            <tr><td>户名：<?=$shop["bank_name"]?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 开户行：<?=$shop["bank_deposit"]?></td></tr>
            <tr><td>支付宝：<?=$shop["alipay_account"]?> &nbsp;&nbsp;&nbsp;&nbsp; 户名：<?=$shop["alipay_name"]?></td></tr>
            <tr><td>注意：产品如有质量问题，请七天内返回给档口，我们将及时为您代修或换货处理，逾期不受理，钱款请当面点清商店概不负责</td></tr>
        </table>

        <div style="width: 100%;display: table;margin-bottom: 9pt">
            <div style="width: 50%;float: left;text-align: center">
                <div>
                    <img src="<?=$shop["wx_code_img"]?>" style="width: 100px"/>
                    <!--<img src="http://qr.liantu.com/api.php?text=231321321321" style="width: 100px"/>-->
                </div>
                <div style="font-size: 16pt">
                    微信
                </div>
            </div>
            <div style="width: 50%;float: right;text-align: center">
                <div>
                    <img src="<?=$shop["alipay_code_img"]?>" style="width: 100px"/>
                    <!--<img src="http://qr.liantu.com/api.php?text=231321321321" style="width: 100px"/>-->
                </div>
                <div style="font-size: 16pt">
                    支付宝
                </div>
            </div>
        </div>

        <div style="height: 30px;width: 100%"></div>
    </div>
</div>

<script>
    $(function(){
        //设置打印列表
        CLODOP.Create_Printer_List(document.getElementById('print-select'));

        //设置当前店铺默认打印机
        var print_name = "<?=isset($shop["name"])?$shop["name"]:""?>";
        $("#print-select option").each(function () {
            if($(this).text() == print_name){
                $(this).attr("selected",true);
            }
        })
    })

    function orderPrint(toPrview){
        //初始化内容
        LODOP.PRINT_INITA(30,0,10,10,"订单打印");
        LODOP.SET_PRINT_PAGESIZE(3,"80mm",0,"");
        LODOP.SET_PRINT_STYLE("FontSize",6);
        LODOP.ADD_PRINT_HTM(0,0,"100%","100%",document.getElementById("lodop").innerHTML);

        //设置打印机
        LODOP.SET_PRINTER_INDEXA(document.getElementById("print-select").value);

        //预览或打印
        if (toPrview)
            LODOP.PREVIEW();
        else{
            var print = LODOP.PRINT();
            console.log(print);
            alert("已请求打印订单……");
        }
    };
</script>