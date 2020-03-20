<!DOCTYPE html>
<html lang="zh-CN">
<head>
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
				width: 168px;
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
			.left_tab{
				margin-top: 10px;
				margin-right: 20px;
			}
			.right_center{
				margin-top: 2px;
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
				height: 192px;
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
			    width: 100%;
			    height: 63px;
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
				margin-top: 0px;
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
			    padding: 0px 5px 0 32px;
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
				background-color: #FF6347;
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
			#myTab_two{}
			.nav{} .nav-tabs{} .myTab{}
			.active a{
				color: white;
				background-color: #ff6b71;
			}
		</style>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>"/>
    <link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>"/>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/static/css/business_center.css?v=<?= VERSION_TXT ?>"/>
    <title>商家个人中心-<?php echo PROJECT_NAME; ?></title>
</head>
<body style="overflow-x: hidden;background: #f0f0f0;">
<?php $this->load->view("/common/top1", ['site' => 'index']); ?>
<div style="width: 1170px;margin: auto;display: block;">
	<div class="left_tab">
			<div class="left_tab_person">
				<img src='/static/imgs/icon/head.png' />
				<p><?= $user_info->nickname; ?></p>
			</div>
			<ul class="left_tab_nav">
				<li class="left_tab_nav_head">
					<img style="vertical-align: middle;" src="/static/imgs/icon/zhgl.png" />
					账户管理
				</li>
				<li><a href="/">个人中心</a></li>
				<li><a href="/center/bind/taobao">绑定店铺</a></li>
				<li><a href="/center/user_info">基本信息</a></li>
				<li class="left_tab_nav_head">
					<img style="vertical-align: middle;" src="/static/imgs/icon/cwgl.png" />
					财务管理
				</li>
				<li><a href="/recharge/deposit">充值押金</a></li>
				<li><a href="/recharge/point">充值金币</a></li>
				<li><a href="/center/record_list/5">充值明细</a></li>
				<li><a href="/group/pay_group">续费会员</a></li>
				<li><a href="/center/record_list">资金明细</a></li>
				<li><a href="/withdrawal/deposit">押金提现</a></li>
				<li class="left_tab_nav_head">
					<img style="vertical-align: middle;" src="/static/imgs/icon/cwgl.png" />
					活动管理
				</li>
				<li><a href="/trade/step">报名活动</a></li>
				<li><a href="/center/trade_finished">已完成活动</a></li>
				<li><a href="/review/traffic_list">审核截图</a></li>
				<li class="left_tab_nav_head">
					<img style="vertical-align: middle;" src="/static/imgs/icon/cwgl.png" />
					邀请管理
				</li>
				<li><a href="/invite/invite_record">邀请记录</a></li>
				<li><a href="/invite/invite_reward">奖励记录</a></li>
				<li><a href="/invite/failure_reward">邀请详情</a></li>
				<li><a href="/invite/invite_url">邀请返利</a></li>
			</ul>
		</div>
<div class="right_center">
    <ul class="right_center_top">
        <li class="right_center_top_left">
        	<div style="float: left;margin-top: 10px;">
				<img style="vertical-align: middle;" src="/static/imgs/icon/user.png"/>
			</div>
			<div class="right_center_top_left1">
				<p>用户名：<span class="color-font"><?= $user_info->nickname; ?></span></p>
				<p>会员等级：<span class="color-font"><?= ($is_vip) ? 'VIP会员' : '普通会员'; ?></span></p>
				<p>会员到期时间：
					<span class="color-font"><?= date('Y-m-d', $user_info->expire_time) ?></span>
				</p>
				<a class="color-font2" href="/group/pay_group">续费会员</a>
			</div>
            <!--<div class="row">
                <div class="col-xs-3"><img src="/static/imgs/icon/m2x.png"/></div>
                <div class="col-xs-9">
                    <p>ID：<?= $user_info->nickname; ?></p>
                    <p>会员等级111：<?= ($is_vip) ? 'VIP会员' : '普通会员'; ?></p>
                </div>
            </div>
            <div class="grade_info">
                <h4><span>安全等级：</span></h4>
                <?php if ($safety_level['account_info']): ?>
                    <a class="grade_icon visa" href="javascript:;" title="已绑定银行卡"><span class="glyphicon glyphicon-credit-card" aria-hidden="true" style="font-size:23px"></span></a>
                <?php else: ?>
                    <a class="grade_icon visa disabled" href="/center/withdrawal_info?left_list_id=2" title="未绑定银行卡"><span class="glyphicon glyphicon-credit-card" aria-hidden="true" style="font-size:23px"></span></a>
                <?php endif ?>
                <?php if ($user_info->mobile_ciphertext): ?>
                    <a class="grade_icon phone" href="javascript:;" title="已绑定手机"><span class="glyphicon glyphicon-phone" aria-hidden="true" style="font-size:23px"></span></a>
                <?php else: ?>
                    <a class="grade_icon phone disabled" href="/center/user_info" title="未绑定手机"><span class="glyphicon glyphicon-phone" aria-hidden="true" style="font-size:23px"></span></a>
                <?php endif ?>
                <?php if ($user_info->trade_password): ?>
                    <a class="grade_icon money" href="javascript:;" title="已设置提现密码"><span class="glyphicon glyphicon-lock" aria-hidden="true" style="font-size:23px"></span></a>
                <?php else: ?>
                    <a class="grade_icon money disabled" href="/center/user_info" title="未设置提现密码"><span class="glyphicon glyphicon-lock" aria-hidden="true" style="font-size:23px"></span></a>
                <?php endif ?>
            </div>
            <?php if ($is_vip) { ?>
                <p>到期时间：<?= date('Y-m-d', $user_info->expire_time) ?><a href="/group/pay_group" class="blue pull-right">续费会员&nbsp;>></a>
                </p>
            <?php } else { ?>
                <p><a href="/group/pay_group" class="blue pull-right">开通会员&nbsp;&gt;&gt;</a></p>
            <?php } ?>-->
        </li>
        <li class="right_center_top_mid">
					<div class="right_center_top_mid1">
						<div class="cons">
							<div class="cons_top">
								<span><?= $deposit_vars->user_deposit ?></span>
								<strong>可用押金</strong>
							</div>
						</div>
						<div class="cons_bottom">
							<a href="/recharge/deposit">充值押金</a>
						</div>
					</div>
					<div class="right_center_top_mid1">
						<div class="cons">
							<div class="cons_top">
								<span><?= $deposit_vars->total_deposit ?></span>
								<strong>总押金</strong>
							</div>
						</div>
						<div class="cons_bottom">
							<a href="/recharge/point">充值金币</a>
						</div>
					</div>
					<div class="right_center_top_mid1">
						<div class="cons">
							<div class="cons_top">
								<span><?= $deposit_vars->trade_frozen_deposit + $deposit_vars->with_frozen_deposit ?></span>
								<strong>冻结押金</strong>
							</div>
						</div>
						<div class="cons_bottom">
							<a></a>
						</div>
					</div>
            <!--<div class="tit"><h5><img src="/static/imgs/icon/t1.png"/>押金<a href="/recharge/deposit" target="_blank">充值押金&nbsp;>></a></h5></div>
            <div class="loadingParent">
             <canvas id="canvasIndex1" style="width:100px;height:100px;" class="pull-left"></canvas>
            <div>
            <div class="pull-left coin_div">
                <h4>可用押金：<span class="red"><?= $deposit_vars->user_deposit ?></span><small>元</small></h4>
                <p>总押金：<span class="red"><?= $deposit_vars->total_deposit ?><small>元</small></span></p>
                <p>冻结押金：<span class="red"><?= $deposit_vars->trade_frozen_deposit + $deposit_vars->with_frozen_deposit ?><small>元</small></span></p>
            </div>-->
        </li>
        
        <li class="right_center_top_right">
        	<a href="/trade/step" href="">报名活动</a>
            <!--<div class="tit"><h5><img src="/static/imgs/icon/t2.png"/>金币<a href="/recharge/point" target="_blank">充值金币&nbsp;>></a></h5></div>
            <h4>可用金币：<span class="red"><?= $user_info->user_point ?></span></h4>
            <a href="/trade/step" class="trade_click">报名活动</a>-->
        </li>
    </ul>
<!--    <div class="banner">-->
<!--				<a   href="/invite/invite_url"><img style="width: 932px;" src="/static/imgs/icon/banner.jpg" /></a>-->
<!--	</div>-->
    <div class="box_2 my_task">
    	<div class="tit_box">
					<p class="tit">我的任务</p>
		</div>
        <?php $status_0 = 0; $status_1 = 0; $status_2 = 0; $status_3 = 0; $status_4 = 0; $status_99 = 0; $traffic_1 = 0; $refunds = 0; ?>
        <?php foreach ($status_list as $item) { $status_2 += intval($item['status_2']); $status_4 += intval($item['status_4']); $status_0 += intval($item['status_0']); $status_1 += intval($item['status_1']); $status_3 += intval($item['status_3']); $status_99 += intval($item['status_99']);  $refunds += intval($item['refunds']); } ?>
        <ul style="height: 65px;">
            <li><a href="/review/refund_order_list"><img src="/static/imgs/icon/task_1.png" alt=""><div><p style="margin: 0;">退款订单</p><span class="<?= $refunds ? 'red' : ''; ?>"><?= $refunds ?></span></div></a></li>
            <li><a href="/review/order_list/4?plat_id=<?= $k; ?>"><img src="/static/imgs/icon/task_2.png" alt=""><div><p style="margin: 0;">待发货</p><span class="<?= $status_2 ? 'red' : ''; ?>"><?= $status_2 ?></span></div></a></li>
            <li><a href="/review/refund_list/?plat_id=<?= $k; ?>"><img src="/static/imgs/icon/task_3.png" alt=""><div><p style="margin: 0;">待退款</p><span class="<?= $status_4 ? 'red' : ''; ?>"><?= $status_4 ?></span></div></a></li>
            <li><a href="/review/order_list/2?plat_id=<?= $k; ?>"><img src="/static/imgs/icon/task_4.png" alt=""><div><p style="margin: 0;">已接手</p><span class="<?= $status_0 ? 'red' : ''; ?>"><?= $status_0 ?></span></div></a></li>
            <!--<li><a href="#wait-print" data-toggle="tab"><img src="/static/imgs/icon/task_5.png" alt=""><div><p style="margin: 0;">退款订单</p><span class="<?= $refunds ? 'red' : ''; ?>"><?= $refunds ?></span></div>已下单，待打印快递单（<span class="<?= $status_1 ? 'red' : ''; ?>"><?= $status_1 ?></span>）</a></li>-->
            <!--<li><a href="#send-out" data-toggle="tab"><img src="/static/imgs/icon/task_5.png" alt=""><div><p style="margin: 0;">已发货</p><span class="<?= $status_3 ? 'red' : ''; ?>"><?= $status_3 ?></span></div></a></li>-->
            <!-- <li><a href="#time-out" data-toggle="tab">超时未提交（<span class="--><?//= $status_99 ? 'red' : ''; ?><!--">--><?//= $status_99 ?><!--</span>）</a></li> -->
            <li><a href="/review/traffic_list"><img src="/static/imgs/icon/task_5.png" alt=""><div><p style="margin: 0;">流量订单</p><span class="<?= $traffic_num ? 'red' : ''; ?>"><?= $traffic_num ?></span></div></a></li>
        </ul>
        <!--<div class="tab-content">
            <div class="tab-pane fade tast-tab-pane" id="fh_task">
                <h6 class="f14">买手已付款，待发货<span class="black">（快递单每天打印时间为9:00至21:00，请在打印出来24小时内操作发货，否则会导致物流不更新且平台上会默认48小时后操作发货）</span></h6>
                <?php if ($status_2 > 0): ?>
                <ul class="list-inline">
                    <?php foreach ($status_list as $k => $item): ?>
                        <li>
                            <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                            <span><?= $item['pname'] ?> <a href="/review/order_list/4?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_2'] ?></span>)</span></a></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <div class="tab-pane fade tast-tab-pane" id="tk_task">
                <h6 class="f14">买手已收货，待退款<span class="black">（请在48小时内操作退款，否则将扣除活动押金中的退款保证金）</span></h6>
                <?php if ($status_4 > 0): ?>
                <ul class="list-inline">
                    <?php foreach ($status_list as $k => $item): ?>
                        <li>
                            <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                            <span><?= $item['pname'] ?> <a href="/review/refund_list/?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_4'] ?></span>)</span></a></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <div class="tab-pane fade tast-tab-pane" id="wait-pay">
                <h6 class="f14">买手已接手，待下单<span class="black">（买手已接单，预计2小时内完成）</span></h6>
                <?php if ($status_0 > 0): ?>
                <ul class="list-inline">
                    <?php foreach ($status_list as $k => $item): ?>
                        <li>
                            <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                            <span><?= $item['pname'] ?> <a href="/review/order_list/2?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_0'] ?></span>)</span></a></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>  
            <div class="tab-pane fade tast-tab-pane" id="wait-print">
                <h6 class="f14">买手已下单，待打印快递单<span class="black">（快递单预计2小时内打印完成 （快递打印时间为9:00-21点，请耐心等待））</span></h6>
                <?php if ($status_1 > 0): ?>
                <ul class="list-inline">
                    <?php foreach ($status_list as $k => $item): ?>
                        <li>
                            <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                            <span><?= $item['pname'] ?> <a href="/review/order_list/3?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_1'] ?></span>)</span></a></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <div class="tab-pane fade tast-tab-pane" id="send-out">
                <h6  class="f14">已发货<span class="black"></span></h6>
                <?php if ($status_3 > 0): ?>
                    <ul class="list-inline">
                        <?php foreach ($status_list as $k => $item): ?>
                            <li>
                                <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                <span><?= $item['pname'] ?> <a href="/review/order_list/5?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_3'] ?></span>)</span></a></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <div class="tab-pane fade tast-tab-pane" id="time-out">
                <h6 class="f14">买手超时未提交订单<span class="black">（买家接到任务没有完成提交且主动放弃,2小时候任务自动超时,请仔细核对,防止买家已经真实下单,导致真实发出）</span></h6>
                <?php if ($status_99 > 0): ?>
                    <ul class="list-inline">
                        <?php foreach ($status_list as $k => $item): ?>
                            <li>
                                <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                <span><?= $item['pname'] ?> <a href="/review/order_list/6?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_99'] ?></span>)</span></a></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <div class="tab-pane fade tast-tab-pane" id="traffic">
                <h6 class="f14">已提交、待审核流量订单<span class="black">（买家提交的截图,客服审核通过系统只保留七天,七天之后系统自动删除,无法再次查看，审核不通过的订单截图单笔任务金币会在任务结束以后进行结算返还至您的账户，如有问题请及时联系在线客服。）</span></h6>
                <?php if ($traffic_1 > 0): ?>
                    <ul class="list-inline">
                        <?php foreach ($status_list as $k => $item): ?>
                            <li>
                                <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                <span><?= $item['pname'] ?> <a href="/review/traffic_list/1?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['traffic_1'] ?></span>)</span></a></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
            </div>
            <!-- 退款订单 -->
            <!--<div class="tab-pane fade in active tast-tab-pane" id="refunds">
                <h6 class="f14">退款订单<span class="black">（买家已在淘宝申请了退款，并上传退款截图，请及时确认）</span></h6>
                <?php if ($refunds > 0): ?>
                    <ul class="list-inline">
                        <?php foreach ($status_list as $k => $item): ?>
                            <li>
                                <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                <span><?= $item['pname'] ?> <a href="/review/refund_order_list/4?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['refunds'] ?></span>)</span></a></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?><a href="/review/refund_order_list" target="_blank"><div style="padding: 12px 25%;font-size:18px;">查看退款订单</div></a><?php endif; ?>
            </div>-->
        <!--</div>-->
    </div>
</div>
<div class="contain my_task" style="float: left;width: 947px;">
	<div class="tit_box">
					<p class="tit">审核任务</p>
				</div>
    <iframe src="/frame/trade_list_frame" id="trade_list" frameborder="0" scrolling="no" width="100%"></iframe>
</div>
</div>
<!--<footer style="width: 100%;float: left;margin-top: 64px;">
    <p>Copyright (c) 2016 Inc. All Rights. 京ICP备17021741号-1</p>
    <p>版权所有 版权所有公司属于 北京昊佳有限公司</p>
</footer>-->
<?php $this->load->view("/common/footer"); ?>
<!--<div class="floatwindow" style="display: <?= ($show_tips) ? 'none':'block'; ?>">
    <div class="floatwindowimg"><img src="/static/imgs/icon/floatwindowimg.png"/></div>
    <a href="/invite/invite_url" target="_blank"><div class="floatwindownav f20">邀请好友入驻,坐享高额收益 ; 月入过万不是梦,赶紧行动吧!</div></a>
    <a href="javascript:;" class="hidebtn">X</a>
</div>-->
</body>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/js/angular.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript" src="/static/js/application.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript" src="/static/js/index.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript" src="/static/js/modernizr-2.6.2.min.js"></script>
<script type="text/javascript" src="/static/js/cycle.js"></script>
<script type="text/javascript">
// 圆环进度条
$(function () {
    var w = $(".loadingParent").width();
    var option2 = {
        percent: '<?= ($deposit_vars->total_deposit > 0) ? ($deposit_vars->user_deposit * 100 / $deposit_vars->total_deposit) : 0 ?>',   //百分比数值
        w: 200,          //宽度
        oneCircle: "ture"  //是否是整个圆  默认半圆
    };
    $("#canvasIndex1").audios2(option2);
    // 邀请好友入驻 colse
    $(".hidebtn").click(function () {
        $(this).parent().remove();
    });
});
</script>
</html>