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
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/toast/toastr.min.js"></script>
<title>充值金币-<?php echo PROJECT_NAME; ?></title>
<style type="text/css">
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
		padding-left: 10px;
		background-color: white;
	    font-size: 16px;
	    color: #666;
	    padding-top: 50px;
	    padding-bottom: 85px;
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
				background-repeat: no-repeat;
				background-image: url(/static/imgs/icon/menbers_sel_ed_bg.png);
			}
			.p_tit {
    border-left: 3px solid #ff464e;
    padding-left: 10px;
    font-size: 20px;
    color: #333;
}
.p_tit span {
    font-size: 16px;
    color: #666;
}
</style>
</head>
<body>
    <?php $this->load->view("/common/top1", ['site' => 'recode']); ?>
    <div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
    <div class="contain" style="min-height: 71vh;width: 947px;float: left; margin-top: 10px;">
    	    	<div class="recharge_right">
    		
    	<div class="rest_time">
			<div class="tit_box">
						<p class="p_tit">充值套餐(推荐)：<span> 金币为平台的虚拟货币，可用来支付任务佣金、增值服务等，不可抵用押金，不可提现</span></p>
			</div>
			<div class="time_choose">
						<div id="1" val="100" class="layui_col_md4">
							<div class="bg_box" val="100">
								<strong class="user_vip_month">100个</strong>
								<p><span class="user_vip_money">100</span>元</p>
								<div class="count">
									<span>新人推荐</span>
								</div>
							</div>
						</div>
						<div id="2" val="500" class="layui_col_md4">
							<div class="bg_box active" val="500">
								<strong class="user_vip_month">500个</strong>
								<p><span class="user_vip_money">500</span>元</p>
								<div class="count">
									<span>金币包</span>
								</div>
							</div>
						</div>
						<div id="3" val="1000" class="layui_col_md4">
							<div class="bg_box" val="1000">
								<strong class="user_vip_month">1000个</strong>
								<p><span class="user_vip_money">1000</span>元</p>
								<div class="count">
									<span>金币包</span>
								</div>
							</div>
						</div>
						<div id="4" val="2000" class="layui_col_md4">
							<div class="bg_box" val="2000">
								<strong class="user_vip_month">2000个</strong>
								<p><span class="user_vip_money">2000</span>元</p>
								<div class="count">
									<span>金币包</span>
								</div>
							</div>
						</div>
<!-- 						<div class="layui_col_md4">
							<div class="bg_box">
								<strong class="user_vip_month">暂无</strong>
								<p><span class="user_vip_money">0</span>元</p>
								<div class="count">
									<span>活动专用</span>
								</div>
							</div>
						</div> -->
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
						<div class="col-xs-6">使用押金支付</div>
                    	<div class="col-xs-6 text-right f18">需支付 <span class="text-danger j-pay-amount">500</span> 元</div>
						<span class="j-goto-pay <?= (floatval($user_info->user_deposit) < 500) ? 'hide' : '' ?>">
						<input type="submit" id="" submitbutton="true" value="确认支付" class="recharge_tijiao j-pay-btn">
						</span>
					</div>
			</div>
    	</div>
    	</div>
</div>
        <div class="vip_box">
            <div class="vip_type">
                <div class="row">
                    <p class="j-lack-deposit text-right <?= (floatval($user_info->user_deposit) >= 500) ? 'hide' : '' ?>" style="height: 40px; line-height: 40px; font-size:16px;"><span class="deposit_ande_point">押金不足，还差<em class="points_pay_number" style="color:#f00;"><?= number_format(500 - $user_info->user_deposit, 2) ?></em></span><a href="/recharge/deposit" target="_blank" style="color:#219bda; margin-left:10px; padding:0;">前去充值&nbsp;&gt;</a></p>
                </div>
            </div>
        </div>
        					<div class="recharge_dec">
							<p><span>温馨提示</span></p>
							<p>1.金币作为网站通货使用，用于支付活动佣金以及平台服务费等，不可提现，不可以充值会员使用。 </p>
							<p>2.线下支付完成后，请提供转账截图，充值账号及时联系客服，以便能快速帮您充值。</p>
							<p>3.如果支付中遇到其他问题，请联系在线客服咨询</p><br>
						</div>
    </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
</body>
<script type="text/javascript">
    $(function(){
        var user_deposit = parseFloat('<?= $user_info->user_deposit ?>');
        $('.layui_col_md4').click(function(){
            var $this = $(this), _id = $this.attr('id'), _val = parseFloat($this.attr('val'));
            console.log(_id)
            var _row = $('.row');
            _row.find('.text-danger').text(_val.toFixed(2));
            $('.j-pay-amount').text(_val.toFixed(2));
            $('.bg_box').removeClass('active')
            $this.find('.bg_box').addClass('active');
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
            var $this = $(this);
            var recharge_point =  parseInt($('.time_choose').find('div.active').attr('val'));
            console.log(recharge_point)
            $.post('/recharge/point_submit', {point: recharge_point}, function (data) {
                if (data.status == 1) {
                    toastr.info(data.msg);
                    setTimeout(function (e) {
                        location.href = '/center';
                    }, 2000);
                } else {
                    toastr.error(data.msg);
                    return false;
                }
            }, 'json');
        });
    })
</script>
</html>