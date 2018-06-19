<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <select data-am-selected id="subnav">
        <option value="<?=site_url("sell/order/Order")?>">销售订单</option>
        <option value="<?=site_url("sell/order/Order/deliverys")?>">直接报货</option>
        <option value="<?=site_url("sell/client/Client")?>">客户管理</option>
        <option value="<?=site_url("sell/allocate/Allocate")?>">报货单</option>
        <option value="#" selected>退货单</option>
        <option value="<?=site_url("sell/report/Report")?>">报表查询</option>
    </select>
</div>
<hr>

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <small><?=$_controller->describe->desc?></small>
    </div>
</div>
<hr>
<!-- 列表内容 -->
<div class="am-g">
    <div class="am-u-sm-12">
        <form class="am-form">
            <!--DataGrid-->
            <?=ViewComponent::DataGrid($_controller,$searched,[
                "client_name","client_phone","statusName","total_num",
                //'id','order_id','order_num','create_at','create_user_id','status','remark','total_num',
                //'sell_order_num',"order_num","client_name","client_phone","statusName","total_num",
            ],[array('label'=>"详情", 'url'=>site_url("/sell/refund/Refund/look/{{id}}")),]
            )?>
            <!--分页条-->
            <?=ViewComponent::PagesBar($page,$size,$searched->count)?>
            <hr />
        </form>
    </div>
</div>    