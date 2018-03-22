<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>韩迅系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta HTTP-EQUIV="pragma" CONTENT="no-cache">
    <meta HTTP-EQUIV="Cache-Control" CONTENT="no-siteapp,no-cache, must-revalidate">
    <meta HTTP-EQUIV="expires" CONTENT="0">
    <link rel="alternate icon" type="image/png" href="/assets/i/favicon.jpg">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <!--    韩迅css   -->
    <link rel="stylesheet" href="/assets/css/app.css"/>
    <!--    jquery   -->
    <script src="/assets/js/1.11.1/jquery.min.js"></script>
</head>
<style>
    .am-topbar-right {
        /*float: left;*/
        margin-right: 1px;
    }
</style>
<body>
<header class="am-topbar am-topbar-fixed-top">
    <div class="am-container">
        <h1 class="am-topbar-brand">
            <a href="/">韩迅-<?php echo $this->session->name; ?></a>
        </h1>

        <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
                data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
                class="am-icon-bars"></span></button>

        <div class="am-collapse am-topbar-collapse" id="collapse-head">

            <?php if (!$this->session->name) { ?>
                <div class="am-topbar-right">
                    <a href="login">
                        <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm"><span class="am-icon-user"></span>
                            登录
                        </button>
                    </a>
                </div>
            <?php } else { ?>
                <?php if (check_auth(8)) { ?>
                    <div class="am-topbar-right">
                        <a href="/sell/order/Order">
                            <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                    class="am-icon-female"></span>
                                销售管理
                            </button>
                        </a>
                    </div>
                <?php } ?>
                <?php if (check_auth(2) || check_auth(9)) { ?>
                    <a href="/goods">
                        <div class="am-topbar-right">
                            <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                    class="am-icon-shopping-bag"></span> 商品管理
                            </button>
                        </div>
                    </a>
                <?php } ?>

                <?php if (check_auth(3)) { ?>
                <a href="/depot/index/">
                    <div class="am-topbar-right">
                        <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                class="am-icon-cube"></span>
                            仓库管理
                        </button>
                    </div>
                </a>
                <?php } ?>

                <?php if (check_auth(4)) { ?>
                    <div class="am-topbar-right">
                        <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                class="am-icon-dollar"></span>
                            财务管理
                        </button>
                    </div>
                <?php } ?>

                <?php if (check_auth(5)) { ?>
                    <div class="am-topbar-right">
                        <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                class="am-icon-scissors"></span>
                            生产管理
                        </button>
                    </div>
                <?php } ?>

                <?php if (check_auth(6)) { ?>
                    <div class="am-topbar-right">
                        <a href="/sys">
                            <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                    class="am-icon-cogs"></span>
                                系统管理
                            </button>
                        </a>
                    </div>
                <?php } ?>

                <?php if (check_auth(6)) { ?>
                    <div class="am-topbar-right">
                        <a href="/data">
                            <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                    class="am-icon-dashboard"></span>
                                数据报表
                            </button>
                        </a>
                    </div>
                <?php } ?>
                <div class="am-topbar-right">
                    <a href="/login/login_out">
                        <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm"><span
                                class="am-icon-user"></span>
                            退出登录
                        </button>
                    </a>
                </div>
            <?php } ?>

        </div>

    </div>
</header>