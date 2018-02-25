<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>登录页 | 韩迅系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="alternate icon" type="image/png" href="/assets/i/favicon.jpg">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <style>
        .header {
            text-align: center;
        }

        .header h1 {
            font-size: 200%;
            color: #333;
            margin-top: 30px;
        }

        .header p {
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="header">
    <hr/>
</div>
<div class="am-g">
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
        <h3>韩迅系统登录</h3>
        <hr>
        <br>

        <form method="post" class="am-form" action="login/confirm">
            <label for="mobile">手机号:</label>
            <input type="text" name="mobile" id="mobile" value=""
                   pattern="^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)">
            <br>
            <label for="password">密码:</label>
            <input type="password" name="password" id="password" value="">
            <br>
            <label for="remember-me">
                <input id="remember-me" name="remember_me" type="checkbox">
                记住密码
            </label>
            <br/>
            <div class="am-cf">
                <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
            </div>
            <hr>
            <div class="am-cf">
                <input type="button" onclick="ding()" name="" value="钉钉账号登录" class="am-btn am-btn am-btn-default am-btn-sm am-fl">
            </div>
        </form>
        <hr>
    </div>
</div>
</body>
<script>
    function ding() {
        location = 'https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=dingoa13doiljtowaexzbj&response_type=code&scope=snsapi_login&redirect_uri=http://www.gzhx-thxgiving.com/checkLogin';
    }
</script>
</html>
