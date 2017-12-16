<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/form/add.css">
<link rel="stylesheet" href="/assets/plugin/images-input/images-input.css">
<script src="/assets/plugin/images-input/images-input.js"></script>

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a href="<?=site_url("$_controller->views/index")?>"><?=$_controller->describe->desc?></a> /
        <small>订单编辑</small>
    </div>
</div>
<hr />

<!-- 主体 -->
<div id="vue-app" class="form-content">
    <!-- 表单主体 -->
    <div>
        <!-- 销售单号 -->
        <div class="form-group">
            <label>销售单号</label>
            <input type="text" class="form-control" placeholder="销售单号" v-model="order_num" disabled>
        </div>
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
                            <div class="form-group" v-if="user.shop_info">
                                <label>销售仓库</label>
                                <div class="input-compose">
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="user.shop_info[0].name"></div>
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="user.shop_info[0].id"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>开单员工</label>
                                <div class="input-compose">
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="user.name"></div>
                                    <div><input type="text" class="form-control" placeholder="Amount" disabled v-model="user.uid"></div>
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
            <div class="panel-group" id="accordion_client" role="tablist" aria-multiselectable="true">
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading_client_1" >
                        <a role="button" data-toggle="collapse" data-parent="#accordion_client" href="#collapse_client_1" aria-expanded="true" aria-controls="collapse_client_1">
                            <h1 class="panel-title" >
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                                销售客户：
                                <span v-if="client != null">{{client.name}}</span>
                                <span v-else>未设置</span>
                            </h1>
                        </a>
                    </div>
                    <div id="collapse_client_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_client_1">
                        <div class="panel-body">
                            <!-- 客户搜索 -->
                            <div class="form-group">
                                <label>搜索客户</label>
                                <input type="text" class="form-control" placeholder="搜索客户" :value="clientKey" v-on:change="clientChange">
                            </div>

                            <!-- 客户信息 -->
                            <div v-if="client != null">
                                <!-- 客户名称 -->
                                <div class="form-group">
                                    <label>客户名称</label>
                                    <div class="input-compose">
                                        <div><input type="text" class="form-control" placeholder="客户名称" v-model="client.name"></div>
                                        <div><input type="text" class="form-control" placeholder="客户ID" v-model="client.id" disabled></div>
                                    </div>
                                </div>

                                <!-- 联系电话 -->
                                <div class="form-group">
                                    <label>联系电话</label>
                                    <input type="text" class="form-control" placeholder="联系电话" v-model="client.phone">
                                </div>

                                <!-- 收货方式 -->
                                <div class="form-group">
                                    <label>收货方式</label>
                                    <div class="input-compose">
                                        <select class="form-control" v-model="client.delivery_type">
                                            <option v-for="(item, index) in deliveryTypeMap" :value="index">{{item}}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 收货方式 -->
                                <div class="form-group" v-if="client.delivery_type == 0">
                                    <label>收货地址</label>
                                    <input type="text" class="form-control" placeholder="收货地址" v-model="client.delivery_addr">
                                </div>

                                <!-- 保存按钮 -->
                                <!--<button type="button" class="btn btn-primary" v-on:click="clientSave()">保存信息</button>-->
                            </div>

                            <!-- 查询结果 -->
                            <div class="form-group"></div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" style="width: 100%" v-for="item in clientList" v-on:click="clientSelect(item)">{{item.name}}({{item.phone}})</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 备注信息 -->
        <div class="form-group">
            <label>备注</label>
            <input type="text" class="form-control" placeholder="请填写备注" v-model="remark">
        </div>
        <!-- 照片备注 -->
        <div class="form-group">
            <label>照片备注</label>
            <div id="remark_images" name="remark_images" path="<?=site_url($_controller->api."/upload_base64")?>" :value='remark_images'></div>
        </div>
        <!-- 搜索商品 -->
        <div class="form-group" v-if="this.id == ''">
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
                                <div class="col-xs-3"><img :src="item.snap_pic" alt="" class="img-rounded"></div>
                                <div class="col-xs-3"><label>单价</label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" placeholder="单价" v-bind:value="item.snap_price" disabled></div>
                            </div>
                            <div class="sku-table row">
                                <!-- 颜色过滤 -->
                                <div class="input-group">
                                    <span class="input-group-addon" id="sizing-addon2"><i class="am-icon-search"></i></span>
                                    <input type="text" class="form-control" placeholder="查询颜色" v-model="item.filter">
                                </div>
                                <!-- SKU表 -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td>颜色</td>
                                            <td>尺码</td>
                                            <td>库存</td>
                                            <td>数量</td>
                                            <!--<td>操作</td>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="sku in item.skus" v-if="sku.color.indexOf(item.filter) != -1">
                                            <td>{{sku.color}}</td>
                                            <td>{{sku.size}}</td>
                                            <td>0</td>
                                            <td><input type="number" class="form-control" placeholder="数量" v-model="sku.num" v-on:change="changeNum($event,sku,'num')"></td>
                                            <!--<td><a v-on:click="skuDel(item,sku)">删除</a></td>-->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sku-actions">
                                <a v-on:click="selectAdd(item)">加入</a>
                                <!--<a v-on:click="searchClean(item)">清理未填写</a>-->
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
                                {{item | sku_total_num}}件*{{item.snap_price}}={{item | sku_total_price}}元
                            </h1>
                        </a>
                    </div>
                    <div :id="'collapse_7_'+index" class="panel-collapse collapse" role="tabpanel" :aria-labelledby="'heading_7_'+index">
                        <div class="panel-body">
                            <div class="sku-content row">
                                <div class="col-xs-3"><img :src="item.snap_pic" alt="" class="img-rounded"></div>
                                <div class="col-xs-3"><label>单价</label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" placeholder="单价" v-model="item.snap_price" v-on:change="changeFloat($event,item,'snap_price')"></div>
                            </div>
                            <div class="sku-table row">
                                <!-- 颜色过滤 -->
                                <div class="input-group">
                                    <span class="input-group-addon" id="sizing-addon2"><i class="am-icon-search"></i></span>
                                    <input type="text" class="form-control" placeholder="查询颜色" v-model="item.filter">
                                </div>
                                <!-- SKU表 -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td>颜色</td><td>尺码</td><td>库存</td>
                                            <td>已请求配货数量</td>
                                            <td>数量</td><!--<td>操作</td>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="sku in item.skus" v-if="sku.color.indexOf(item.filter) != -1" v-bind:class="{ danger: parseInt(sku.num) < parseInt(sku.num_allocat)}">
                                            <td>{{sku.color}}</td>
                                            <td>{{sku.size}}</td>
                                            <td>0</td>
                                            <td>{{sku.num_allocat}}</td>
                                            <td><input type="number" class="form-control" placeholder="数量" v-model="sku.num" v-on:change="changeNum($event,sku,'num')"></td><!--<td><a v-on:click="skuDel(item,sku)">删除</a></td>-->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 销售总量 -->
        <div class="form-group">
            <label>销售总量</label>
            <input id="total_num" type="text" class="form-control" placeholder="" :value="selectList | total_num" disabled>
        </div>
        <!-- 销售总额 -->
        <div class="form-group">
            <label>销售总额</label>
            <input id="total_price" type="text" class="form-control" placeholder="" :value="selectList | total_price" disabled>
        </div>
        <!-- 销售总额 -->
        <div class="form-group">
            <label>销售单金额</label>
            <input id="total_price" type="text" class="form-control" placeholder="" v-model="total_amount" disabled v-if="this.id != ''">
            <input id="total_price" type="text" class="form-control" placeholder=""  :value="selectList | total_price" disabled v-else>
        </div>
        <!-- 配货方式 -->
        <div class="form-group">
            <label>配货方式</label>
            <select class="form-control" v-model="allocate_mode">
                <option v-for="(item, index) in allocateModeMap" :value="index">{{item}}</option>
            </select>
        </div>
        <!-- 付款方式 -->
        <div class="form-group">
            <label>付款方式</label>
            <select class="form-control" v-model="payment">
                <option v-for="(item, index) in paymentMap" :value="index">{{item}}</option>
            </select>
        </div>
        <!-- 收款日期 -->
        <div class="form-group">
            <label>收款日期</label>
            <input type="date" class="form-control" placeholder="收款日期" v-model="receipt_date">
        </div>
        <!-- 提交按钮 -->
        <button class="btn btn-lg btn-primary from-submit" type="submit" v-on:click="submit()">提交</button>
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

