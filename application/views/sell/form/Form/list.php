<!-- 样式 -->
<!--<link rel="stylesheet" href="<?/*=$base_url*/?>/assets/page/css/header.css">-->
<link rel="stylesheet" href="/assets/page/css/list.css">

<!-- 主体 -->
<div id="vue-app" class="form-content">
    <!-- 标题 -->
    <div class="page-header">
        <h3>销售单<small>今日开单</small></h3>
    </div>
    <!-- 新开单 -->
    <a class="btn-add" href="<?=site_url($_controller->views."/add")?>"><span class="glyphicon glyphicon-plus"></span>新开单</a></label>
    <!-- 搜索框 -->
    <div class="panel panel-primary panel-search">
        <div class="panel-heading">
            <h3 class="panel-title">查找信息</h3>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <td class="title">开始日期</td>
                    <td class="item"><input type="date" class="form-control" placeholder="开始日期" v-model="start_date"></td>
                </tr>
                <tr>
                    <td class="title">结束日期</td>
                    <td class="item"><input type="date" class="form-control" placeholder="结束日期" v-model="end_date"></td>
                </tr>
            </table>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary btn-lg btn-search" type="submit" v-on:click="search()">查找</button>
        </div>
    </div>
    <!-- 过滤 -->
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
        <input type="text" class="form-control" placeholder="搜索" aria-describedby="" v-model="filter">
    </div>
    <!-- 订单 -->
    <div class="order-list">
        <div class="item" v-for="item in list" v-if="item.create_user_id.indexOf(filter) != -1 || item.total_price.indexOf(filter) != -1">
            <div class="body" v-on:click="update(item)">
                <div class="title">
                    <div class="client">{{item.client.id}}</div>
                    <div class="tag">{{item.status_name}}</div>
                </div>
                <div>{{item.date}}</div>
                <div>开单人：{{item.create_user_id}}</div>
                <div>销售数量：{{item.total_num}}  销售金额：{{item.total_price}}</div>
            </div>
            <div class="icon">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</div>

<script>
    //vue构造器
    var vue = new Vue({
        el:"#vue-app",
        data: {
            //列表
            "list":[],
            "start_date":"<?=date("Y-m-d",time())?>",
            "end_date":"<?=date("Y-m-d",time())?>",
            "filter":"",
        },
        created:function() {
            this.search();
        },
        methods: {
            //修改订单
            update:function(order){
                if(order.canUpdate){
                    window.location.href="update.html";
                }
            },
            //查询订单
            search:function(){
                //参数
                var start_date = this.start_date;
                var end_date = this.end_date;

                //Ajax
                $.ajax({
                    url:'<?=site_url("sell/form/Form/search_api")?>',
                    type:"post",
                    dataType:"json",
                    data:{
                        "start_date":start_date,
                        "end_date":end_date,
                    },
                    success:function(result) {
                        if(result.state.return_code == 0)
                            vue.list = result.data;
                        //console.log(vue.list);
                    }
                });
            }
        }
    })
</script>