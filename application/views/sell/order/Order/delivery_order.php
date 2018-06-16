<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/form/add.css">
<link rel="stylesheet" href="/assets/plugin/images-input/images-input.css">
<script src="/assets/plugin/images-input/images-input.js"></script>

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a href="<?=site_url("$_controller->views/index")?>"><?=$_controller->describe->desc?></a> /
        <small>直接报货</small>
    </div>
</div>
<hr />

<!-- 主体 -->
<div id="vue-app" class="form-content">
    <!-- 表单主体 -->
    <div>
        <!-- 制单人 -->
        <div class="form-group">
            <label>制单人</label>
            <div><input type="text" class="form-control" placeholder="制单人" disabled v-model="user.name"></div>
        </div>
        <!-- 需求店铺 -->
        <div class="form-group">
            <label>需求店铺</label>
            <div><input type="text" class="form-control" placeholder="制单人" disabled v-model="shop.name"></div>
        </div>
        <!-- 配货方式 -->
        <div class="form-group">
            <label>配货方式</label>
            <select class="form-control" v-model="allocate_mode">
                <option v-for="(item, index) in allocateModeMap" :value="index">{{item}}</option>
            </select>
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
        <div class="form-group" v-if="this.id == '' || spuAllowChange">
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
        <!-- 报货总量 -->
        <div class="form-group">
            <label>报货总量</label>
            <input id="total_num" type="text" class="form-control" placeholder="" :value="selectList | total_num" disabled>
        </div>
        <!-- 报货总额 -->
        <div class="form-group" v-show="false">
            <label>报货总额</label>
            <input id="total_price" type="text" class="form-control" placeholder="" :value="selectList | total_price" disabled>
        </div>
        <!-- 报货总额 -->
        <div class="form-group" v-show="false">
            <label>报货单金额</label>
            <input id="total_price" type="text" class="form-control" placeholder="" v-model="total_amount" disabled v-if="this.id != ''">
            <input id="total_price" type="text" class="form-control" placeholder=""  :value="selectList | total_price" disabled v-else>
        </div>
        <!-- 付款方式 -->
        <!--<div class="form-group">
            <label>付款方式</label>
            <select class="form-control" v-model="payment">
                <option v-for="(item, index) in paymentMap" :value="index">{{item}}</option>
            </select>
        </div>-->
        <!-- 收款日期 -->
        <!--<div class="form-group">
            <label>收款日期</label>
            <input type="date" class="form-control" placeholder="收款日期" v-model="receipt_date">
        </div>-->
        <!-- 提交按钮 -->
        <button class="btn btn-lg btn-primary from-submit" type="submit" v-on:click="submit()">提交</button>
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
            //是否允许修改SPI
            'spuAllowChange':1,
            //是否为旧单
            'isNew':1,
            //店铺信息
            'shop':<?=json_encode($shop)?>,
        },
        created:function() {
            //this
            var _this = this;

            //获取当前用户信息
            if("<?=$model->user_id?>" == ""){
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
            }
            else{
                $.ajax({
                    url:'<?=site_url($_controller->api."/user")?>',
                    data:{
                        "id":"<?=$model->user_id?>"
                    },
                    async: false,
                    type:"post",
                    dataType:"json",
                    success:function(result) {
                        if(result.state.return_code == 0)
                            _this.user = result.data
                    }
                });
            }

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
            //是否允许修改spu
            this.spuAllowChange = <?=$spuAllowChange?>;
            this.isNew = <?=$isNew?>;

            //覆盖收款方式和地址
            if(this.client != null) {
                this.client.delivery_type = <?=$model->delivery_type?$model->delivery_type:0?>;
                this.client.delivery_addr = '<?=$model->delivery_addr?>';
            }

            //搜索值修正
            if(this.client)
                this.clientKey = this.client.id;

            //禁止未关联店铺用户新建
            if(this.id == "" && this.shop == null){
                alert("请先关联所属店铺");
                history.go(-1);
            }
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
                    async : false,
                    data: {
                        "key": input_value,
                    },
                    success:function(result){
                        if(result.state.return_code == 0){
                            result.data = _this.addFilter(result.data);
                            _this.searchList = result.data;
                            //console.log(_this.searchList);
                        }
                    },
                    complete:function(){
                        _this.searchKey = '';
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
                    url:'<?=site_url($_controller->controller."/delivery_create_submit")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        "id":this.id,
                        "order_num":this.order_num,
                        "user_id":this.user.uid,
                        "shop_id":this.shop?this.shop.id:null,
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
                            location.href="<?=site_url("sell/order/Order/deliverys")?>";
                        }
                        else {
                            alert(result.state.return_msg)
                        }
                    }
                });
            },
            //检测
            check:function(){
                //总报货额
                var total_price = $("#total_price").val();

                if(this.selectList.length == 0){
                    alert("报货单不能为空");
                    return false;
                }
                //判断收货类型为快递时，是否有收货地址
                /*else if(this.client.delivery_type == 0 && this.client.delivery_addr == ""){
                    alert("请填写客户收货地址");
                    return false;
                }*/
                //判断修改订单不能超过订单金额
                /*else if(this.id !="" && parseInt(this.total_amount) < parseInt(total_price)){
                    alert("修改订单不能超过订单金额");
                    return false;
                }*/
                //判断修改订单不能超过订单金额,如果是新单则可随意修改
                /*else if(!this.isNew){
                    if(this.id !="" && parseInt(this.total_amount) < parseInt(total_price)){
                        alert("修改订单不能超过订单金额");
                        return false;
                    }
                    return false;
                }*/
                //修改时触发
                /*if(this.id !=""){
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
                }*/

                //不允许未关联店铺的人下单
                if(this.id == "" && this.shop == null){
                    alert("请先关联所属店铺");
                    return false;
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
                //设置值
                if(parseFloat(item[attr]) >= 0)
                    item[attr]=parseFloat(item[attr]);
                else
                    item[attr] = 0;
            },
        }
    })
</script>