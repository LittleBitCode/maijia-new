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
			    height: 105px;
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
				margin-left: 30px;
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
			.active:hover{
				color: red;
			}
			.right{
				box-shadow:none;
				background: none;
			}
			.right ul li{
				margin-left: 120px;
			}
			.login_view{
				background:none;
				opacity: 1;
				min-height:407px;
				margin-left: 60%;
			}
			.main_box {
			    min-height: 759px;
		    }
		    .btn-blues, .btn-blues:hover, .btn-blues:active, .btn-blues:focus {
			    background-color: #FF6347;
			    color: #ffffff;
			}
</style>
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
<!-- 右则通知、消息 -->
<div class="right" style="width: 200px;">
    <ul>
        <li><img src="/static/imgs/icon/notice.png"/></li>
        <!--<li><p>平台</p><p>公告</p></li>
        <li><p>常见</p><p>问题</p></li>-->
        <!--<li><a href="www.baidu.com" target="_blank"><p>联系</p><p>客服</p></a></li>-->
        <li class="bdqrcode"><a href="javascript:;" target="_blank"><p>联系</p><p>客服</p></a><img src="/static/imgs/ke.jpg" alt="联系客服" /></li>
        <li class="bdqrcode"><a href="javascript:;" target="_blank"><p>联系</p><p>运营</p></a><img src="/static/imgs/bd_qrcode.jpg" alt="联系运营" /></li>
        <li class="top"><span class="glyphicon glyphicon-chevron-up"></span></li>
        <!--<div>
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
        </div>-->
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
