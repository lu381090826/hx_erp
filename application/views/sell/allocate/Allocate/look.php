<!-- 样式 -->
<link rel="stylesheet" href="/assets/page/allocate/common.css">

<!-- 面包屑 -->
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf">
        <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/form/Form")?>">销售订单</a> /
        <a class="am-text-primary am-text-lg" href="<?=site_url("/sell/allocate/Allocate/index")."/$form->id"?>">配货订单</a> /
        <small>查看配货</small>
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
            <input type="text" class="" id="doc-ipt-email-1" placeholder="配货单号" disabled :value="allocate.order_num">
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-1">备注</label>
            <input type="text" class="" id="doc-ipt-pwd-1" placeholder="请填写备注" disabled :value="allocate.remark">
        </div>

        <div class="am-form-group">
            <table class="table table-striped">
                <thead>
                <td>款号</td><td>颜色</td><td>尺码</td><td>需求数量</td><td>配货数量</td>
                </thead>
                <tr v-for="item in list">
                    <td>{{item.spu_id}}</td>
                    <td>{{item.color}}</td>
                    <td>{{item.size}}</td>
                    <td>{{item.num_sum}}</td>
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
            allocate:null,
            list:[],
        },
        created:function()
        {
            this.form = <?=json_encode($form)?>;
            this.allocate = <?=json_encode($allocate)?>;
            this.list = <?=json_encode($list)?>;

            console.log(this.list);
        },
        methods: {

        }
    })
</script>