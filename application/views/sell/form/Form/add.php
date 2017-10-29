<!-- 样式 -->
<!--<link rel="stylesheet" href="<?/*=$base_url*/?>/assets/page/css/header.css">-->
<link rel="stylesheet" href="/assets/page/css/add.css">

<!-- 主体 -->
<div id="vue-app" class="form-content">
    <!-- 标题 -->
    <div class="page-header">
        <h3>销售单<small>新订单</small></h3>
    </div>
    <!-- 表单主体 -->
    <div>
        <!-- 员工仓库 -->
        <div class="form-group">
            <label>销售员</label>
            <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading_1_1" >
                        <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapse_1_1" aria-expanded="false" aria-controls="collapse_1_1">
                            <h1 class="panel-title" >
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                                仓库、员工
                            </h1>
                        </a>
                    </div>
                    <div id="collapse_1_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_1_1">
                        <div class="panel-body">
                            <!-- 项目内容 -->
                            <div class="form-group">
                                <label>销售仓库</label>
                                <div class="input-compose">
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="seller.name"></div>
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="seller.id"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>开单员工</label>
                                <div class="input-compose">
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="seller.store.name"></div>
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="seller.store.id"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 客户相关 -->
        <div class="form-group">
            <label>
                销售客户
                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-client-add"><span class="glyphicon glyphicon-plus"></span>添加</button>-->
                <!--<a data-toggle="modal" data-target="#modal-client-add"><span class="glyphicon glyphicon-plus"></span>添加</a>-->
                <!--<a onclick="javascript:$('#modal-client-add').modal({show:true})"><span class="glyphicon glyphicon-plus"></span>添加</a>-->
                <a type="button" data-am-modal="{target: '#modal-client-add', closeViaDimmer: 0}"><span class="glyphicon glyphicon-plus"></span>添加</a>
            </label>
            <div class="input-compose">
                <div><input type="text" class="form-control" placeholder="搜索客户" :value="client_search" v-on:change="clientChange"></div>
                <div><input type="text" class="form-control" placeholder="客户名称/电话" disabled :value="client | client_show"></div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" style="width: 100%" v-for="item in clientList" v-on:click="clientSelect(item)">{{item.name}}({{item.phone}})</button>
            </div>
        </div>
        <!-- 付款方式 -->
        <div class="form-group">
            <label>付款方式</label>
            <select class="form-control" v-model="payment">
                <option v-for="(item, index) in paymentMap" :value="index">{{item}}</option>
            </select>
        </div>
        <!-- 备注信息 -->
        <div class="form-group">
            <label>备注</label>
            <input type="text" class="form-control" placeholder="请填写备注" v-model="remark">
        </div>
        <!-- 搜索商品 -->
        <div class="form-group">
            <label>搜索商品</label>
            <input type="text" class="form-control" placeholder="请输入商品SPU" v-model="searchKey" v-on:change="searchChange">
        </div>
        <!-- 搜索结果 -->
        <div class="form-group">
            <div class="panel-group" id="accordion_6" role="tablist" aria-multiselectable="true">
                <div class="panel panel-primary" v-for="(item,index) in searchList">
                    <div class="panel-heading" role="tab" :id="'heading_6_'+index">
                        <a role="button" data-toggle="collapse" data-parent="#accordion_6" :href="'#collapse_6_'+index" aria-expanded="false" :aria-controls="'collapse_6_'+index">
                            <h1 class="panel-title" >
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                                {{item.spu_id}}
                            </h1>
                        </a>
                    </div>
                    <div :id="'collapse_6_'+index" class="panel-collapse collapse" role="tabpanel" :aria-labelledby="'heading_6_'+index">
                        <div class="panel-body">
                            <div class="sku-content row">
                                <div class="col-xs-3"><img :src="item.pic" alt="" class="img-rounded"></div>
                                <div class="col-xs-3"><label>单价</label></div>
                                <div class="col-xs-6"><input type="number" class="form-control" placeholder="单价" v-model="item.price"></div>
                            </div>
                            <div class="sku-table row">
                                <table class="table table-striped">
                                    <thead>
                                    <td>颜色</td><td>尺码</td><td>数量</td><td>操作</td>
                                    </thead>
                                    <tr v-for="sku in item.skus">
                                        <td>{{sku.color}}</td><td>{{sku.size}}</td><td><input type="number" class="form-control" placeholder="单价" v-model="sku.num"></td><td><a v-on:click="skuDel(item,sku)">删除</a></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="sku-actions">
                                <a v-on:click="selectAdd(item)">加入</a>
                                <a v-on:click="searchClean(item)">清理未填写</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 选择结果 -->
        <div class="form-group">
            <label>订单商品</label>
            <div class="panel-group" id="accordion_7" role="tablist" aria-multiselectable="true">
                <div class="panel panel-primary" v-for="(item,index) in selectList">
                    <div class="panel-heading" role="tab" :id="'heading_7_'+index">
                        <a role="button" data-toggle="collapse" data-parent="#accordion_7" :href="'#collapse_7_'+index" aria-expanded="false" :aria-controls="'collapse_7_'+index">
                            <h1 class="panel-title">
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                                {{item.spu_id}}
                            </h1>
                            <h1 class="panel-title">
                                {{item | sku_total_num}}件*{{item.price}}={{item | sku_total_price}}元
                            </h1>
                        </a>
                    </div>
                    <div :id="'collapse_7_'+index" class="panel-collapse collapse" role="tabpanel" :aria-labelledby="'heading_7_'+index">
                        <div class="panel-body">
                            <div class="sku-content row">
                                <div class="col-xs-3"><img :src="item.pic" alt="" class="img-rounded"></div>
                                <div class="col-xs-3"><label>单价</label></div>
                                <div class="col-xs-6"><input type="number" class="form-control" placeholder="单价" v-model="item.price"></div>
                            </div>
                            <div class="sku-table row">
                                <table class="table table-striped">
                                    <thead>
                                    <td>颜色</td><td>尺码</td><td>数量</td><td>操作</td>
                                    </thead>
                                    <tr v-for="sku in item.skus">
                                        <td>{{sku.color}}</td><td>{{sku.size}}</td><td><input type="number" class="form-control" placeholder="单价" v-model="sku.num"></td><td><a v-on:click="skuDel(item,sku)">删除</a></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 销售总量 -->
        <div class="form-group">
            <label>销售单总数量</label>
            <input id="total_num" type="text" class="form-control" placeholder="" :value="selectList | total_num" disabled>
        </div>
        <!-- 销售总额 -->
        <div class="form-group">
            <label>销售单总额</label>
            <input id="total_price" type="text" class="form-control" placeholder="" :value="selectList | total_price" disabled>
        </div>
        <!-- 提交按钮 -->
        <button class="btn btn-primary from-submit" type="submit" v-on:click="submit()">提交</button>
    </div>

    <!-- 模拟框 (添加用户) -->
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-client-add">
        <div class="am-modal-dialog">
            <div class="modal-header">
                <button type="button" class="close" data-am-modal-close><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加客户</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="输入客户名称" v-model="client_input.name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="输入客户电话" v-model="client_input.phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-am-modal-close>关闭</button>
                <button type="button" class="btn btn-primary" v-on:click="clientAdd">确定</button>
            </div>
        </div>
    </div>
