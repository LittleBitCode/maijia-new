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
<link rel="stylesheet" href="/static/css/layout.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<link rel="stylesheet" href="/static/css/lcj_login_register.css" />
<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<title>找回登录密码-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
<header class="nav_head" style="height: 80px">
    <div class="nav_head_t" style="display: none">
        <div class="contain_top">
            <div class="contain_one">
                <div class="contain_layui_col1">
                    <span><a href="/user/login">登录<a></span>
                </div>
            </div>
        </div>
    </div>
    <div style="background-color: white;">
        <div class="nav_head_b contain">
            <nav class="navbar navbar-default navbar-lg" role="navigation">
                <img style="width: 300px;float: left;margin: 12px;" src="/static/imgs/icon/jinpai.png">

            </nav>
        </div>
    </div>
</header>
    <div class="bg_wrap" style="min-height:72vh">
        <div class="find_pwd_wrap" style="margin-top: 32px;">
            <p class="find_pwd_titele"><a href="/">返回登录</a></p>
            <div class="find_pwd_second">
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
                    <li class="find_pwd_step_item">
                        <p  class="find_pwd_step_number">√</p>
                        <span class="find_pwd_step_bg"></span>
                        <p class="find_pwd_step_title">重置密码</p>
                    </li>
                </ul>
                <div class="data_item" style="margin: 86px auto;min-height: 30px; margin-bottom: 20px;">
                    <p class="find_pwd_phone">您的手机号：<span class="phone_number" val=""><?php echo str_replace(substr($mobile,3,4),'****',$mobile); ?></span></p>
                    <input type="hidden" name="error_nums" value="0">
                </div>

                <div class="data_item renjiyanzheng" style="margin: 0 auto;display: none;">
                    <span>图形码：</span>
                    <div class="input_wrap">
                        <div class="border_wrap">
                            <input class="form-control" type="text" id="captcha_response" name="captcha_response" value="" maxlength="4" style="width: 140px; display: table-row;" />
                            <img id='code2' src="<?php echo site_url('service/captcha'); ?> " alt="" width="92" height="32" onclick="create_code()" style="margin-left: 20px;" />
                        </div>
                    </div>
                    <p style="color: red;margin-left: 30px "></p>
                </div>


                <div class="data_item" style="margin: 0 auto;margin-bottom: 20px;">
                    <span>验证码：</span>
                    <div class="input_wrap">
                        <div class="border_wrap" style="width: 160px;">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            <input class="phone_code form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="请输入手机验证码" maxlength="6">
                        </div>
                        <a class="get_code" style="right: 0" href="javascript:;">获取验证码</a>
                        <p></p>
                        <i class="error"></i>
                    </div>
                </div>
                <div class="data_item" style="margin: 0 auto;">
                    <a class="next_find_pwd second_next" href="javascript:;">下一步</a>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <script type="text/javascript">
        // 验证码切换
        function create_code(){
            var URL = "<?php echo site_url('service/captcha');?>";
            document.getElementById('code2').src = URL+'?'+Math.random()*10000;
        }

        // 第二步
        $(function() {
            // 获取验证码
            $('.get_code').click(function(event) {
                if ($(this).hasClass('get_code_dis')) return false;
                // 当错误次数超过三次时需要进行人机验证
                var mobile = '<?php echo $mobile; ?>';
                if (i >= 3) {
                    var captcha_response = $("#captcha_response").val();
                    if(!captcha_response){
                        toastr.warning("请先进行图形验证码验证");
                        return false;
                    }
                    $.ajax({
                        url: '/service/captcha_ajax',
                        type: "post",
                        dataType: 'json',
                        data: {code: captcha_response},
                        success: function (data) {
                            if(data.status == 1){
                                $('.get_code').addClass('get_code_dis');
                                cost(60);
                                $.ajax({
                                    url: '/user/login_send_code',
                                    type: "post",
                                    dataType: 'json',
                                    data: {
                                        mobile: mobile
                                    },
                                    success: function (data) {
                                        if (data.state != 1) {
                                            clearInterval(timer);
                                            $('.get_code').text("获取验证码").removeClass('get_code_dis');
                                            toastr.error(data.msg);
                                        }
                                    }
                                });
                            }else{
                                create_code();
                                toastr.error("图形验证码验证失败");
                            }
                        }
                    });
                } else {
                    $('.get_code').addClass('get_code_dis');
                    cost(60);
                    $.ajax({
                        url: '/user/login_send_code',
                        type: "post",
                        dataType: 'json',
                        data: {
                            mobile: mobile
                        },
                        success: function (data) {
                            if (data.state != 1) {
                                clearInterval(timer);
                                $('.get_code').text("获取验证码").removeClass('get_code_dis');
                                toastr.error(data.msg);
                            }
                        }
                    });
                }
            });

            //第二步提交
            var i = 0;
            $('.second_next').click(function(event) {
                var mobile = '<?php echo $mobile; ?>';
                var verify = $('.phone_code').val();
                var user_id = '<?php echo $id; ?>';
                data = {mobile:mobile,verify:verify,i:i};
                if (i >= 3) {
                    //当错误次数超过三次时需要进行人机验证
                    var captcha_response = $("#captcha_response").val();
                    if(!captcha_response){
                        toastr.warning("请先进行图形验证码验证");
                        return false;
                    }
                    data = {mobile: mobile, verify: verify, i: i, captcha_response: captcha_response};
                }

                $.ajax({
                    url: '/user/check_findpwd_2',
                    type: "post",
                    dataType: 'json',
                    data:data,
                    success: function (data) {
                        if (data.code != 1) {
                            //不存在
                            i++;
                            if (i>=3) {
                                $(".renjiyanzheng").show();
                                if ($("#captcha_response").val()) {
                                    $("#captcha_response").val('');
                                }
                            }
                            create_code();
                            toastr.error(data.msg);
                            return false;
                        }else{
                            window.location.href = '/user/set_passwd/' + user_id + '/' + verify;
                            return false;
                        }
                    }
                })

            });

            // 验证码输入离开
            $('.phone_code').blur(function(event) {
                var input_val = $(this).val();
                if (input_val.length == 0) {
                    return false;
                } else if (input_val.length != 6) {
                    toastr.warning("请输入您收到的6位短信验证码");
                    return false;
                }
            });
        });
        
        var timer;
        function cost(a){
            timer = setInterval(function(){
                a-=1;
                var text_time = a+"s后重新获取";
                $('.get_code').text(text_time);
                if(a < 0){
                    clearInterval(timer);
                    $('.get_code').text("获取验证码").removeClass('get_code_dis');
                }
            },1000);
        }
    </script>
</body>
</html>