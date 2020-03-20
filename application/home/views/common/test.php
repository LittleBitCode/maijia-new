<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
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
				margin-top: 10px;
				margin-right: 20px;
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
			#myTab_two.nav-tabs>li.active>a, #myTab_two.nav-tabs>li>a:hover {
			    background-color: #ed702c;
			    border-top: 3px solid #ed702c;
			    font-size: 16px;
			    color: white;
			}
			#myTab_two.nav-tabs{
				background-color: white;
				border-bottom: 1px solid #ccc;
			}
		</style>
	</head>
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
<!--				<li class="left_tab_nav_head">-->
<!--					<img style="vertical-align: middle;" src="/static/imgs/icon/cwgl.png" />-->
<!--					邀请管理-->
<!--				</li>-->
<!--				<li><a href="/invite/invite_record">邀请记录</a></li>-->
<!--				<li><a href="/invite/invite_reward">奖励记录</a></li>-->
<!--				<li><a href="/invite/failure_reward">邀请详情</a></li>-->
<!--				<li><a href="/invite/invite_url">邀请返利</a></li>-->
			</ul>
		</div>
		


			
			