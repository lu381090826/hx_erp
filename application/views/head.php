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
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/png" href="/assets/i/favicon.png">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <style>
        .get {
            background: #f5f5f5;
            color: #fff;
            text-align: center;
            padding: 280px 0;
        }

        .get-title {
            font-size: 200%;
            border: 2px solid #fff;
            padding: 20px;
            display: inline-block;
        }

        .get-btn {
            background: #fff;
        }

        .detail {
            background: #fff;
        }

        .detail-h2 {
            text-align: center;
            font-size: 150%;
            margin: 40px 0;
        }

        .detail-h3 {
            color: #1f8dd6;
        }

        .detail-p {
            color: #7f8c8d;
        }

        .detail-mb {
            margin-bottom: 30px;
        }

        .hope {
            background: #0bb59b;
            padding: 50px 0;
        }

        .hope-img {
            text-align: center;
        }

        .hope-hr {
            border-color: #149C88;
        }

        .hope-title {
            font-size: 140%;
        }

        .about {
            background: #fff;
            padding: 40px 0;
            color: #7f8c8d;
        }

        .about-color {
            color: #34495e;
        }

        .about-title {
            font-size: 180%;
            padding: 30px 0 50px 0;
            text-align: center;
        }

        .footer p {
            color: #7f8c8d;
            margin: 0;
            padding: 15px 0;
            text-align: center;
            background: #2d3e50;
        }

        .other-select {
            display: none;
        }
    </style>
</head>
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
                        <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                class="am-icon-female"></span>
                            销售管理
                        </button>
                    </div>
                <?php } ?>
                <?php if (check_auth(2)) { ?>
                    <a href="goods">
                        <div class="am-topbar-right">
                            <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                    class="am-icon-shopping-bag"></span> 商品管理
                            </button>
                        </div>
                    </a>
                <?php } ?>

                <?php if (check_auth(3)) { ?>
                    <div class="am-topbar-right">
                        <button class="am-btn am-btn-default am-topbar-btn am-btn-sm"><span
                                class="am-icon-cube"></span>
                            仓库管理
                        </button>
                    </div>
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