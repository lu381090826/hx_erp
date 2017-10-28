<form>
    <!-- 面包屑 -->
    <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf">
            <a class="am-text-primary am-text-lg" href="<?=base_url()?>">HOME</a> /
            <a href="<?=site_url("$_controller->views/index")?>"><?=$_controller->describe->desc?></a> /
            <small>添加</small>
        </div>
    </div>
    <hr />
    <!-- 表单内容-->
    <div class="am-tabs am-margin" data-am-tabs="">
        <!-- 导航 -->
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">基本信息</a></li>
        </ul>
        <!-- 内容 -->
        <div class="am-tabs-bd">
            <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                <div class="am-form">
                    <?php $this->load->view("$_controller->views/_form",["model"=>$model]); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- 动作按钮 -->
    <div class="am-margin">
        <button type="submit" class="am-btn am-btn-primary am-btn-xs">提交保存</button>
    </div>
</form>    