<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/allocate/common.css">

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/form/Form")?>">销售订单</a> /
        <small>配货订单</small>
    </div>
</div>

<!-- 主体 -->
<div id = "app">
    <form class="am-form">
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
                            <label for="doc-ipt-email-1">销售单号：{{form.order_num}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-1">开单员工：{{seller.name}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">开单日期：{{form.create_date}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">收款日期：{{form.receipt_date}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">销售总量：{{form.total_num}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">销售总额：{{form.total_price}} 元</label>
                        </div>

                        <div class="am-form-group" v-if="form.delivery_type == 0">
                            <label for="doc-ipt-pwd-2">收货地址：{{form.delivery_addr}}</label>
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-2">备注信息：{{form.remark}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 销售单商品 -->
        <div class="panel-group" id="accordion_7" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="heading_7_1">
                    <a role="button" data-toggle="collapse" data-parent="#accordion_7" href="#collapse_7_1" aria-expanded="false" aria-controls="collapse_7_1">
                        <h1 class="panel-title">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                            销售单清单
                        </h1>
                    </a>
                </div>
                <div id="collapse_7_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_7_1">
                    <div class="panel-body">
                        <div class="sku-table row">
                            <!-- SKU表 -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <td>款号</td><td>颜色</td><td>尺码</td><td>数量</td>
                                    </tr>
                                </thead>
                                <tbody  v-for="item in goods">
                                    <tr v-for="sku in item.skus">
                                        <td>{{item.spu_id}}</td><td>{{sku.color}}</td><td>{{sku.size}}</td><td>{{sku.num}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="am-btn am-btn-primary" @click="add()">添加配货</button>

        <div class="am-form-group">
            <table class="table table-striped">
                <thead>
                <td>配货单号</td><td>配货日期</td><td>状态</td><td>备注</td><td>操作</td>
                </thead>
                <tr v-for="item in list">
                    <td>{{item.order_num}}</td>
                    <td>{{item.create_data}}</td>
                    <td>{{item.statusName}}</td>
                    <td>{{item.remark}}</td>
                    <td><a href="#" @click="look(item.id)">查看</a></td>
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
            list:[],
        },
        created:function()
        {
            this.form = <?=json_encode($form)?>;
            this.list = <?=json_encode($list)?>;
            this.seller = <?=json_encode($seller)?>;
            this.client = <?=json_encode($client)?>;
            this.goods = <?=json_encode($goods)?>;

            console.log(this.goods);
        },
        methods: {
            //添加配货单
            add:function(){
                if(this.form){
                    window.location.href="<?=site_url($_controller->views."/add")?>/"+this.form.id;
                }
            },
            //查看配货单
            look:function(allocate_id){
                window.location.href="<?=site_url($_controller->views."/look")?>/"+allocate_id;
            }
        }
    })
</script>