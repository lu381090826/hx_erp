<?php
$nav = array(
    /*1 => array(
        'title' => 'GII',
        'icon' => 'am-icon-university',
        'auth' => '',
        'items' => array(
            1 => array('title' => "GII", 'url' => site_url("/gii/main"), "icon"=>"", 'auth' => ''),
        )
    ),*/
    2 => array(
        'title' => '销售模块',
        'icon' => 'am-icon-file',
        'auth' => '',
        'items' => array(
            1 => array('title' => "销售订单", 'url' => site_url("/sell/form/form"), "icon"=>"", 'auth' => ''),
            2 => array('title' => "客户列表", 'url' => site_url("/sell/client/Client"), "icon"=>"", 'auth' => ''),
        )
    ),
)
?>

<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
        <ul class="am-list admin-sidebar-list">
            <li><a href="<?=$base_url?>"><span class="am-icon-home"></span> 首页</a></li>
            <?php foreach($nav as $key=>$item):?>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav-<?=$key?>'}"><span class="<?=$item["icon"]?>"></span> <?=$item["title"]?> <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-<?=$key?>">
                        <?php foreach($item["items"] as $item2):?>
                            <li><a href="<?=$item2["url"]?>" class="am-cf"><span class="<?=$item2["icon"]?>"></span> <?=$item2["title"]?></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
            <?php endforeach;?>


            <!--<li><a href="admin-index.html"><span class="am-icon-home"></span> 首页</a></li>
            <li class="admin-parent">
                <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span> 页面模块 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
                    <li><a href="admin-user.html" class="am-cf"><span class="am-icon-check"></span> 个人资料<span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
                    <li><a href="admin-help.html"><span class="am-icon-puzzle-piece"></span> 帮助页</a></li>
                    <li><a href="admin-gallery.html"><span class="am-icon-th"></span> 相册页面<span class="am-badge am-badge-secondary am-margin-right am-fr">24</span></a></li>
                    <li><a href="admin-log.html"><span class="am-icon-calendar"></span> 系统日志</a></li>
                    <li><a href="admin-404.html"><span class="am-icon-bug"></span> 404</a></li>
                </ul>
            </li>
            <li><a href="admin-table.html"><span class="am-icon-table"></span> 表格</a></li>
            <li><a href="admin-form.html"><span class="am-icon-pencil-square-o"></span> 表单</a></li>
            <li><a href="#"><span class="am-icon-sign-out"></span> 注销</a></li>-->
        </ul>
    </div>
</div>