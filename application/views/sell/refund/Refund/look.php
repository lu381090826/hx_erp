<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/allocate/common.css">

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/refund/Refund/index")?>">退货列表</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/refund/Refund/order")."/$order->id"?>">退货订单</a> /
        <small>查看配货</small>
    </div>
</div>

<!-- 主体 -->
<div id="app">
    <form class="am-form">
        <!-- 修改按钮 -->
        <div class="am-form-group">
            <a class="btn btn-success" href="<?=site_url("/sell/refund/Refund/modify")."/$order->id/$refund->id"?>" role="button">修改订单</a>
        </div>

        <!-- 配货单信息 -->
        <div class="panel-group" id="accordion_2" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="heading_2_1" >
                    <a role="button" data-toggle="collapse" data-parent="#accordion_2" href="#collapse_2_1" aria-expanded="false" aria-controls="collapse_2_1">
                        <h1 class="panel-title" >
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                            配货单信息
                        </h1>
                    </a>
                </div>
                <div id="collapse_2_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_2_1">
                    <div class="panel-body">
                        <div class="am-form-group">
                            <label for="doc-ipt-email-1">退货单号：{{refund.order_num}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-email-1">备注信息：{{refund.remark}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 销售单信息 -->
        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="heading_1_1" >
                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapse_1_1" aria-expanded="false" aria-controls="collapse_1_1">
                        <h1 class="panel-title" >
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                            销售单信息
                        </h1>
                    </a>
                </div>
                <div id="collapse_1_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_1_1">
                    <div class="panel-body">

                        <div class="am-form-group">
                            <label for="doc-ipt-email-1">客户姓名：{{client.name}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-email-1">销售单号：{{order.order_num}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-1">开单员工：{{seller.name}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">开单日期：{{order.create_date}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">收款日期：{{order.receipt_date}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">销售总量：{{order.total_num}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">销售总额：{{order.total_price}} 元</label>
                        </div>

                        <div class="am-form-group" v-if="order.delivery_type == 0">
                            <label for="doc-ipt-pwd-2">收货地址：{{order.delivery_addr}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">备注信息：{{order.remark}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 搜索条 -->
        <div class="am-form-group">
            <label>款号搜索</label>
            <div class="am-input-group">
                <span class="input-group-addon" id="sizing-addon2"><i class="am-icon-search"></i></span>
                <input type="text" class="form-control" placeholder="查询款号" v-model="filter">
            </div>
        </div>

        <!-- 配货表 -->
        <div class="am-form-group">
            <table class="table table-striped">
                <thead>
                    <td>款号</td><td>颜色</td><td>尺码</td>
                    <td>订单数量</td>
                    <td>退货数量</td>
                </thead>
                <tr v-for="item in list" v-if="item.spu_id.indexOf(filter) != -1">
                    <td>{{item.spu_id}}</td><td>{{item.color}}</td><td>{{item.size}}</td>
                    <td>{{item.num_order}}</td>
                    <td>{{item.num}}</td>
                </tr>
            </table>
        </div>
    </form>
</div>

<script>
    //vue构造器
    var vue = new Vue({
        el:"#app",
        data: {
            //销售单
            form:null,
            refund:null,
            list:[],
            filter:"",
        },
        created:function()
        {
            this.order = <?=json_encode($order)?>;
            this.refund = <?=json_encode($refund)?>;
            this.list = <?=json_encode($list)?>;
            this.seller = <?=json_encode($seller)?>;
            this.client = <?=json_encode($client)?>;

            console.log(this.list);
        },
        methods: {

        }
    })
</script>