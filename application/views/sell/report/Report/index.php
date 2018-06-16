<!-- 样式 -->
<style>
    #report-date img{
        max-width: 50px;
        max-height: 50px;
    }
    .am-table > tbody + tbody tr:first-child td{
        border-top-width: 1px;
    }
    .actions-bar{
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .actions-bar button{
        margin-bottom: 10px;
    }
</style>

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <select data-am-selected id="subnav">
        <option value="<?=site_url("sell/order/Order")?>">销售订单</option>
        <option value="<?=site_url("sell/order/Order/deliverys")?>">直接报货</option>
        <option value="<?=site_url("sell/client/Client")?>">客户管理</option>
        <option value="<?=site_url("sell/allocate/Allocate/index2")?>">报货订单</option>
        <option value="<?=site_url("sell/refund/Refund")?>">退货订单</option>
        <option value="#" selected>报表查询</option>
    </select>
</div>
<hr>

<div id="app">
    <!-- 搜索表格 -->
    <form class="am-form-inline" role="form">
        <div>
            <div class="am-form-group">
                <label for="doc-ipt-email-1">开始日期</label>
                <input type="date" class="am-form-field" placeholder="开始日期" v-model="start_date">
            </div>
            <div class="am-form-group">
                <label for="doc-ipt-email-1">结束日期</label>
                <input type="date" class="am-form-field" placeholder="结束日期" v-model="stop_date">
            </div>
            <div class="am-form-group">
                <label for="doc-ipt-email-1">款号</label>
                <input type="text" class="am-form-field" placeholder="款号" v-model="spu_id">
            </div>
            <div class="am-form-group">
                <label for="doc-ipt-email-1">客户</label>
                <input type="text" class="am-form-field" placeholder="客户" v-model="client_name">
            </div>
        </div>
        <div class="actions-bar">
            <button type="button" class="am-btn am-btn-primary" v-on:click="search_date">查看日报表</button>
            <button type="button" class="am-btn am-btn-primary" v-on:click="search_client">客户精选</button>
            <button type="button" class="am-btn am-btn-success" v-on:click="report_date">导出日报表</button>
            <button type="button" class="am-btn am-btn-success" v-on:click="report_client">导出客户精选</button>
        </div>
    </form>

    <br>

    <!-- 日报表 -->
    <div id="report-date" class="am-panel am-panel-secondary" v-if="list_date != null">
        <div class="am-panel-hd">搜索结果：日报表</div>
        <div class="am-panel-bd">
            <table class="am-table am-table-bordered am-table-centered">
                <tr>
                    <th>图片</th>
                    <th>款号</th>
                    <th>数量</th>
                    <th>金额</th>
                    <th>颜色</th>
                    <th>码数</th>
                    <th>数量</th>
                    <th>金额</th>
                </tr>
                <tbody v-for="item in list_date">
                <tr class="tr_amount">
                    <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle"><img :src="item.snap_pic"/></td>
                    <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.spu_id}}</td>
                    <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.num}}</td>
                    <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.amount}}</td>
                </tr>
                <tr v-for="item_sku in item.sku">
                    <td>{{item_sku.color}}</td>
                    <td>{{item_sku.size}}</td>
                    <td>{{item_sku.amount}}</td>
                    <td>{{item_sku.num}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 客户精选 -->
    <div id="report-client" class="am-panel am-panel-success" v-if="list_client != null">
        <div class="am-panel-hd">搜索结果：客户精选</div>
        <div class="am-panel-bd">
            <table class="am-table am-table-bordered am-table-centered">
                <tr>
                    <th>客户</th>
                    <th>数量</th>
                    <th>金额</th>
                    <th>款号</th>
                    <th>颜色</th>
                    <th>数量</th>
                </tr>
                <tbody v-for="item in list_client">
                    <tr class="tr_amount">
                        <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.client_name}}</td>
                        <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.num}}</td>
                        <td :rowspan="parseInt(item.sku_count) + 1" class="am-text-middle">{{item.amount}}</td>
                    </tr>
                    <tr v-for="item_sku in item.sku">
                        <td>{{item_sku.spu_id}}</td>
                        <td>{{item_sku.color}}</td>
                        <td>{{item_sku.num}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="export" action="<?=site_url($_controller->controller."/export")?>" method="post">
    <input type="hidden" name="data">
</form>

<script>
    //vue构造器
    var vue = new Vue({
        el:"#app",
        data: {
            //查询参数
            start_date:"",
            stop_date:"",
            spu_id:"",
            client_name:"",

            //销售单
            list_client:null,
            list_date:null
        },
        created:function() {
        },
        methods: {
            search_client:function(){
                //Ajax
                $.ajax({
                    url:'<?=site_url($_controller->api."/search_report_client")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        start_date:this.start_date,
                        stop_date:this.stop_date,
                        spu_id:this.spu_id,
                        client_name:this.client_name,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0)
                            vue.list_client = result.data;
                    }
                });
            },
            search_date:function(){
                //Ajax
                $.ajax({
                    url:'<?=site_url($_controller->api."/search_report_date")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        start_date:this.start_date,
                        stop_date:this.stop_date,
                        spu_id:this.spu_id,
                        client_name:this.client_name,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0)
                            vue.list_date = result.data;
                        console.log(vue.list_date);
                    }
                });
            },
            report_client:function(){
                data = {
                    export_type:"client",
                    start_date:this.start_date,
                    stop_date:this.stop_date,
                    spu_id:this.spu_id,
                    client_name:this.client_name,};
                $("#export input[name='data']").val(JSON.stringify(data));
                $("#export").submit();
            },
            report_date:function(){
                data = {
                    export_type:"date",
                    start_date:this.start_date,
                    stop_date:this.stop_date,
                    spu_id:this.spu_id,
                    client_name:this.client_name,};
                $("#export input[name='data']").val(JSON.stringify(data));
                $("#export").submit();
            }
        }
    })
</script>