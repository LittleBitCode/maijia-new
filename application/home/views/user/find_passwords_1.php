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
<link rel="stylesheet" href="/static/css/layout.css"/>
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
    <div class="bg_wrap" style="min-height: 72vh;">
        <div class="content2">
            <div style="margin:0 auto; width: 1200px;"><div class="logo_title">找回登录密码</div></div>
        </div>
        <div class="find_pwd_wrap" style="margin-top: 32px;">
            <p class="find_pwd_titele"><span>找回登录密码</span><a href="/">返回登录</a></p>
            <div class="find_pwd_first">
                <ul class="find_pwd_step">
                    <li class="find_pwd_step_item active">
                        <p class="find_pwd_step_number">1</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">填写手机号</p>
                    </li>
                    <li class="find_pwd_step_item">
                        <p  class="find_pwd_step_number">2</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">验证信息</p>
                    </li>
                    <li class="find_pwd_step_item">
                        <p  class="find_pwd_step_number">√</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">重置密码</p>
                    </li>
                </ul>
    
                <div class="data_item" style="margin: 86px auto;margin-bottom: 20px;">
                    <span>手机号：</span>
                    <div class="input_wrap">
                        <div class="border_wrap">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            <input class="phone form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="请输入手机号" maxlength="11">
                        </div>
                        <p></p>
                        <i class="error"></i>
                    </div>
                </div>
                <div class="data_item" style="margin: 0 auto;">
                    <a class="next_find_pwd first_next" href="javascript:;">下一步</a>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <script type="text/javascript">
        $(function() {
            // 手机输入离开
            $('.phone').blur(function(event) {
                var input_val = $(this).val();
                var phone_reg = /^1[345789]\d{9}$/;
                if(input_val.length == 0){
                    return false;
                }else if(!(phone_reg.test(input_val))){
                    toastr.warning("您输入的手机号不存在，请核对后重新输入");
                    return false;
                }
            });
            // 第一步填写手机号完成
            $('.first_next').click(function(event) {
                var phone = $('.phone').val();
                var phone_reg = /^1[345789]\d{9}$/;
                if(phone_reg.test(phone)){
                    // 验证通过 请求
                    var _this = $(this);
                    $.ajax({
                        url: '/user/find_mobile',
                        type: "post",
                        dataType: 'json',
                        data:{
                            mobile:phone
                        },
                        success: function (data) {
                            if (data.code != 1) {
                                //不存在
                                toastr.error("您输入的手机号不存在，请核对后重新输入");
                                return false;
                            }else{
                                location.href = '/user/find_passwd/'+data.id;
                            }
                        }
                    })
                    return false;
                }
                $('.phone').blur();
            });
        });
    </script>
</body>
</html>