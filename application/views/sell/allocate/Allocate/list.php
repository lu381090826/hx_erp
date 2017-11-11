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
        <div class="am-form-group">
            <label for="doc-ipt-email-1">销售单号</label>
            <input type="text" class="" id="doc-ipt-email-1" placeholder="销售单号" disabled :value="form.order_num" v-if="form">
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-1">销售员</label>
            <input type="text" class="" id="doc-ipt-pwd-1" placeholder="销售员" disabled :value="form.create_user_id" v-if="form">
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-2">销售日期</label>
            <input type="text" class="" id="doc-ipt-pwd-2" placeholder="销售日期" disabled :value="form.create_date" v-if="form">
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

            console.log(this.list);
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