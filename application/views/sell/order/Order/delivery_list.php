<!-- 二级导航 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <select data-am-selected id="subnav">
        <option value="<?=site_url("sell/order/Order")?>">销售订单</option>
        <option value="#" selected>直接报货</option>
        <option value="<?=site_url("sell/client/Client")?>">客户管理</option>
        <option value="<?=site_url("sell/allocate/Allocate")?>">报货单</option>
        <option value="<?=site_url("sell/refund/Refund")?>">退货单</option>
        <option value="<?=site_url("sell/report/Report")?>">报表查询</option>
    </select>
</div>
<hr>

<!-- 动作按钮 -->
<div class="am-g">
    <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
            <div class="am-btn-group am-btn-group-xs">
                <a type="button" class="am-btn am-btn-default"  href="<?=site_url("$_controller->views/delivery_create")?>"><span class="am-icon-plus"></span> 新增</a>
            </div>
        </div>
    </div>
</div>
<!-- 列表内容 -->
<div class="am-g">
    <div class="am-u-sm-12">
        <form class="am-form">
            <!--DataGrid-->
            <?=ViewComponent::DataGrid($_controller,$searched,[
                'id','creater',"shop",'order_num','total_num','remark','create_at','statusName'
            ],[
                ["url"=>"javascript:scrap({{id}})","label"=>"作废"]
            ])?>
            <!--分页条-->
            <?=ViewComponent::PagesBar($page,$size,$searched->count)?>
            <hr />
        </form>
    </div>
</div>

<script>
    function scrap(id){
        var _this = this;
        var w=confirm("是否确定报废该销售单?")
        if (w==true) {
            $.ajax({
                url: '<?=site_url($_controller->controller . "/scrap_asyn")?>',
                type: "post",
                dataType: "json",
                data: {
                    "id": id,
                },
                success: function (result) {
                    if (result.state.return_code == 0) {
                        location.reload();
                    }
                    else
                        alert(item.state.return_msg);
                }
            });
        }
    }
</script>