<link rel="stylesheet" href="/static/css/flat-ui.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/layout.css?v=<?= VERSION_TXT ?>" />
<style>
			body{
				background-color: #f0f0f0;
			}
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
				width: 1200px;
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
			.nav_head .navbar-default .navbar-nav>li>a {
			    color: black;
			}
			.nav_head_b .nav>li>a{
				font-size: 18px;
				margin: 0 0px;
			}
			.nav_head_b {
				background: white;
			}
			.navbar-default .navbar-nav>.open>a, .navbar-default .navbar-nav>.open>a:focus, .navbar-default .navbar-nav>.open>a:hover {
			    background-color: transparent;
			    color: red;
			}
			.nav_head .navbar-default .navbar-nav > .dropdown > a .caret {
			    border-top-color: black;
			    border-bottom-color: black;
			}
			.nav_head{
				background: url(img/logo.png) no-repeat;
			}
			.right ul li{
				background-color: black;
			}
			.right ul li p{
				color: white;
			}
			.nav_head .navbar-default .navbar-nav>li>a {
			    color: black;
			}
			/*.active:hover{
				color: red;
			}*/
			.navbar-lg .navbar-nav>li>a {
			    padding-top: 35px;
			}
			
</style>
<header class="nav_head">
    <div class="nav_head_t">
    	<div class="contain_top">
					<div class="contain_one">
						<div class="contain_layui_col1">
							<span>你好，<?= $this->session->userdata('mobile'); ?></span>
							<span class="vertical"></span>
							<span><a class="" href="/user/logout">退出</span></a>
						</div>
						<div class="contain_layui_col2">
							<div class="contain_layui_col2left">
								<img style="vertical-align: middle;" src="img/vip.png" alt="">
								<span>普通会员   </span>
								<span>会员到期时间：<?= date('Y-m-d', $user_info->expire_time) ?></span>
								<a class="color-font2" href="/group/pay_group">[续费]</a>
							</div>
							<div class="contain_layui_col2mid">
								<span class="padd-right">可用押金：<?= $user_info->user_deposit*1; ?></span>
								<span>可用金币：<?= $user_info->user_point*1; ?></span>
							</div>
							<div class="contain_layui_col2right">
								<a class="color-font2" href="/recharge/deposit">充值押金</a>
								<span class="vertical"></span>
								<a class="color-font2" href="/recharge/point">充值金币</a>
							</div>
						</div>
					</div>
				</div>
        <!--<div class="contain">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/" style="padding: 12px 15px;"><img src="/static/imgs/logo.png" alt="logo" /></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/recharge/deposit">押金：<?= $user_info->user_deposit*1; ?></a></li>
                    <li><a href="/recharge/point">金币：<?= $user_info->user_point*1; ?></a></li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?= $this->session->userdata('mobile'); ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/center/user_info">账号设置</a></li>
                            <li><a href="/center/withdrawal_info">提现账号管理</a></li>
                        </ul>
                    </li>
                    <li><a href="/user/logout">退出1</a></li>
                </ul>
            </nav>
        </div>-->
    </div>
    <div style="background-color: white;">
    	

    <div class="nav_head_b contain">
        <nav class="navbar navbar-default navbar-lg" role="navigation">
        	<img style="width: 400px;height: 70px;float: left;margin: 12px;" src="/static/imgs/icon/jinpai.png">
            <ul class="nav navbar-nav navbar-left" style="margin-left: 80px;">
                <li class="<?= ($site=='index') ? 'active': '' ?>" style = 'height: 94px;'><a href="/">首页</a></li>
                <li class="<?= ($site=='trade') ? 'active': '' ?>" style = 'height: 94px;'><a href="/trade/step">报名活动</a></li>
                <li class="<?= ($site=='trade') ? 'active': '' ?>" style = 'height: 94px;'><a href="/invite/invite_url" target="_blank">邀请返利</a></li>
                <!--<li class="dropdown <?= ($site=='manage') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">活动管理<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/trade/step">报名活动</a></li>
                        <li><a href="/center/trade_finished">已完成的活动</a></li>
                        <?php foreach ($review_plat_cnts as $k=>$v): ?>
                        <li><a href="/center/trade_manage?plat_id=<?= $k; ?>"><?= $v['pname']; ?>待处理的 (<?= $v['cnt']; ?>)</a></li>
                        <?php endforeach; ?>
                        <li><a href="/review/traffic_list">流量订单</a></li>
                        <li><a href="/center/bind">+绑定店铺</a></li>
                    </ul>
                </li>
                <li class="dropdown <?= ($site=='recode') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">资金记录<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/recharge/deposit">充值押金</a></li>
                        <li><a href="/recharge/point">充值金币</a></li>
                        <li><a href="/group/pay_group"><?= $is_vip ? '续费会员':'开通会员'; ?></a></li>
                        <li><a href="/center/record_list">押金记录</a></li>
                        <li><a href="/center/record_list/3">金币记录</a></li>
                        <li><a href="/center/record_list/2">提现记录</a></li>
                        <li><a href="/withdrawal/deposit">提现</a></li>
                    </ul>
                </li>
                <li class="dropdown <?= ($site=='invite') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">邀请会员<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/invite/invite_url" target="_blank">邀请会员</a></li>
                        <li><a href="/invite/invite_record">邀请会员记录</a></li>
                        <li><a href="/invite/invite_reward">活动奖励记录</a></li>
                        <li><a href="/invite/failure_reward">失效的奖励</a></li>
                    </ul>
                </li>
                <li class="<?= ($site=='member') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">个人信息<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/center/user_info">账号设置</a></li>
                        <li><a href="/center/withdrawal_info">提现账号管理</a></li>
                    </ul>
                </li>-->
                <li class="<?= ($site=='finance') ? 'active': '' ?>" style = 'height: 94px;'><a href="/finance/summary">账房</a></li>
                <li class="<?= ($site=='traffic') ? 'active': '' ?>" style = 'height: 94px;'><a href="/review/traffic_list">流量截图</a></li>
                <li><a target="_blank" href="<?= POST_SERVICE_URL ?>">发快递</a></li>
            </ul>
        </nav>
    </div>
