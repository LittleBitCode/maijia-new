<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/static/css/common.css"/>
    <link rel="stylesheet" href="/static/toast/toastr.min.css"/>
    <link rel="stylesheet" href="/static/css/layout.css"/>
    <link rel="stylesheet" href="/static/css/lcj_login_register.css"/>
    <script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
    <script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
    <script language="javascript" src="/static/toast/toastr.min.js"></script>

    <style type="text/css">
        .register_content{width:450px;height:450px;font-size:14px;border-radius: 3px}
        .data_item{position:relative;width:400px;min-height:55px;padding-bottom:5px}
        .data_item>span{display:inline-block;width:105px;height:50px;padding-right:5px;text-align:left;line-height:35px;font-size:16px}
    	.register_wrap, .find_pwd_wrap {
		    box-shadow:none;
		    background: #f0f0f0;
		}
		.get_code {
			border:none;
		    background: #7B68EE;
		}
		.login_btn, .register_btn{
			border:none;
			background: #e73c3a;
		}
		.register_wrap, .find_pwd_wrap{
			margin-left: 40%;
			color: white;
			background: none;
		}
    </style>
    <title>注册-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
<!--<header class="nav_head">
    <div class="nav_head_t">
        <div class="contain">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/"><img src="/static/imgs/logo.png" alt="logo"/></a>
                </div>
                <ul class="nav navbar-nav navbar-right" style="font-size: 15px;">
                    <li><a href="/user/login">登录</a></li>
                    <li><a href="/user/register">注册</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="content2">
    <div style="margin:0 auto; width: 1200px;"><div class="logo_title">商家注册</div></div>
</div>-->
    <?php $this->load->view("/common/test1", ['site' => 'recode']); ?>
<div  style="height: 500px;background:url(/static/imgs/icon/login_bg.jpg) no-repeat 0;background-size: 100%;background-color: white">
    <div class="register_wrap" style="padding-top: 20px">
        <div class="register_content"  style="background-color: #FF6347;padding: 25px">
            <div class="data_item">
                <span>手&nbsp;&nbsp;机：</span>
                <div class="input_wrap">
                    <div class="border_wrap">
                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        <input class="phone form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="tel" placeholder="请输入手机号" maxlength="11"/>
                    </div>
                    <p></p>
                    <i class="error"></i>
                </div>
            </div>

            <div class="data_item renjiyanzheng" style="<?= (!$renqiyanzhengis) ? 'display:none' : ''; ?>">
                <span>图形码：</span>
                <div class="input_wrap">
                    <div class="border_wrap">
                        <input class="form-control" type="text" id="captcha_response" name="captcha_response" value="" maxlength="4" style="width: 140px; display: table-row;"/>
                        <img id='code2' src="<?php echo site_url('service/captcha'); ?> " alt="" width="92" height="32" onclick="create_code()" style="margin-left: 20px;"/>
                    </div>
                    <p></p>
                    <i class="error"></i>
                </div>
            </div>
            <input type="hidden" name="pid" class="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="renjiyanzhengis" class="renjiyanzhengis" value="<?php echo $renqiyanzhengis; ?>" />
            <div class="data_item">
                <span>验证码：</span>
                <div class="input_wrap">
                    <div class="border_wrap" style="width: 160px;">
                        <span class="glyphicon glyphicon-barcode form-control-feedback"></span>
                        <input class="phone_code form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="请输入手机验证码" maxlength="6" />
                    </div>
                    <a class="get_code" style="right: 0" href="javascript:;">获取验证码</a>
                    <p></p>
                    <i class="error"></i>
                </div>
            </div>

            <div class="data_item">
                <span>密&nbsp;&nbsp;码：</span>
                <div class="input_wrap">
                    <div class="border_wrap">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input class="pwd form-control" type="password" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[\u4e00-\u9fa5]/g,''))" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'')" placeholder="密码请设置6-16位数字、字母" minlength="6" maxlength="16" />
                        <img style="display: none;" class="input_val_close" src="/static/imgs/lcj_login/input_close.png" alt="" />
                    </div>
                    <p></p>
                    <i class="error"></i>
                </div>
            </div>

            <div class="data_item">
                <span>QQ号：</span>
                <div class="input_wrap">
                    <div class="border_wrap">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <input class="qq form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="请输入QQ号" />
                    </div>
                    <p></p>
                    <i class="error"></i>
                </div>
            </div>
            <div class="data_item">
                <?php if ($pid): ?>
                <span>邀请码：</span>
                <div class="input_wrap">
                    <div class="border_wrap">
                        <input class="form-control" type="text" id="inviter_code" name="captcha_response" value="<?=$pid?>" placeholder="输入邀请码(选填)" maxlength="8" style="width: 180px; display: table-row;"/>
                    </div>
                    <p></p>
                    <i class="error"></i>
                </div>
                <?php else: ?>
                    <span>邀请码：</span>
                    <div class="input_wrap">
                        <div class="border_wrap">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            <input class="form-control" id="inviter_phone" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" type="tel" placeholder="输入邀请人手机号(选填)" maxlength="11"/>
                        </div>
                        <p></p>
                        <i class="error"></i>
                    </div>
                <?php endif;?>
            </div>
            <a class="register_btn" href="javascript:;">立即注册</a>
            <div class="register_find_pwd">
                <div class="true_protocol">
                    <input type="checkbox" checked id="i_true">
                    <label for="i_true">我同意</label>
                    <a style="color: #0090FF;" href="/static/html/protocol.html" target="_blank"><?php echo PROJECT_NAME; ?>服务协议</a>
                </div>
                <span>已有账号</span>
                <a href="/user/login">立即登录</a>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
