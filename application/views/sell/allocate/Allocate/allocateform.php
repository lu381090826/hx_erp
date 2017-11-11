<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/allocate/common.css">

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/form/Form")?>">销售订单</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/allocate/Allocate/index")."/$form->id"?>">配货订单</a> /
        <small>添加配货</small>
    </div>
</div>

<!-- 主体 -->
<div id="app">
    <form class="am-form">
        <div class="am-form-group">
            <label for="doc-ipt-email-1">销售单号</label>
            <input type="text" class="" id="doc-ipt-email-1" placeholder="销售单号" disabled :value="form.order_num">
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-email-1">配货单号</label>
            <input type="text" class="" id="doc-ipt-email-1" placeholder="配货单号" disabled :value="order_num">
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-1">备注</label>
            <input type="text" class="" id="doc-ipt-pwd-1" placeholder="请填写备注" v-model="remark">
        </div>

        <div class="am-form-group">
            <table class="table table-striped">
                <thead>
                <td>款号</td><td>颜色</td><td>尺码</td><td>需求数量</td><td>已配数量</td><td>配货数量</td>
                </thead>
                <tr v-for="item in list" v-bind:class="{ danger: parseInt(item.num) + parseInt(item.num_end) > item.num_sum }">
                    <td>{{item.spu_id}}</td>
                    <td>{{item.sku.color}}</td>
                    <td>{{item.sku.size}}</td>
                    <td>{{item.num_sum}}</td>
                    <td>{{item.num_end}}</td>
                    <td><input type="number" class="form-control" placeholder="单价" v-model="item.num"></td>
                </tr>
            </table>
        </div>

        <p><button type="button" class="am-btn am-btn-primary" @click="submit">提交</button></p>
    </form>
</div>

<script>
    //vue构造器
    var vue = new Vue({
        el:"#app",
        data: {
            //销售单
            form:null,
            user:null,
            remark:"",
            order_num:"",
            list:[],
        },
        created:function()
        {
            this.form = <?=json_encode($form)?>;
            this.list = <?=json_encode($list)?>;
            this.order_num = "<?=$order_num?>";

            console.log(this.list);
        },
        methods: {
            //添加配货单
            add:function(){
                if(this.form){
                    window.location.href="<?=site_url($_controller->views."/add")?>/"+this.form.id;
                }
            },
            //提交表单
            submit:function(){
                if(!this.check())
                    return;

                $.ajax({
                    url: '<?=site_url($_controller->views . "/add_api")?>',
                    type: "post",
                    dataType: "json",
                    data: {
                        "form_id": this.form.id,
                        "order_num":this.order_num,
                        "remark":this.remark,
                        "list":this.getSubmitList(),
                    },
                    success: function (result) {
                        console.log(result);
                        if (result.state.return_code == 0) {
                            location.href = '<?=site_url($_controller->views . "/index/$form->id")?>'
                        }
                        else
                            alert(item.state.return_msg);
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
                    delete item.num_sum;
                    delete item.num_end;
                    list.push(item);
                }
                return list;
            },
            //检测提交的表单
            check:function(){
                var isNull = true;
                for(var key in this.list){
                    //设置item
                    var item = this.list[key];

                    //判断是否有数量
                    if(parseInt(item.num) > 0)
                        isNull = false;

                    //判断数量是否为正数
                    if(parseInt(item.num) < 0){
                        alert("配货数量有误");
                        return false;
                    }

                    //判断配货数量是否正确
                    if(parseInt(item.num) + parseInt(item.num_end) > item.num_sum){
                        alert("配货超过了订单需求");
                        return false;
                    }
                }

                if(isNull){
                    alert("请进行配货");
                    return false;
                }

                return true;
            }
        }
    })
</script>