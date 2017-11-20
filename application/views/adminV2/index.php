<?php $this->load->view('adminV2/head'); ?>
<!-- 内容区域 -->
<div class="tpl-content-wrapper">
    <div class="row-content am-cf">
        <div class="row  am-cf">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-4">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl"><?= date("H-m") ?>销售单数</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body am-fr">
                        <div class="am-fl">
                            <div class="widget-fluctuation-period-text">
                                100
                                <button class="widget-fluctuation-tpl-btn">
                                    <i class="am-icon-calendar"></i>
                                    更多月份
                                </button>
                            </div>
                        </div>
                        <div class="am-fr am-cf">
                            <div class="widget-fluctuation-description-amount text-success" am-cf>
                                +1

                            </div>
                            <div class="widget-fluctuation-description-text am-text-right">
                                8月份新增
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
                <div class="widget widget-primary am-cf">
                    <div class="widget-statistic-header">
                        总商品数
                    </div>
                    <div class="widget-statistic-body">
                        <div class="widget-statistic-value">
                            100
                        </div>
                        <div class="widget-statistic-description">
                        </div>
                        <span class="widget-statistic-icon am-icon-credit-card-alt"></span>
                    </div>
                </div>
            </div>
            <div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
                <div class="widget widget-purple am-cf">
                    <div class="widget-statistic-header">
                        总sku数
                    </div>
                    <div class="widget-statistic-body">
                        <div class="widget-statistic-value">
                            200
                        </div>
                        <div class="widget-statistic-description">
                        </div>
                        <span class="widget-statistic-icon am-icon-support"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row am-cf">
            <div class="am-u-sm-12 am-u-md-8">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">月度下单量</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body-md widget-body tpl-amendment-echarts am-fr" id="tpl-echarts">

                    </div>
                </div>
            </div>

            <div class="am-u-sm-12 am-u-md-4">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">专用服务器负载</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body widget-body-md am-fr">

                        <div class="am-progress-title">CPU Load <span
                                class="am-fr am-progress-title-more">28% / 100%</span>
                        </div>
                        <div class="am-progress">
                            <div class="am-progress-bar" style="width: 15%"></div>
                        </div>
                        <div class="am-progress-title">CPU Load <span
                                class="am-fr am-progress-title-more">28% / 100%</span>
                        </div>
                        <div class="am-progress">
                            <div class="am-progress-bar  am-progress-bar-warning" style="width: 75%"></div>
                        </div>
                        <div class="am-progress-title">CPU Load <span
                                class="am-fr am-progress-title-more">28% / 100%</span>
                        </div>
                        <div class="am-progress">
                            <div class="am-progress-bar am-progress-bar-danger" style="width: 35%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row am-cf">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-4 widget-margin-bottom-lg ">
                <div class="tpl-user-card am-text-center widget-body-lg">
                    <div class="tpl-user-card-title">
                        小张
                    </div>
                    <div class="achievement-subheading">
                        月度最佳销售
                    </div>
                    <img class="achievement-image" src="/assetsV2/img/user07.png" alt="">
                    <div class="achievement-description">
                        小张在
                        <strong>30天内</strong> 下了
                        <strong>100</strong>单。
                    </div>
                </div>
            </div>

            <div class="am-u-sm-12 am-u-md-12 am-u-lg-8 widget-margin-bottom-lg">

                <div class="widget am-cf widget-body-lg">

                    <div class="widget-body  am-fr">
                        <div class="am-scrollable-horizontal ">
                            <table width="100%" class="am-table am-table-compact am-text-nowrap tpl-table-black "
                                   id="example-r">
                                <thead>
                                <tr>
                                    <th>文章标题</th>
                                    <th>作者</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="gradeX">
                                    <td>优化建议</td>
                                    <td>小刘</td>
                                    <td>2017-11-17</td>
                                    <td>
                                        <div class="tpl-table-black-operation">
                                            <a href="javascript:;">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>
                                            <a href="javascript:;" class="tpl-table-black-operation-del">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- more data -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('adminV2/foot');?>
<script src="/assetsV2/js/chart.js"></script>

