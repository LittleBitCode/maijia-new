<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/css/common.css"/>
    <link rel="stylesheet" href="/static/css/lcj_login_register.css"/>
    <title>找回登录密码-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
<header class="nav_head">
    <div class="nav_head_t">
        <div class="contain">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/"><img src="/static/imgs/logo.png" alt="logo"/></a>
                </div>
                <ul class="nav navbar-nav navbar-right" style="font-size: 15px;">
                    <li><a href="/user/login">登录</a></li>
                    <!-- <li><a href="/user/register">注册</a></li> -->
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="bg_wrap" style="min-height: 72vh">
    <div class="content2">
        <div style="margin:0 auto; width: 1200px;"><div class="logo_title">找回登录密码</div></div>
    </div>
    <div class="find_pwd_wrap" style="margin-top: 32px;">
        <p class="find_pwd_titele"><span>找回登录密码</span><a href="/">返回登录</a></p>
        <div class="find_pwd_true">
            <p>
                <img src="/static/imgs/lcj_login/find_pwd_true.png" alt=""/>
                <span>恭喜你，密码重置成功</span>
            </p>
            <p>为了您的账户安全，请注意保护好您的密码等信息！</p>
            <div class="data_item" style="margin: 10px auto;">
                <a class="next_find_pwd third_next" href="/">去登录</a>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
</body>
</html>