<!--
<div class="right2">
    <ul>
        <li><img src="/static/imgs/icon/notice.png"/></li>
        <li class="lineqq"><p>联系</p><p>客服</p>
        <div class="online" style="border:1px solid #ededed">
         <span>在线咨询</span>
         <span>工作时间：9:00-24:00</span>
         <a href="http://www.baidu.com" target="_blank" >商家工作客服</a>
        </div>
        </li>
        <li class="bdqrcode"><p>联系</p><p>运营</p><img src="/static/imgs/yunying.png" style="border:1px solid #ededed" alt="联系运营" /></li>
    </ul>
</div> -->
<script type="text/javascript">
    // 验证码切换
    function create_code() {
        var URL = "<?php echo site_url('service/captcha');?>";
        document.getElementById('code2').src = URL + '?' + Math.random() * 10000;
    }

    var captcha = false;
    $(function () {
        // 手机输入离开
        $('.phone').blur(function (event) {
            var input_val = $(this).val();
            var phone_reg = /^1[3456789]\d{9}$/;
            if (input_val.length == 0) {
                return false;
            } else if (!(phone_reg.test(input_val))) {
                toastr.warning("输入的手机号码格式不正确");
                return false;
            } else {
                $.ajax({
                    url: '/user/check_user_register',
                    type: "post",
                    dataType: 'json',
                    data: {
                        mobile: input_val
                    },
                    success: function (data) {
                        if (data.code != 1) {
                            //存在
                            toastr.error("该手机号已注册");
                            return false;
                        }
                    }
                });
            }
        });
        // 获取验证码
        $('.get_code').click(function (event) {
            if ($('.get_code').hasClass('get_code_dis')) return;
            var input_val = $('.phone').val();
            var phone_reg = /^1[3456789]\d{9}$/;
            if (input_val.length == 0) {
                toastr.warning("请输入注册手机号");
                return false;
            } else if (!(phone_reg.test(input_val))) {
                toastr.warning("输入的手机号码格式不正确");
                return false;
            } else {
                // 验证码输入
                var captcha_val = $('#captcha_response').val();
                if (captcha_val.length == 0) {
                    toastr.warning("请先填写图形验证码");
                    return false;
                } else {
                    $.ajax({
                        url: '/service/captcha_ajax',
                        type: "post",
                        dataType: 'json',
                        data: {code: captcha_val},
                        success: function (data) {
                            if (data.status == 1) {
                                captcha = true;
                                // 验证通过 请求后台获取手机验证码
                                $('.get_code').addClass('get_code_dis');
                                cost(60);
                                get_code();
                            } else {
                                create_code();
                                captcha = false;
                                toastr.error("图形验证码验证失败");
                            }
                        }
                    });
                }
            }
        });
        // 验证码输入离开
        var phone_code_reg = false;
        $('.phone_code').blur(function (event) {
            phone_code_reg = false;
            var input_val = $(this).val();
            var phone = $('.phone').val();
            var phone_reg = /^1[3456789]\d{9}$/;
            if (input_val.length == 0) {
                return false;
            } else if (input_val.length < 6) {
                toastr.warning("填写的验证码不正确，请确认");
                return false;
            } else {
                if (phone_reg.test(phone)) {
                    $.ajax({
                        type: "POST",
                        url: "/user/check_code_nums",
                        data: {"m": phone, "v": input_val},
                        dataType: "json",
                        async: false,
                        success: function (d) {
                            if (d.status != 1) {
                                toastr.error("输入的验证码不正确，请确认");
                                i++;
                                if (i >= 3) {
                                    $(".renjiyanzheng").show();
                                }
                            } else {
                                phone_code_reg = true;
                            }
                        }
                    });
                }
            }
        });
        // 登录密码离开
        $('.pwd').blur(function (event) {
            var input_val = $(this).val();
            var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
            if (input_val.length == 0) {
                $(this).siblings('.input_val_close').hide();
                return false;
            } else if (input_val.length < 6 || input_val.length > 16) {
                toastr.warning("密码长度应为6~16位");
                return false;
            } else if (!(pwd_reg.test(input_val))) {
                toastr.warning("密码过于简单，需包含字母和数字");
                return false;
            }
        });
        // 密码输入出现一键删除按钮
        $('.pwd').focus(function (event) {
            $(this).siblings('.input_val_close').show();
        });

        // 一键删除密码按钮
        $('.input_val_close').click(function (event) {
            $(this).hide().siblings('.pwd').val("");
        });

        // 密码展示隐藏
        $('.pwd_type').click(function (event) {
            var pwd_type = $(this).siblings('.pwd').prop('type');
            if (pwd_type == 'password') {
                $(this).siblings('.pwd').prop('type', 'text');
            } else {
                $(this).siblings('.pwd').prop('type', 'password');
            }
        });

        // QQ离开
        var qq_reg = false;
        $('.qq').blur(function (event) {
            var input_val = $(this).val();
            if (input_val.length == 0) {
                return false;
            } else if (input_val.length <= 5) {
                toastr.warning("请输入正确格式的QQ号");
                return false;
            } else {
                $.ajax({
                    url: '/user/check_userqq_register',
                    type: "post",
                    dataType: 'json',
                    async: false,
                    data: {
                        qq: input_val
                    },
                    success: function (data) {
                        if (data.code != 1) {
                            //存在
                            toastr.error("该QQ号已存在");
                        } else {
                            qq_reg = true;
                        }
                    }
                });
            }
        });

        // 同意<?php echo PROJECT_NAME; ?>服务协议
        $('#i_true').change(function (event) {
            if ($(this).is(':checked')) {
                $('.register_btn').removeClass('register_btn_dis');
            } else {
                $('.register_btn').addClass('register_btn_dis');
            }
        });


        var $renqiyanzhengis = <?php echo $renqiyanzhengis;?>;
        // 注册
        var i = 0;
        $('.register_btn').click(function (event) {
            if ($(this).hasClass('register_btn_dis')) return;
            var phone = $('.phone').val();
            var phone_reg = /^1[3456789]\d{9}$/;
            var code = $('.phone_code').val();
            var pwd = $('.pwd').val();
            var qq = $('.qq').val();
            var pid = $('.pid').val();
            var pid_inp="";
            var inviter_phone="";
            if (document.getElementById("inviter_code"))
            {
                pid =  $('#inviter_code').val();
            }
            if(document.getElementById("inviter_phone")){
                inviter_phone = $('#inviter_phone').val();
            }
            var renjiyanzhengis = $('.renjiyanzhengis').val();
            var pwd_reg = /^(?![A-Z]+$)(?![a-z]+$)(?!\d+$)(?![\W_]+$)\S{6,16}$/;
            var captcha_response = $('#captcha_response').val();
            if (i >= 3 || $renqiyanzhengis) {
                if (!captcha_response) {
                    toastr.warning("请先填写图形验证码");
                    return false;
                }
            }

            var sub = function () {
                // 验证通过请求
                $.ajax({
                    url: '/user/register_submit',
                    type: "post",
                    dataType: 'json',
                    data: {
                        mobile: phone,
                        qq: qq,
                        phone_verify: code,
                        password: pwd,
                        pid: pid,
                        inviter_phone:inviter_phone,
                        i: i,
                        renjiyanzhengis: renjiyanzhengis,
                        qr_code: 2,
                        reg_rcode: ''
                    },
                    success: function (data) {
                        if (data.is_success == 1) {
                            if (data.give_member == 1) {
                                window.location.href = '/center/bind';
                            } else {
                                window.location.href = '/group/pay_group';
                            }
                        } else {
                            toastr.error(data.msg);
                            return false;
                        }
                    }
                });
            }

            if (phone_reg.test(phone) && code.length == 6 && pwd.length >= 6 && pwd.length <= 16 && pwd_reg.test(pwd) && qq.length >= 5 && qq_reg && phone_code_reg) {
                if (i >= 3 || $renqiyanzhengis) {
                    if (captcha) {
                        sub();
                    } else {
                        if (!captcha_response) {
                            toastr.warning("请先进行图形验证码验证");
                            return false;
                        } else {
                            toastr.warning("图形验证码验证失败");
                            return false;
                        }
                    }
                } else {
                    sub();
                }
                return;
            }

            $('.phone').blur();
            $('.phone_code').blur();
            $('.pwd').blur();
            $('.qq').blur();
        });

        $(".right2 .lineqq").mouseenter(function(){
            $(this).find('.online').show();
        });
        $('.right2 .lineqq').mouseleave(function () {
            $(this).find('.online').hide();
        });
        $('.right2 .bdqrcode').mouseenter(function(){
            $(this).find('img').show();
        });
        $('.right2 .bdqrcode').mouseleave(function () {
            $(this).find('img').hide();
        });
    });

    // 短信验证码获取
    function get_code() {
        var phone = $('.phone').val();
        $.post("/user/send_message", {'mobile': phone}, function (data) {
            if (data.state == 1) {
            } else if (data.state == 2) {
                clearInterval(timer);
                $('.get_code').text("获取验证码").removeClass('get_code_dis');
                toastr.error(data.msg);
                return false;
            } else {
                clearInterval(timer);
                $('.get_code').text("获取验证码").removeClass('get_code_dis');
                toastr.error(data.msg);
                return false;
            }
        }, 'json');

    }

    var timer;
    function cost(a) {
        timer = setInterval(function () {
            a -= 1;
            var text_time = a + "s后重新获取";
            $('.get_code').text(text_time);
            if (a < 0) {
                clearInterval(timer);
                $('.get_code').text("获取验证码").removeClass('get_code_dis');
            }
        }, 1000);
    }
</script>
</body>
</html>
