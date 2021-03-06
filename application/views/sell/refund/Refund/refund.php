<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/allocate/common.css">

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/refund/Refund/index")?>">退货单</a> /
        <small>添加退货</small>
    </div>
</div>

<!-- 主体 -->
<div id="app">
    <form class="am-form">
        <!-- 配货单信息 -->
        <div class="panel-group" id="accordion_2" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="heading_2_1" >
                    <a role="button" data-toggle="collapse" data-parent="#accordion_2" href="#collapse_2_1" aria-expanded="false" aria-controls="collapse_2_1">
                        <h1 class="panel-title" >
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                            退货单信息
                        </h1>
                    </a>
                </div>
                <div id="collapse_2_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_2_1">
                    <div class="panel-body">
                        <div class="am-form-group">
                            <label for="doc-ipt-email-1">退货单号</label>
                            <input type="text" class="" id="doc-ipt-email-1" placeholder="退货单号" disabled :value="order_num">
                        </div>

                        <div class="am-form-group">
                            <label for="doc-ipt-pwd-1">备注</label>
                            <input type="text" class="" id="doc-ipt-pwd-1" placeholder="请填写备注" v-model="remark">
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
                <tr>
                    <td>款号 / 颜色 / 尺码</td>
                    <td>订单数量</td>
                    <td>已退数量</td>
                    <td>已报数量</td>
                    <td>已配数量</td>
                    <td>退货</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in list" v-bind:class="{ danger: parseInt(item.num) + parseInt(item.num_refund) > item.num_order }"  v-if="item.spu_id.indexOf(filter) != -1 && item.num_order > 0">
                    <td>{{item.spu_id}} / {{item.sku.color}} / {{item.sku.size}}</td>
                    <td>{{item.num_order}}</td>
                    <td>{{parseInt(item.num_refund)}}</td>
                    <td>{{item.num_allocate}}</td>
                    <td>{{item.num_send}}</td>
                    <td><input type="number" class="form-control" placeholder="单价" v-model="item.num" v-on:change="changeNum($event,item,'num')"></td>
                </tr>
                </tbody>
            </table>
        </div>

        <p>
            <button type="button" class="am-btn am-btn-primary" @click="submit">提交</button>
            <a type="button" class="am-btn am-btn-success" href="javascript:window.history.go(-1);">返回</a>
            <button type="button" class="am-btn am-btn-secondary" @click="toOrder">历史退货</button>
        </p>
    </form>
</div>

<script>
    //vue构造器
    var vue = new Vue({
        el:"#app",
        data: {
            //销售单
            id:"",
            order:null,
            user:null,
            remark:"",
            order_num:"",
            filter:"",
            list:[],
        },
        created:function() {
            this.id = "<?=$id?>";
            this.order_num = "<?=$order_num?>";
            this.order = <?=json_encode($order)?>;
            this.list = <?=json_encode($list)?>;
            this.seller = <?=json_encode($seller)?>;
            this.client = <?=json_encode($client)?>;

            //console.log(this.id);
        },
        methods: {
            //添加配货单
            add:function(){
                if(this.order){
                    window.location.href="<?=site_url($_controller->views."/add")?>/"+this.order.id;
                }
            },
            //提交表单
            submit:function(){
                if(!this.check())
                    return;

                //设置数据
                var list = this.getSubmitList();
                var total = this.getTotalNum(list);

                //提交
                $.ajax({
                    url: '<?=site_url($_controller->views . "/submit")?>',
                    type: "post",
                    dataType: "json",
                    data: {
                        "id": this.id,
                        "order_id": this.order.id,
                        "order_num":this.order_num,
                        "remark":this.remark,
                        "total_num":total,
                        "list":list,
                    },
                    success: function (result) {
                        console.log(result);
                        if (result.state.return_code == 0) {
                            location.href = '<?=site_url($_controller->views . "/order/$order->id")?>'
                        }
                        else
                            alert(result.state.return_msg);
                    }
                });
            },
            //获取提交处理
            getSubmitList:function(){
                var list = [];
                for(var key in this.list){
                    var item = JSON.parse(JSON.stringify(this.list[key]));
                    delete item.spu;
                    delete item.sku;
                    list.push(item);
                }
                return list;
            },
            //检测提交的表单
            check:function(){
                var isNull = true;
                var sumNum = 0;//用于统计总数量
                for(var key in this.list){
                    //设置item
                    var item = this.list[key];

                    //判断是否有数量
                    if(parseInt(item.num) > 0)
                        isNull = false;

                    //判断数量是否为正数
                    if(parseInt(item.num) < 0){
                        alert("退货数量有误");
                        return false;
                    }

                    //判断配货数量是否正确
                    if(parseInt(item.num) + parseInt(item.num_refund) > item.num_order){
                        alert("退货数量超过了订单数量");
                        return false;
                    }

                    //累加总配货数
                    sumNum += parseInt(item.num_refund);
                    sumNum += parseInt(item.num);
                }

                //判断是否配货
                if(isNull){
                    alert("请填写退货数量");
                    return false;
                }

                //返回
                return true;
            },
            //数量改变(整数)
            changeNum:function(e,item,attr){
                //取项属性名
                var attr = attr || "num";
                //设置值
                if(parseInt(item[attr]) >= 0)
                    item[attr] = parseInt(item[attr]);
                else
                    item[attr] = 0;
            },
            //获取总数量
            getTotalNum:function(list){
                var total = 0;
                for(var key in list){
                    var item = list[key];
                    total += parseInt(item.num);
                }
                return total;
            },
            //查看历史退货
            toOrder:function () {
                if(this.order){
                    window.location.href="<?=site_url($_controller->views."/order")?>/"+this.order.id;
                }
            }
        }
    })
</script>