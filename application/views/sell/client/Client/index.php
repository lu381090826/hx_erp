<!-- 面包屑 --><div class="am-cf am-padding am-padding-bottom-0">    <select data-am-selected id="subnav">        <option value="<?=site_url("sell/form/Form")?>">销售订单</option>        <option value="#" selected><a>客户管理</a></option>    </select></div><hr><!-- 动作按钮 --><div class="am-g">    <div class="am-u-sm-12 am-u-md-6">        <div class="am-btn-toolbar">            <div class="am-btn-group am-btn-group-xs">                <a type="button" class="am-btn am-btn-default"  href="<?=UrlComponent::create($_controller)?>"><span class="am-icon-plus"></span> 新增</a>            </div>        </div>    </div></div><!-- 列表内容 --><div class="am-g">    <div class="am-u-sm-12">        <form class="am-form">            <!--DataGrid-->            <?=ViewComponent::DataGrid($_controller,$searched,[                'id','name','phone',/*'addr',*/            ])?>            <!--分页条-->            <?=ViewComponent::PagesBar($page,$size,$searched->count)?>            <hr />        </form>    </div></div>