</div>

<!-- 脚本 -->
<script>
    //过滤器--获取总数量
    Vue.filter('total_num',function(list){
        var total = 0;
        if(list.length > 0){
            for(key in list){
                var item = list[key];
                for(key2 in item.skus){
                    var sku = item.skus[key2];
                    total += parseInt(sku.num);
                }
            }
        }
        return total;
    })

    //过滤器--获取总金额
    Vue.filter('total_price',function(list){
        var total = 0;
        if(list.length > 0){
            for(key in list){
                var item = list[key];
                for(key2 in item.skus){
                    var sku = item.skus[key2];
                    total += parseInt(sku.num) * item.price;
                }
            }
        }
        return total;
    })

    //过滤器--获取sku的总数量
    Vue.filter('sku_total_num',function(item){
        var total = 0;
        for(key in item.skus){
            var sku = item.skus[key];
            total += parseInt(sku.num);
        }
        return total;
    })

    //过滤器--获取sku的总价钱
    Vue.filter('sku_total_price',function(item){
        var total = 0;
        for(key in item.skus){
            var sku = item.skus[key];
            total += parseInt(sku.num) * item.price;
        }
        return total;
    })

    //过滤器--拼凑客户信息
    Vue.filter('client_show',function(client){
        if(client.id == "" || client.id == undefined || client.id == null)
            return "";
        else
            return client.name+"("+client.phone+")";
    })

    //vue构造器
    var vue = new Vue({
        el:"#vue-app",
        data: {
            //数据
            "seller":{
                "id":"1000",
                "name":"测试",
                "store":{
                    "id":"1000",
                    "name":"仓库01",
                }
            },
            "payment":0,
            "remark":"",
            //列表
            "paymentMap": <?=json_encode($paymentMap)?>,
            //客户相关
            "client_search":"",
            "client":{
                "id":"",
                "name":"",
                "phone":"",
                "source":null,
            },
            "client_input":{
                "name":"",
                "phone":"",
            },
            "clientList":[],
            //SKU查询相关
            'searchKey':"",
            'searchList':[],
            'selectList':[],
        },
        created:function()
        {
            //console.log(this.seller);
            //console.log(this.paymentMap);
        },
        methods: {
            //搜索
            searchChange:function(e){
                this.searchList = [
                    {
                        "spu_id":"TS100001","pic":"images/597ffd72d627f.JPG","pic_normal":"images/597ffd72d627f.JPG","price":12,
                        "skus":[
                            {"sku_id":"1231","color":"红","size":"F","num":0},
                            {"sku_id":"1232","color":"蓝","size":"F","num":0}
                        ]
                    },
                    {
                        "spu_id":"TS100002","pic":"images/597ffd72d627f.JPG","pic_normal":"images/597ffd72d627f.JPG","price":12,
                        "skus":[
                            {"sku_id":"1233","color":"红","size":"F","num":0},
                            {"sku_id":"1234","color":"蓝","size":"F","num":0}
                        ]
                    }
                ];
            },
            //加入列表
            selectAdd:function(item){
                var clone = JSON.parse(JSON.stringify(item));
                this.selectList.push(clone);
            },
            //清理未填
            searchClean:function(item){
                var list = [];
                for(key in item.skus){
                    var sku = item.skus[key];
                    if(parseInt(sku.num) > 0)
                        list.push(sku);
                }
                item.skus = list;
            },
            //删除SKU项
            skuDel:function(item,sku){
                //获取索引
                var index = this.getIndex(sku,item.skus,"id");
                //删除索引位置项
                item.skus.splice(index,1);
            },
            //获取索引
            getIndex:function(item,list,key){
                //定义索引
                var index = null;
                //获取索引
                for(var i=0;i<list.length;i++){
                    if(list[i][key] == item[key])
                        index = i;
                }
                //返回索引
                return index;
            },
            //客户输入改变
            clientChange:function(e){
                //this
                var _this = this;

                //input
                var input = e;


                console.log(e);

                //Ajax查询
                $.ajax({
                    url: '<?=site_url("sell/client/Client/search_api")?>',
                    type: "get",
                    dataType: "json",
                    data: {
                        "key": this.client_search,
                    },
                    success:function(result){
                        if(result.state.return_code==0){
                            _this.clientList = result.data;
                            console.log(result.data);
                        }
                    }
                });

                //清空搜索内容
                this.client_search = "";
                //情况当前client
                this.client.id = "";
                this.client.name = "";
                this.client.phone = "";
            },
            //选择客户
            clientSelect:function(client){
                //更改查询框内容
                this.client_search = client.id;
                //更改客户信息
                this.client.id = client.id;
                this.client.name = client.name;
                this.client.phone = client.phone;
                this.client.source = client;
                this.clientList = [];
            },
            //添加客户
            clientAdd:function(){
                //this
                var _this = this;
                //Ajax
                $.ajax({
                    url:'<?=site_url("sell/client/Client/add_api")?>',
                    type:"get",
                    dataType:"json",
                    data:{
                        "name":this.client_input.name,
                        "phone":this.client_input.phone
                    },
                    success:function(result) {
                        if(result.state.return_code == 0) {
                            //更改查询框内容
                            _this.client_search = result.data.id;
                            //更改客户信息
                            _this.client.id = result.data.id;
                            _this.client.name = result.data.name;
                            _this.client.phone = result.data.phone;
                            _this.client.source = result.data;
                            _this.clientList = [];
                            //关闭
                            var $modal = $('#modal-client-add');
                            $modal.modal('close');
                        }
                        else if(result.state.return_code == 1) {
                            alert("客户已经存在");
                        }
                        else {
                            alert(result.state.return_msg)
                        }
                        console.log(vue.list);
                    }
                });
            },
            //提交
            submit:function(){
                console.log(this.seller.id);
                console.log(this.client.id);
                console.log(this.payment);
                console.log(this.remark);
                console.log(this.selectList);
                //获取总额
                var total_num = $("#total_num").val();
                var total_price = $("#total_price").val();

                //Ajax
                $.ajax({
                    url:'<?=site_url("sell/form/Form/add_api")?>',
                    type:"get",
                    dataType:"json",
                    data:{
                        "user_id":this.seller.id,
                        "client_id":this.client.id,
                        "payment":this.payment,
                        "remark":this.remark,
                        "selectList":this.selectList,
                        "total_num":total_num,
                        "total_price":total_price,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0) {
                            location.href="<?=site_url("sell/form/Form/index")?>";
                        }
                        else {
                            alert(result.state.return_msg)
                        }
                        console.log(vue.list);
                    }
                });
            }
        }
    })
</script>


