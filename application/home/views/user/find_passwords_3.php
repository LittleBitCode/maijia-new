<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x"> 
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<link rel="stylesheet" href="/static/css/lcj_login_register.css" />
<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
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
            <p class="find_pwd_titele">
                <span>找回登录密码</span>
                <a href="/">返回登录</a>
            </p>
        
            <div class="find_pwd_third">
                <ul class="find_pwd_step">
                    <li class="find_pwd_step_item active">
                        <p class="find_pwd_step_number">1</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">填写手机号</p>
                    </li>
                    <li class="find_pwd_step_item active">
                        <p  class="find_pwd_step_number">2</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">验证信息</p>
                    </li>
                    <li class="find_pwd_step_item active">
                        <p  class="find_pwd_step_number">√</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">重置密码</p>
                    </li>
                </ul>
                <div class="data_item" style="margin: 0 auto; margin-top: 86px;">
                    <p class="find_pwd_phone">请重置密码并妥善保管</p>
                </div>
                <form id="form" action="/user/set_passwd/<?php echo $user_id; ?>/<?php echo $verify; ?>" method="post">
                <div class="data_item" style="margin: 0 auto;">
                    <span>新密码：</span>
                    <div class="input_wrap">
                        <div class="border_wrap">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <input style="padding-right: 54px;" class="pwd form-control" type="password" name="password" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[\u4e00-\u9fa5]/g,''))" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'')" placeholder="请输入6~16位包含数字,字母的密码" minlength="6" maxlength="16">
                            <img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
                        </div>
                        <p></p>
                        <i class="error"></i>
                    </div>
                </div>
                <div class="data_item" style="margin: 0 auto;">
                    <span>确认密码：</span>
                    <div class="input_wrap">
                        <div class="border_wrap">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <input style="padding-right: 54px;" class="pwds form-control" type="password" name="passwords" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[\u4e00-\u9fa5]/g,''))" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'')" placeholder="请再次输入密码" minlength="6" maxlength="16">
                            <img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="">
                        </div>
                        <p></p>
                        <i class="error"></i>
                    </div>
                </div>

                <div class="data_item" style="margin: 0 auto;">
                    <a class="next_find_pwd third_next" href="javascript:;">下一步</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <script type="text/javascript">
        $(function() {
            // 第三步
            // 密码输入出现一键删除按钮
            $('.pwd').focus(function(event) { 
                $(this).siblings('.input_val_close').show();
            });
            $('.pwds').focus(function(event) { 
                $(this).siblings('.input_val_close').show();
            });
            // 一键删除密码按钮
            $('.input_val_close').click(function(event) {
                $(this).hide().siblings('.pwd').val("");
            });
            // 密码离开
            $('.pwd').blur(function(event) {
                var input_val = $(this).val();
                var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
                if(input_val.length == 0){
                    $(this).siblings('.input_val_close').hide();
                    return false;
                }else if(input_val.length <6 || input_val.length >16){
                    toastr.warning("密码长度为6~16位");
                    return false;
                }else if(!(pwd_reg.test(input_val))){
                    toastr.warning("密码过于简单，需包含字母和数字");
                    return false;
                }
            });
            // 再次输入密码离开
            $('.pwds').blur(function(event) {
                var pwds = $(this).val();
                var pwd = $('.pwd').val();
                if(pwds == ''){
                    $(this).siblings('.input_val_close').hide();
                    return false;
                }else if(pwds != pwd){
                    toastr.warning("两次输入密码不一致，请重新输入");
                    return false;
                }
            });

            // 提交修改密码
            $('.third_next').click(function(event) {
                var pwd = $('.pwd').val();
                var pwds = $('.pwds').val();
                if (pwd.length==0){
                    toastr.warning("请输入新的登录密码");
                    return false;
                }
                if (pwds.length==0){
                    toastr.warning("请重复输入新的登录密码");
                    return false;
                }
                var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
                if (!pwd_reg.test(pwd) || pwds != pwd) {
                    toastr.warning("输入的新登录密码不合法，请确认");
                    return false;
                }

                $("#form").submit();
            });
        });
    </script>
</body>
</html>