<!-- 页脚占位 -->
<div style="width: 100%;height: 32px"></div>

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
                    total += parseInt(sku.num) * item.snap_price;
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
            total += parseInt(sku.num) * item.snap_price;
        }
        return total;
    })

    //过滤器--拼凑客户信息
    Vue.filter('client_show',function(client){
        if(client.id == null)
            return "";
        else
            return client.name+"("+client.phone+")";
    })

    //vue构造器
    var vue = new Vue({
        el:"#vue-app",
        data: {
            //数据
            "id":'<?=$model->id?>',
            'order_num':'',
            "user":null,
            "client":null,
            "payment":"0",
            'receipt_date':"",
            "remark":"",
            "remark_images":[],
            'selectList':[],
            "allocate_mode":"0",
            //搜索、输入、映射
            "clientKey":"",
            "clientList":[],
            'searchKey':"",
            'searchList':[],
            "client_input":{
                "name":"",
                "phone":"",
            },
            "paymentMap": <?=json_encode($paymentMap)?>,
            "deliveryTypeMap": <?=json_encode($deliveryTypeMap)?>,
            "allocateModeMap": <?=json_encode($allocateModeMap)?>,
            //订单金额
            'total_amount':0,
        },
        created:function() {
            //this
            var _this = this;

            //获取当前用户信息
            $.ajax({
                url:'<?=site_url($_controller->api."/now_user")?>',
                async: false,
                type:"post",
                dataType:"json",
                success:function(result) {
                    if(result.state.return_code == 0)
                        _this.user = result.data
                }
            });

            //载入数据
            this.order_num = '<?=$model->order_num?>';
            this.client = <?=json_encode($model->client)?>;
            this.remark = '<?=$model->remark?>';
            this.payment = '<?=$model->payment?>';
            this.receipt_date = '<?=$model->receipt_date?>';
            this.allocate_mode = '<?=$model->allocate_mode?>';
            //添加过滤字段并设置selectList
            var selectList = <?=json_encode($model->goods)?>;
            this.selectList = this.addFilter(selectList);
            this.total_amount = '<?=$model->total_amount?$model->total_amount:"0"?>';
            this.remark_images = '<?=$model->remark_images?>';

            //覆盖收款方式和地址
            if(this.client != null) {
                this.client.delivery_type = <?=$model->delivery_type?$model->delivery_type:0?>;
                this.client.delivery_addr = '<?=$model->delivery_addr?>';
            }

            //搜索值修正
            if(this.client)
                this.clientKey = this.client.id;
        },
        mounted:function(){
            //构建插件
            $("#remark_images").imagesInput({});
        },
        methods: {
            //搜索
            searchChange:function(e){
                //取值和This
                var _this = this;
                var input_value = e.target.value;

                //Ajax查询
                $.ajax({
                    url: '<?=site_url($_controller->api."/search_goods")?>',
                    type: "get",
                    dataType: "json",
                    data: {
                        "key": input_value,
                    },
                    success:function(result){
                        if(result.state.return_code == 0){
                            result.data = _this.addFilter(result.data);
                            _this.searchList = result.data;
                            //console.log(_this.searchList);
                        }
                    }
                });
            },
            //加入列表
            selectAdd:function(item){
                //遍历已选
                var isNew = true;
                for(key in this.selectList){
                    var select = this.selectList[key];
                    if(item.spu_id == select.spu_id){
                        //遍历添加SKU
                        for(key_select in select.skus){
                            var sku_select = select.skus[key_select];
                            for(key_item in item.skus){
                                var sku_item = item.skus[key_item];
                                if(sku_select.sku_id == sku_item.sku_id){
                                    //合并项目
                                    sku_select.num = parseInt(sku_select.num) + parseInt(sku_item.num);
                                }
                            }
                        }
                        //设置非新项
                        isNew = false;
                    }
                }

                //如果没有合并项，则克隆然后添加
                if(isNew){
                    //克隆然后添加
                    var clone = JSON.parse(JSON.stringify(item));
                    this.selectList.push(clone);
                }

                //在搜索表中移除
                this.searchDel(item);
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
            //删除搜索项
            searchDel:function(item){
                //获取索引
                var index = this.getIndex(item,this.searchList);
                //删除索引位置项
                this.searchList.splice(index,1);
            },
            //删除SKU项
            skuDel:function(item,sku){
                //获取索引
                var index = this.getIndex(sku,item.skus);
                //删除索引位置项
                item.skus.splice(index,1);
            },
            //获取索引
            getIndex:function(item,list){
                //定义索引
                var index = null;
                //获取索引
                for(var i=0;i<list.length;i++){
                    if(list[i] == item)
                        index = i;
                }
                //返回索引
                return index;
            },
            //客户输入改变
            clientChange:function(e){
                //取值和This
                var _this = this;
                var input_value = e.target.value;

                //Ajax查询
                $.ajax({
                    url: '<?=site_url($_controller->api."/search_client")?>',
                    type: "get",
                    dataType: "json",
                    data: {
                        "key": input_value,
                    },
                    success:function(result){
                        if(result.state.return_code==0){
                            _this.clientList = result.data;
                        }
                    }
                });

                //清空搜索内容
                this.clientKey = "";
                //情况当前client
                this.client = null;
            },
            //选择客户
            clientSelect:function(client){
                //更改查询框内容
                this.clientKey = client.name;
                //更改客户
                this.client = client;
                this.clientList = [];
            },
            //添加客户
            clientAdd:function(){
                //this
                var _this = this;
                //Ajax
                $.ajax({
                    url:'<?=site_url($_controller->api."/add_client")?>',
                    type:"get",
                    dataType:"json",
                    data:{
                        "name":this.client_input.name,
                        "phone":this.client_input.phone,
                        "addr":"",
                        "delivery_type":0,
                        "delivery_addr":"",
                    },
                    success:function(result) {
                        if(result.state.return_code == 0) {
                            //更改查询框内容
                            _this.clientKey = result.data.name;
                            //更改客户信息
                            _this.client = result.data;
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
                    }
                });
            },
            //保存用户信息
            clientSave:function(){
                //判断是否有选择用户
                if(this.client == null)
                    return;

                //调用API保存
                $.ajax({
                    url:'<?=site_url($_controller->api."/save_client")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        "id":this.client.id,
                        "name":this.client.name,
                        "phone":this.client.phone,
                        "addr":this.client.addr,
                        "delivery_type":this.client.delivery_type,
                        "delivery_addr":this.client.delivery_addr,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0)
                            alert("保存成功");
                        else
                            alert("保存失败");
                    }
                });
            },
            //提交
            submit:function(){
                //检测
                if(!this.check())
                    return;

                //获取总额
                var total_num = $("#total_num").val();
                var total_price = $("#total_price").val();
                var remark_images = $("input[name='remark_images']").val();

                //Ajax
                $.ajax({
                    url:'<?=site_url($_controller->controller."/update_asyn")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        "id":this.id,
                        "order_num":this.order_num,
                        "user_id":this.user.uid,
                        "client_id":this.client.id,
                        "delivery_type":this.client.delivery_type,
                        "delivery_addr":this.client.delivery_addr,
                        "receipt_date":this.receipt_date,
                        "payment":this.payment,
                        "remark":this.remark,
                        "selectList":this.selectList,
                        "total_num":total_num,
                        "total_price":total_price,
                        "client":this.client,           //客户最新信息
                        "allocate_mode":this.allocate_mode,
                        "remark_images":remark_images,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0) {
                            location.href="<?=site_url("sell/order/Order/index")?>";
                        }
                        else {
                            alert(result.state.return_msg)
                        }
                    }
                });
            },
            //检测
            check:function(){
                //总销售额
                var total_price = $("#total_price").val();

                if(this.client == null){
                    alert("请选择客户");
                    return false;
                }
                else if(this.selectList.length == 0){
                    alert("销售单不能为空");
                    return false;
                }
                //判断收货类型为快递时，是否有收货地址
                /*else if(this.client.delivery_type == 0 && this.client.delivery_addr == ""){
                    alert("请填写客户收货地址");
                    return false;
                }*/
                else if(this.id !="" && parseInt(this.total_amount) < parseInt(total_price)){
                    alert("修改订单不能超过订单金额");
                    return false;
                }

                //修改是触发
                if(this.id !=""){
                    for(var key_spu in this.selectList){
                        var spu = this.selectList[key_spu];
                        for(var key_sku in spu.skus) {
                            var sku = spu.skus[key_sku];
                            if (parseInt(sku.num) < parseInt(sku.num_allocat)) {
                                alert("款号："+spu.spu_id+"，存在不符合配货条件项");
                                return false;
                            }
                        }
                    }
                }

                return true;
            },
            //添加过滤字段
            addFilter:function(list){
                for(key in list){
                    var item = list[key];
                    item.filter = "";
                }
                return list;
            },
            //数量改变(整数)
            changeNum:function(e,item,attr){
                //取项属性名
                var attr = attr || "num";
                //设置值
                if(parseInt(item[attr]) >= 0)
                    item[attr]=parseInt(item[attr]);
                else
                    item[attr] = 0;
            },
            //数量改变(浮点数)
            changeFloat:function(e,item,attr){
                //取项属性名
                var attr = attr || "num";
                console.log(attr);
                //设置值
                if(parseFloat(item[attr]) >= 0)
                    item[attr]=parseFloat(item[attr]);
                else
                    item[attr] = 0;
            },
        }
    })
</script>



