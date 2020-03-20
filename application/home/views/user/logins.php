<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/static/css/common.css"/>
    <link rel="stylesheet" href="/static/toast/toastr.min.css"/>
    <link rel="stylesheet" href="/static/css/lcj_login_register.css"/>
    <link rel="stylesheet" href="/static/css/flat-ui.css"/>
    <link rel="stylesheet" href="/static/css/layout.css"/>
    <link rel="stylesheet" href="/static/css/crowd.css"/>
    <link rel="stylesheet" href="/static/css/non-responsive.css"/>
    <title>登录-<?= PROJECT_NAME; ?></title>
</head>
<body>
    <?php $this->load->view("/common/test1", ['site' => 'recode']); ?>
    <div  style="min-height:550px;background:url(/static/imgs/icon/login_bg.jpg) no-repeat 0;background-size: 100%;background-color: white;padding-top: 10px;">
        <div class="login_view" style="background-color: #D3D3D3;margin-top: 50px">
            <ul id="Tab_login" class="nav nav-tabs">
                <li class="active"><a href="#login1" data-toggle="tab">密码登录</a></li>
                <li><a href="#login2" data-toggle="tab">手机无密码登录</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="login1">
                    <div class="LoginBox pull-right">
                        <input type="hidden" name="renqiyanzheng_pwd" id="renqiyanzheng_pwd" value="no"/>
                        <input type="hidden" name="check_ip_num_pwd" id="check_ip_num_pwd" value="0"/>
                        <input type="hidden" name="check_ip_num_code" id="check_ip_num_code" value="0"/>
                        <form role="form" name="myForm1" onsubmit="return false;">
                            <!-- 账号 -->
                            <div class="form-group">
                                <img src="/static/imgs/icon/account.png" alt="账号"/>
                                <input id="user" type="text" name="user" class="form-control account"
                                       placeholder="请输入<?= PROJECT_NAME ?>账号/已验证手机号" maxlength="11" autocomplete="off"
                                       disableautocomplete/>
                                <p class="tips hide">
                                    <span class="glyphicon glyphicon-remove red"> 账号不能为空！</span>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </p>
                            </div>
                            <!-- 图形验证码 -->
                            <div class="form-group renjiyanzheng_one hide">
                                <img src="/static/imgs/icon/picture.png" alt="验证码"/>
                                <input type="text" id="captcha_response" class="form-control pad_r"
                                       placeholder="请输入图形验证码" name="phone_code" maxlength="4"/>
                                <span class="pic_code"><img id='code2' src="<?php echo site_url('service/captcha'); ?> "
                                                            alt="" onclick="create_code()"/></span>
                                <p class="tips hide">
                                    <span class="glyphicon glyphicon-remove red">图形验证码不能为空！</span>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </p>
                            </div>
                            <!-- 密码 -->
                            <div class="form-group">
                                <img src="/static/imgs/icon/Password.png" alt="密码">
                                <input type="password" class="form-control pwd" placeholder="请输入密码" name="password"
                                       ng-minlength="6" ng-maxlength="16">
                                <p class="tips hide">
                                    <span class="glyphicon glyphicon-remove red">密码不能为空！</span>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </p>
                            </div>
                            <div class="submitBtn">
                                <button type="submit" class="btn btn-default btn-blues pwd_login_btn">登录</button>
                            </div>
                            <div class="pasGo">
                                <a href="/user/find_passwd_1" class="white">忘记密码</a>
                                <?php if ($eabled_register): ?>
                                    <a href="/user/register" class="pull-right underLine white">商家注册</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="login2">
                    <div class="LoginBox pull-right">
                        <form role="form" name="myForm2" onsubmit="return false;">
                            <!-- 手机号 -->
                            <div class="form-group">
                                <img src="/static/imgs/icon/phone.png" alt="手机号">
                                <input type="tel" class="form-control phone" placeholder="请输入手机号" name="phone"
                                       maxlength="11" autocomplete="off" disableautocomplete/>
                                <p class="tips hide">
                                    <span class="glyphicon glyphicon-remove red">手机号不能为空！</span>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </p>
                            </div>
                            <!-- 图形验证码 -->
                            <div class="form-group renjiyanzheng_two"></div>
                            <!-- 验证码 -->
                            <div class="form-group">
                                <img src="/static/imgs/icon/yzm.png" alt="验证码"/>
                                <input type="text" class="form-control pad_r phone_code" placeholder="请输入手机验证码"
                                       name="phone_code" maxlength="6"/>
                                <button class="phoneCodeBox">获取验证码</button>
                                <p class="tips hide">
                                    <span class="glyphicon glyphicon-remove red">验证码不能为空！</span>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </p>
                            </div>
                            <div class="submitBtn">
                                <button type="submit" class="btn btn-default btn-blues code_login_btn">登录</button>
                            </div>
                            <div class="pasGo text-center">
                                 <a href="/user/register" class="underLine white">商家注册</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <style>
            footer{float: left;margin-top: 64px; width: 100%; padding: 20px; background:#fafafa; }
            footer p{text-align: center;margin: 0;color: #909090;}
        </style>
        <footer>
            <p>Copyright (c) 2016 Inc. All Rights. 京ICP备17021741号-1</p>
            <p>版权所有 版权所有公司属于 北京昊佳有限公司</p>
        </footer>
    </div>
<!--
<div class="right2">
    <ul>
        <li><img src="/static/imgs/icon/notice.png"/></li>
        <li class="lineqq"><p>联系</p><p>客服</p>
        <div class="online">
         <span>在线咨询</span>
         <span>工作时间：9:00-24:00</span>
         <a href="http:www.baidu.com" target="_blank" >商家工作客服</a>
        </div>
        </li>
        <li class="bdqrcode"><p>联系</p><p>运营</p><img src="/static/imgs/yunying.png" alt="联系运营" /></li>
    </ul>
</div> -->
<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script language="javascript" src="/static/js/angular.js"></script>
<script language="javascript" src="/static/js/application.js"></script>
<script language="javascript" src="/static/js/index.js"></script>
<script language="javascript" src="/static/js/constellation.js"></script>
<script type="text/javascript">
    //验证码切换
    function create_code() {
        var URL = "<?php echo site_url('service/captcha');?>";
        document.getElementById('code2').src = URL + '?' + Math.random() * 10000;
    }

    $(function () {
        // 密码登录按钮
        $('.pwd_login_btn').click(function (event) {
            var account = $('.account').val();
            if (account.length == 0) {
                toastr.warning("请输入您的登录账号");
                return false;
            }
            var renqiyanzheng_pwd = $("#renqiyanzheng_pwd").val();
            if (j >= 3 || renqiyanzheng_pwd == 'yes') {
                var captcha_response = $("#captcha_response").val();
                if (captcha_response.length == 0) {
                    toastr.warning("请先填写图形验证码");
                    return false;
                } else if (captcha_response.length != 4) {
                    toastr.warning("填写图形验证码不正确，请确认");
                    return false;
                }
            }
            var pwd = $('.pwd').val();
            if (pwd.length == 0) {
                toastr.warning("请输入您的登录密码");
                return false;
            } else if (pwd.length < 6) {
                toastr.warning("登录密码输入不正确，请确认");
                return false;
            }
            // 普通验证通过请求后台
            login(account, pwd);
        });

        // 获取验证码
        $('.phoneCodeBox').click(function (event) {
            if ($(this).hasClass('get_code_dis')) return;
            var phone = $('.phone').val();
            var phone_reg = /^1[3456789]\d{9}$/;
            var renqiyanzheng_code = $("#renqiyanzheng_code").val();
            if (phone.length == 0) {
                toastr.warning("请输入您的登录手机号");
                return false;
            } else if (!(phone_reg.test(phone))) {
                toastr.warning("您输入的手机号不正确，请确认");
                return false;
            }
            var captcha_check = true;
            if (i >= 3 || renqiyanzheng_code == 'yes') {
                //当错误次数超过三次时需要进行人机验证
                var captcha_response = $("#captcha_response").val();
                if (captcha_response.length == 0) {
                    toastr.warning("请先进行图形验证码验证");
                    return false;
                } else if (captcha_response.length != 4) {
                    toastr.warning("填写图形验证码不正确，请确认");
                    return false;
                }
                // 验证图形验证码
                $.ajax({
                    url: '/service/captcha',
                    type: "post",
                    dataType: 'json',
                    sync: false,
                    data: {code: captcha_response},
                    success: function (data) {
                        if (data.status == 1) {
                            captcha_check = true;
                        } else {
                            captcha_check = false;
                            toastr.error("图形验证码验证失败");
                            return false;
                        }
                    }
                });
            }
            if (captcha_check) {
                // 验证通过 请求后台获取手机验证码
                $('.phoneCodeBox').addClass('get_code_dis');
                cost(60);
                get_code();
            }
        });

        // 手机验证码点击登录
        $('.code_login_btn').click(function (event) {
            var phone = $('.phone').val();
            var code = $('.phone_code').val();
            var phone_reg = /^1[3456789]\d{9}$/;
            if (phone.length == 0) {
                toastr.warning("请输入您的登录手机号");
                return false;
            } else if (!(phone_reg.test(phone))) {
                toastr.warning("输入的手机号不存在");
                return false;
            }
            if (code.length == 0) {
                toastr.warning("请输入您收到的短信验证码");
                return false;
            } else if (code.length < 6) {
                toastr.warning("输入的验证码不正确，请确认");
                return false;
            }
            super_login(phone, code);
        });
    });

    // 短信验证码获取
    function get_code() {
        var phone = $('.phone').val();
        $.post("/user/login_send_code", {'mobile': phone}, function (data) {
            if (data.state != '1') {
                clearInterval(timer);
                $('.phoneCodeBox').text("获取验证码").removeClass('get_code_dis');
                toastr.error(data.msg);
            }
        }, 'json');
    }

    var timer;

    function cost(a) {
        timer = setInterval(function (_this) {
            a -= 1;
            var text_time = a + "s后重新获取";
            $('.phoneCodeBox').text(text_time);
            if (a < 0) {
                clearInterval(timer);
                $('.phoneCodeBox').text("获取验证码").removeClass('get_code_dis');
            }
        }, 1000);
    }

    var j = 0;  //用于记录错误次数
    function login(username, password) {
        var check_ip_num_pwd = $("#check_ip_num_pwd").val();
        var renqiyanzheng_pwd = $("#renqiyanzheng_pwd").val();
        var data = {
            username: username,
            password: password,
            j: j,
            check_ip_num_pwd: check_ip_num_pwd
        }
        if (j >= 3 || renqiyanzheng_pwd == 'yes') {
            //当错误次数超过三次时需要进行人机验证//当ip限制时需要
            var captcha_response = $("#captcha_response").val();
            data = {
                username: username,
                password: password,
                captcha_response: captcha_response,
                j: j,
                check_ip_num_pwd: check_ip_num_pwd
            }
        }
        $.ajax({
            url: '/user/login_submit',
            type: "post",
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.code == 0) {
                    location.href = "/center";
                } else {
                    j++;
                    if (j >= 3 || renqiyanzheng_pwd == 'yes' || data.code == 5) {
                        if ($('.renjiyanzheng_one').html() == '') {
                            $('.renjiyanzheng_one').html($('.renjiyanzheng_two').html());
                            $('.renjiyanzheng_two').html('');
                        }
                        $(".renjiyanzheng_one").removeClass("hide");
                        $("#captcha_response").val('');
                        create_code();
                    }
                    if (data.code == 5) {
                        $("#check_ip_num_pwd").val(1);
                        $("#renqiyanzheng_pwd").val(data.renqiyanzheng_pwd);
                    }
                    // 展示异常信息
                    toastr.error(data.msg);
                }
            }
        });

        return false;
    }

    var i = 0;  //用于记录错误次数
    function super_login(mobile, verify) {
        var check_ip_num_code = $("#check_ip_num_code").val();
        var renqiyanzheng_code = $("#renqiyanzheng_code").val();
        var data = {
            'mobile': mobile,
            'verify': verify,
            'i': i,
            'check_ip_num_code': check_ip_num_code
        }

        if (i >= 3 || renqiyanzheng_code == 'yes') {
            //当错误次数超过三次时需要进行人机验证
            var captcha_response = $("#captcha_response").val();
            if (captcha_response.length == 0) {
                toastr.warning("请先进行图形验证码验证");
                return false;
            } else if (captcha_response.length != 4) {
                toastr.warning("填写图形验证码不正确，请确认");
                return false;
            }
            data.captcha_response = captcha_response;
        }
        $.ajax({
            url: '/user/super_login',
            type: "post",
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.code == 0) {
                    location.href = "/center";
                } else {
                    i++;
                    if (i >= 3 || renqiyanzheng_code == 'yes' || data.code == 5) {
                        if ($('.renjiyanzheng_two').html() == '') {
                            $('.renjiyanzheng_two').html($('.renjiyanzheng_one').html());
                            $('.renjiyanzheng_one').html('');
                        }
                        $('.renjiyanzheng_two').removeClass('hide');
                        $("#captcha_response").val('');
                        create_code();
                    }
                    if (data.code == 5) {
                        $("#check_ip_num_code").val(1);
                        var value = data.renqiyanzheng_code;
                        $("#renqiyanzheng_code").val(value);
                    }
                    toastr.error(data.msg);
                }
            }
        });

        return false;
    }

    $(function () {
        $(".right2 .lineqq").mouseenter(function () {
            $(this).find('.online').show();
        });
        $('.right2 .lineqq').mouseleave(function () {
            $(this).find('.online').hide();
        });
        $('.right2 .bdqrcode').mouseenter(function () {
            $(this).find('img').show();
        });
        $('.right2 .bdqrcode').mouseleave(function () {
            $(this).find('img').hide();
        });
    });
</script>
</body>
</html>