</div>
</header>
<!-- 右则通知、消息 -->
<div class="right">
    <ul>
        <li><img src="/static/imgs/icon/notice.png"/></li>
        <li><p>平台</p><p>公告</p></li>
        <li><p>常见</p><p>问题</p></li>
        <!--<li><a href="http://www.baidu.com"><p>联系</p><p>客服</p></a></li>-->
        <li class="bdqrcode"><a href="javascript:;" target="_blank"><p>联系</p><p>客服</p></a><img src="/static/imgs/ke.jpg" alt="联系客服" /></li>
        <li class="bdqrcode"><a href="javascript:;" target="_blank"><p>联系</p><p>运营</p></a><img src="/static/imgs/bd_qrcode.jpg" alt="联系运营" /></li>
        <li class="top"><span class="glyphicon glyphicon-chevron-up"></span></li>
        <div>
            <div class="questions1 questions"></div>
            <div class="questions1 questions">
                <?php foreach ($bus_notice_list as $key => $item): ?>
                    <p class="ellipsis"><a href="<?= $item->url ?>" target="_blank"><?= ($key + 1) . '、' . $item->contents; ?></a></p>
                <?php endforeach; ?>
            </div>
            <div class="questions2 questions">
                <?php foreach ($bus_question_list as $key => $item): ?>
                    <p class="ellipsis"><a href="<?= $item->url ?>" target="_blank"><?= ($key + 1) . '、' . $item->contents; ?></a></p>
                <?php endforeach; ?>
            </div>
        </div>
    </ul>
</div>
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript">
    $(function () {
        // 右则导航
        $('.right ul li').hover(function(){
            var index=$(this).index();
            $($('.questions')[index]).fadeIn(300).siblings().fadeOut();
        });
        $('.questions').mouseleave(function(){
            $(this).fadeOut(300);
        });
        $('.right .bdqrcode').mouseenter(function(){
            $(this).find('img').show();
        });
        $('.right .bdqrcode').mouseleave(function () {
            $(this).find('img').hide();
        });
        //回到顶部
        $(window).scroll(function() {
            var t = $(this).scrollTop();
            if (t > 300) {
                $(".top").stop().css('visibility','visible');
            } else {
                $(".top").stop().css('visibility','hidden');
            }
        });
        $(".right .top").click(function() {
            $("body,html").stop().animate({
                scrollTop: 0
            }, 300);
        });
    });
</script>
