<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico" />
    <link rel="stylesheet" href="/static/css/common.css" />
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/static/css/deposit.css" />
    <link rel="stylesheet" href="/static/toast/toastr.min.css" />
    <title><?= $is_vip ? '续费会员' : '开通会员'; ?>-<?= PROJECT_NAME; ?></title>
    <style>
        li{
            list-style: none;
        }
        a{
            text-decoration: none;
        }
        * {
            font-family: 微软雅黑;
        }
        .nav_head a{
            cursor: pointer;
            text-decoration: none;
        }
        body{
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            line-height: 1.42857143;
            color: #333;
            padding: 0;
            margin: 0;
        }
        .nav_head{
            /*background: url(img/logo.png) no-repeat;*/
            background-size: 100% 100%;
            width: 100%;
            height: 127px;
        }
        .nav_head_t {
            font-size: 14px;
            line-height: 30px;
            border-bottom: #e4e3e3 solid 0px;
        }
        .contain_top {
            font-weight: bold;
            width: 100%;
            margin: 0 auto;
            background: #f9f9f9;
        }
        .nav_head .navbar {
            margin: 0;
            border: none;
            background: none;
        }
        .navbar {
            position: relative;
            min-height: 50px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        .contain_one{
            height: 30px;
            width: 1170px;
            margin: 0 auto;
        }
        .vertical {
            width: 1px;
            height: 12px;
            display: inline-block;
            background: #999;
            margin: 0 5px;
        }
        .contain_layui_col1{
            float: left;
            width: 16.7%;
        }
        .contain_layui_col2{
            width: 83.3%;
            float: left;
        }
        .contain_layui_col2left{
            float: left;
            display: inline-block;
            width: 50%;
            text-align: right;
        }
        .color-font2{
            text-decoration: none;
            color: red;
        }
        .contain_layui_col2mid{
            float: left;
            text-align: right;
            display: inline-block;
            width: 33%;
        }
        .contain_layui_col2right{
            float: left;
            text-align: right;
            display: inline-block;
            width: 16%;
        }
        .contain_bot{
            height: 80px;
            width: 1170px;
            margin: 0 auto;
        }
        .contain_botleft{
            float: left;
            width: 200px;
            height: 100%;
        }
        .contain_botright{
            line-height: 80px;
            float: left;
            width: 970px;
            height: 80px;
        }
        .mainli{
            width: 12%;
            float: left;
            font-size:18px ;
            list-style: none;
        }
        .caret{
            border-top-color: #ffffff;
            border-bottom-color: #ffffff;
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px dashed;
            border-top: 4px solid\9;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }
        .dropdown-menu{
            min-width: 100%;
            z-index: 1000;
            background-color: #f3f4f5;
            min-width: 220px;
            border: none;
            margin-top: 9px;
            padding: 0;
            font-size: 14px;
            border-radius: 4px;
            box-shadow: none;
            top: 100%;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            text-align: left;
            list-style: none;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }
        .dropdown-menu li{
            cursor: pointer;
            margin-left: 10px;
            line-height: 30px;
            height: 30px;
        }

        .left_tab{
            background-color: white;
            float: left;
            width: 178px;
            height: auto;
        }
        .left_tab_person{
            text-align: center;
            padding: 30px 0;
        }
        .left_tab_nav{
            text-decoration: none;
            margin: 0;
            padding: 0;
        }
        .left_tab_nav li{
            list-style-type:none;
            list-style:none;
            text-decoration: none;
            height: 47px;
            text-align: center;
            line-height: 47px;
            cursor: pointer;
        }
        .left_tab_nav a{
            font-size: 16px;
            color: #333;
            cursor: pointer;
            text-decoration: none;
        }
        .left_tab_nav_head{
            height: 55px;
            line-height: 55px;
            background: #eaeaea;
            color: #999;
            font-size: 20px;
            margin: 0;
            padding: 0;
            list-style-type: none;
            text-align: center;
        }

        .right_center{
            width: 947px;
            height: auto;
            float: left;
            padding: 7.5px;
        }
        .right_center_top{
            background-color: white;
            border: 1px solid #cccccc;
            height: auto;
            padding: 20px;
            height: 150px;
        }
        .right_center_top_left{
            border-right: 1px solid #ccc;
            margin: 20px 0;
            float: left;
        }
        .color-font{
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }
        .right_center_top_left1{
            width: 180px;
            float: left;
            margin: 10px;
        }
        .right_center_top_left1 p{
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .right_center_top_mid{
            margin: 0px 20px;
            float: left;
        }
        .right_center_top_mid1{
            padding: 6px;
            float: left;
            width: 128px;
        }
        .cons{
            text-align: center;
        }
        .cons_top{
            position: relative;
        }
        .cons_top span{
            line-height: 80px;
            display: inline-block;
            width: 100%;
            background-color: #e56a6a;
            font-size: 20px;
            color: #fff;
        }
        .cons_top strong{
            position: absolute;
            width: 110px;
            left: 50%;
            margin-left: -55px;
            bottom: -13px;
            border: 2px solid #ea8888;
            background-color: #fff;
            line-height: 26px;
            border-radius: 13px;
        }
        .cons_bottom{
            width: 63%;
            height: 30px;
            border: 1px solid #ccc;
            border-top: none;
            padding: 23px 23px 9px;
        }
        .cons_bottom a{
            display: inline-block;
            width: 100%;
            font-size: 13px;
            background-color: #6aa5e5;
            color: #f0f0f0;
            text-align: center;
            line-height: 25px;
        }
        .right_center_top_right{
            text-align: center;
            width: 100%;
            height: 50px;
            line-height: 50px;
            font-size: 20px;
            border-radius: 5px;
        }
        .right_center_top_right a{
            background-color: #ff6b71;
            width: 150px;
            height: 38px;
            color: #fff;
            font-size: 13px;
            text-align: center;
            display: inline-block;
            line-height: 38px;
            margin-top: 50px;
            text-decoration: none;
            height: 50px;
            line-height: 50px;
            font-size: 20px;
            border-radius: 5px;
        }

        .banner{
            margin-top: 20px;
            width: 974px;
            float: left;
        }

        .my_task{
            width: 947px;
            float: left;
            margin-top: 20px;
        }
        /*.tit_box{
            padding-bottom: 15px;
        }*/
        .tit{
            padding-left: 20px;
            font-size: 20px;
            color: #333;
            border-left: 4px solid #ed702c;
        }
        .my_task ul li{
            list-style:none;
            float: left;
            padding: 0px 5px 0 44px;
        }
        .my_task ul {
            margin: 0px;
            padding: 0px;
        }
        .my_task ul li a{
            padding: 5px 10px;
            background-color: white;
            display: inline-block;
            width: 100%;
            height: 100%;
        }
        .my_task ul li img {
            float: left;
            vertical-align: bottom;
            margin-right: 10px;
        }
        .my_task ul li div{
            float: left;
            display: inline-block;
            padding: 5px;
            font-size: 16px;
            color: #333;
            line-height: 20px;
        }
        .examine_task_cont{
            background-color: #fff;
            padding-bottom: 20px;
        }
        .nav_1 ul{
            overflow: hidden;
            border-bottom: 1px solid #ccc;
        }
        .nav_1 ul li{
            padding: 0;
            margin: 0;
        }
        .nav_1 ul li a{
            width: auto;
            line-height: 44px;
            display: inline-block;
            padding: 0 25px;
            font-size: 16px;
            color: #666;
            position: relative;
        }
        .on a{
            background-color: #ed702c;
            color: #fff;
        }
        .choose_cond{
            padding: 0 0 10px;
        }
        .choose_cond ul {
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .choose_cond ul li{
            margin-bottom: 20px;
            margin-right: 15px;
            float: left;
        }
        .choose_cond li span {
            font-size: 14px;
            color: #666;
            line-height: 35px;
        }
        .layui-input{
            display: inline-block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        .input_time{
            width: 160px;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 32px;
        }
        button{
            height: 36px;
            background-color: #e73c3a;
            font-size: 16px;
            color: #fff;
            border-radius: 5px;
            width: 100px;
            margin-left: 30px;
            outline: 0;
            -webkit-appearance: none;
            transition: all .3s;
            -webkit-transition: all .3s;
            box-sizing: border-box;
            align-items: flex-start;
            text-align: center;
            cursor: default;
            box-sizing: border-box;
            padding: 2px 6px 3px;
            border-width: 2px;
            border-style: outset;
            border-color: buttonface;
            border-image: initial
        }
        .on a{
            background-color: #ed702c;
            color: #fff;
        }

        *{
            padding: 0px;
            margin: 0px;
        }
        .recharge_right{
            padding: 15px;
            background-color: white;
        }
        .rest_time{
            margin-top: 15px;
        }
        p {
            margin-bottom: 0px;
            font-size: 14px;
            line-height: 22px;
        }

        .rest_time img{
            float: left;
            display: inline-block;
            width: 45px;
            height: 45px;
            vertical-align: top;
            margin-right: 10px;
        }

        .recharge_box .members_box .rest_time div {
            margin-bottom: 50px;
            display: inline-block;
        }
        .tit_box{
            margin-top: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #ccc;
        }
        .time_choose{
            width: 100%;
            margin: 0;
            margin-top: 35px;
        }
        .layui_col_md4{
            margin-bottom: 30px;
            margin-left: 25px;
            display: inline-block;
            width: 27%;
        }
        .bg_box{
            background-repeat: no-repeat;
            text-align: center;
            background-position: center center;
            height: 120px;
            padding-top: 12px;
            position: relative;
            cursor: pointer;
            background-image: url(/static/imgs/icon/menbers_sel_bg.png);
        }
        .user_vip_month{
            font-size: 40px;
            margin-left: 85px;
            color: #333;

        }

        .count{
            position: absolute;
            left: -7px;
            top: 21px;
            background-repeat: no-repeat;
            width: 108px;
            height: 45px;
            background-size: 100% 100%;
            background-position: center center;
            background-image: url(/static/imgs/icon/count.png);
        }
        .count span{
            font-size: 22px;
            color: #fff;
            font-weight: bold;
            vertical-align: top;
            line-height: 33px;
            text-shadow: 1px 1px 10px #ff6f1f;
        }
        .user_vip_money{
            font-size: 24px;
            color: #666;
        }
        .recharge_pay_img{
            background-image: url(/static/imgs/icon/choose_pay_bg.png);
            margin-top: 10px;
            width: 165px;
            height: 60px;
            /*border: 1px solid #e7e7e7;*/
        }
        .recharge_pay_img img {
            margin-left: 15px;
            margin-top: 10px;
            height: 40px;
            width: 40px;
            vertical-align: middle;
            margin-right: 11px;
        }
        .recharge_pay_img span{
            line-height: 60px;
            font-size: 18px;
        }
        .recharge_pay_money p{
            margin-top: 10px;
            font-size: 18px;
            color: #666;
        }
        .recharge_tijiao{
            margin-top: 50px;
            margin-left: 20px;
            width: 200px;
            height: 55px;
            background-color: #e92725;
            font-size: 26px;
            outline: none;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        .recharge_dec {
            font-size: 16px;
            color: #666;
            margin-top: 50px;
            margin-bottom: 85px;
        }
        .recharge_dec p{
            font-size: 16px;
        }
        .recharge_dec p span{
            color: #333;
        }
        .col-xs-6{
            margin-top: 10px;
            font-size: 18px;
        }
        .active{
            background-image: url(/static/imgs/icon/menbers_sel_ed_bg.png);
        }
        #myModal {top:30%;display:none;}
        #myModal .modal-dialog .modalImg{position: absolute;top: -20px;z-index: 1002;width: 600px;text-align: center;}
        #myModal .modal-content{background:#fff100;width:600px;height:365px;}
        #myModal .modal-content .modal-body p{padding-top:124px;font-size:20px;color:#000;}
        #myModal .modal-content .btns{padding:16px;text-align:center}
        #myModal .modal-content .btns button{font-size:20px;margin-right:64px;margin-left:84px;width:120px;height:48px;color:#fff;border-radius:5px;background-color:#fc3131;-moz-box-shadow:0 6px 6px rgba(0, 0, 0, 0.3);-webkit-box-shadow:0 6px 6px rgba(0, 0, 0, 0.3);box-shadow:0 6px 6px rgba(0, 0, 0, 0.3); }
    </style>
</head>
<body>
<?php $this->load->view("/common/top1", ['site' => 'recode']); ?>
<div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
    <div class="contain right_center" style="   margin-top: 2px;min-height: 71vh;width: 947px;float: left;">
        <div class="recharge_right">

            <div class="rest_time">
                <img src="/static/imgs/icon/members_time_bg.png"/>
                <div>
                    <p>会员有效期：</p>
                    <span><?php echo $surplus_days; ?>天</span>
                </div>
                <div class="tit_box">
                    <p class="tit">请选择会员时长</p>
                </div>
                <div class="time_choose">
                    <div class="layui_col_md4">
                        <div class="bg_box" id="3" val='1500'>
                            <strong class="user_vip_month">3个月</strong>
                            <p><span class="user_vip_money">1500</span>元</p>
                            <div class="count">
                                <span>体验推荐</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui_col_md4">
                        <div class="bg_box active" id="6" val='1800'>
                            <strong class="user_vip_month">6个月</strong>
                            <p><span class="user_vip_money">1800</span>元</p>
                            <div class="count">
                                <span>5.6折</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui_col_md4">
                        <div class="bg_box" id="12" val='2100'>
                            <strong class="user_vip_month">12个月</strong>
                            <p><span class="user_vip_money">2100</span>元</p>
                            <div class="count">
                                <span>3.1折</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui_col_md4">
                        <div class="bg_box" id="24" val='2400'>
                            <strong class="user_vip_month">24个月</strong>
                            <p><span class="user_vip_money">2400</span>元</p>
                            <div class="count">
                                <span>1.7折</span>
                            </div>
                        </div>
                    </div>
                    <div class="layui_col_md4">
                        <div class="bg_box" id="48" val='3000'>
                            <strong class="user_vip_month">48个月</strong>
                            <p><span class="user_vip_money">3000</span>元</p>
                            <div class="count">
                                <span>1.1折</span>
                            </div>
                        </div>
                    </div>
                    <!--<?php foreach ($group_price_list as $k=>$v): ?>
						<div class="layui_col_md4">
								<div class="bg_box <?= ($k==6) ? 'active':''; ?>" id="<?= $k ?>" val="<?= $v['price'] ?>">
									<strong class="user_vip_month"><?= $v['month']; ?>个月</strong>
									<p><span class="user_vip_money"><?= $v['price']; ?></span>元</p>
									<div class="count">
										<span>1.1折</span>
									</div>
							</div>
						</div>
						<?php endforeach; ?>-->
                    <div class="tit_box">
                        <p class="tit">支付</p>
                    </div>
                    <div class="recharge_pay_img">
                        <img src="/static/imgs/icon/yajin_pay.png" />
                        <span>
								押金支付
							</span>
                    </div>
                    <div class="recharge_pay_money">
                        <div class="row">
                            <div class="col-xs-6">您已选择购买 商家VIP<span class="text-danger j-date-time">6</span>个月</div>
                            <div class="col-xs-6 text-right f18">需支付 <span class="text-danger j-pay-amount"><?= $group_price_list[6]['price'] ?></span> 元</div>

                            <input type="submit" id="" submitbutton="true" value="确认支付" class="recharge_tijiao j-pay-btn">
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="vip_box">
        <div class="vip_type">

            <div class="row">
                <p class="j-lack-deposit text-right <?= (floatval($user_info->user_deposit) >= $group_price_list[6]['price']) ? 'hide' : '' ?>" style="height: 40px; line-height: 40px; font-size:16px;"><span class="deposit_ande_point">押金不足，还差<em class="points_pay_number" style="color:#f00;"><?= number_format($group_price_list[6]['price'] - $user_info->user_deposit, 2) ?></em></span><a href="/recharge/deposit" target="_blank" style="color:#219bda; margin-left:10px; padding:0;">前去充值&nbsp;&gt;</a></p>
                <span class="j-goto-pay <?= (floatval($user_info->user_deposit) < $group_price_list[6]['price']) ? 'hide' : '' ?>">
                        <div class="col-xs-10 text-right">&nbsp;</div>
                        <div class="col-xs-2">
                            <button  type="button" class="btn btn-blue j-pay-btn" data-loading-text="Loading..." autocomplete="off"  style='margin-left: 0;background-color: #ff6b71;border: 1px solid #ff6b71;'>确认付款</button>
                        </div>
                    </span>
            </div>
        </div>
    </div>-->
        </div>
    </div>
    <!-- 充值弹框提示 -->
    <div class="modal small fade show-tips" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
        <div class="modal-dialog" role="document">
            <div class="modalImg" ><img src="/static/imgs/trade/taoke_tips.png"></div>
            <div class="modal-content" >
                <div class="modal-body text-center">
                    <p>您将充值<span class="color_red">6</span>个月会员，注意会员一经充值概不退款噢！！！</p>
                </div>
                <div class="btns">
                    <button type="button" class="btn btn-primary">充值</button>
                    <button type="button" class="btn" data-dismiss="modal">不充值</button>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
    <script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/toast/toastr.min.js"></script>
    <script type="text/javascript">

        var id = 6;;
        $(function(){
            var _expire_time = $('#expire_time').val();
            $('.row').find('.date_time_end').text(addDate(_expire_time, 6));
            var user_deposit = parseFloat('<?= $user_info->user_deposit ?>');
            $('.bg_box').click(function(d,index){
                var $this = $(this), _id = $this.attr('id'), _val = parseFloat($this.attr('val'));
                id = _id;
                var _row = $('.row');
                _row.find('.j-date-time').text(_id);
                _row.find('.j-pay-amount').text(_val);
                _row.find('.date_time_end').text(addDate(_expire_time, _id));
                document.getElementsByClassName('color_red')[0].text = _id;
                $('.j-pay-amount').text(_val.toFixed(2));
                $('.bg_box').removeClass('active')
                $this.addClass('active')//.parent('div').siblings('div').firstChild('div').removeClass('active');
                if (_val > user_deposit) {
                    _row.find('.points_pay_number').text((_val - user_deposit).toFixed(2));
                    _row.find('.j-lack-deposit').removeClass('hide');
                    _row.find('.j-goto-pay').addClass('hide');
                } else {
                    _row.find('.j-lack-deposit').addClass('hide');
                    _row.find('.j-goto-pay').removeClass('hide');
                }
            });
            // 确认付款
            $('.j-pay-btn').click(function (e) {
                var payvip =  parseInt($('.time_choose').find('div.active').attr('id'));
                var _modal = $('#myModal');
                _modal.find('.modal-body .color_red').text(payvip).data('payvip', payvip);
                _modal.modal({backdrop: 'static', keyboard: false});
                document.getElementsByClassName('color_red')[0].innerText = id;
                console.log(id)
            });
            $('#myModal').on('click', '.btn-primary', function () {
                var _modal = $('#myModal');
                var payvip =  parseInt(_modal.find('.modal-body .color_red').data('payvip'));
                document.getElementsByClassName('color_red').innerText = id;
                $.post('/group/pay_group_submit', {payvip: payvip}, function (data) {
                    if (data.status == 1) {
                        _modal.modal('hide');
                        toastr.info('操作成功');
                        setTimeout(function (e) {
                            location.href = data.url;
                        }, 2000);
                    } else {
                        toastr.error(data.message);
                    }
                }, 'json');
            })
        });

        function addDate(date, months) {
            var d = new Date(date);
            var _year = Math.floor(months / 12);
            var _month = parseInt(months % 12);
            d.setMonth(d.getMonth() + _month);
            d.setYear(d.getYear() + _year + 1900);
            var _m = d.getMonth() + 1, _d = d.getDate();
            if (_m < 10) {
                _m = '0' + _m;
            }
            if (_d < 10) {
                _d = '0' + _d;
            }
            return d.getFullYear() + '-' + _m + '-' + _d;
        }
    </script>
</body>
</html>