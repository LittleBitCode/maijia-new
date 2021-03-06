<!DOCTYPE html>
<html>
	<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico">
    <link rel="stylesheet" href="/static/css/common.css">
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/layout.css">
    <link rel="stylesheet" href="/static/css/invite_20190315.css">
    <link rel="stylesheet" href="/static/toast/toastr.min.css"/>
    <title>邀请会员-<?php echo PROJECT_NAME; ?></title>
    <style type="text/css">
    	*{
	padding: 0px;
	margin: 0px;
}

.yqfl-font{
	width: 1094px;
    margin: -40px auto 0;
    position: relative;
    box-shadow: 1px 1px 9px 0px;
    background: #fff;
    border-radius: 25px;
    margin-bottom: 100px;
}
.yqfl-head{
    background-image: url(../static/imgs/icon/yaoqingfl_back.png);
    background-size: 100%;
    width: 1094px;
    height: 128px;
    line-height: 82px;
    font-size: 38px;
    color: #fff;
    text-align: center;
}
.yqfl-head2{
	color: #2a79d1;
    font-size: 32px;
    width: 516px;
    margin: 0 auto;
}
.yqfl-font-p{
	font-size: 22px;
    color: #333333;
    text-align: center;
    padding: 30px 0 36px;
}
.yqfl-font-copy{
    width: 1000px;
    margin: 0 auto;
    overflow: hidden;
}
.yqfl-font-copy>input:last-child {
    width: 185px;
    height: 80px;
    line-height: 80px;
    font-size: 24px;
    color: #fff;
    text-align: center;
    background: #e73c3a;
    border-radius: 10px;
    cursor: pointer;
    display: inline-block;
    float: left;
}
.yqfl-font-copy>input:first-child {
    display: inline-block;
    float: left;
    width: 760px;
    height: 80px;
    background: #f5f5f5;
    font-size: 22px;
    color: #999;
    padding: 10px 20px 0;
    border-radius: 10px;
    word-wrap: break-word;
    text-align: center;
    overflow: hidden;
}
.yqfl-rule{
	width: 494px;
    margin: 0 auto;
    overflow: hidden;
}
.yqfl-rule>div {
    float: left;
    text-align: center;
    font-size: 21px;
    color: #333;
    line-height: 63px;
}
.yqfl-rule>div:nth-child(2) {
    margin: 30px 115px;
}
img {
    border: none;
    vertical-align: middle;
}
.yaoqingfl-rule-p {
    color: #e73c3a;
    text-align: center;
    font-size: 26px;
    padding: 10px 0 0 0px;
    margin-bottom: 0px;
}
.yaoqingfl-rule-p2 {
	margin-top: 0px;
    color: #333;
    font-size: 22px;
    text-align: center;
}
.yaoqingfl-rule-ul {
    width: 912px;
    margin: 70px auto 0px;
    background: #fef3f3;
}
.yaoqingfl-rule-ul li {
    border-bottom: 1px solid #fff;
    overflow: hidden;
}
.yaoqingfl-rule-ul li:first-child p {
	margin-top: 0px;
	margin-bottom: 0px;
    font-size: 26px;
    height: 82px;
    line-height: 82px;
}
.yaoqingfl-rule-ul li p {
	margin-top: 0px;
	margin-bottom: 0px;
    float: left;
    width: 50%;
    text-align: center;
    color: #333;
    font-size: 24px;
    height: 60px;
    line-height: 60px;
}
.invite_center{
	display: inline-block;
	margin-left: 30px;
	float: left;
    width: 530px;
    text-align: left;
}
.invite_center1{
	margin-left:120px;
	float: left;
	display: inline-block;
}
em{
	font-style: normal;
	color: red;
}
.invite_center1 a {
	margin-top: 30px;
	margin-left: 50px;
    width: 165px;
    
    line-height: 47x;
    font-size: 24px;
    color: #fff;
    text-align: center;
    background: #e73c3a;
    border-radius: 10px;
    cursor: pointer;
    display: inline-block;
    float: left
}
.yqfl-list {
    overflow: hidden;
    text-align: center;
    width: 790px;
    margin: 0 auto;
}
.yqfl-list li:first-child {
    margin-left: 0;
}
.yqfl-list li {
    float: left;
    margin-left: 150px;
}
.yqfl-list li>div {
    width: 156px;
    height: 146px;
    border-radius: 50%;
    background: #f8c4c4;
    padding-top: 10px;
    margin-bottom: 20px;
}
.yqfl-list li>div div {
    width: 136px;
    height: 136px;
    border-radius: 50%;
    background: #E73C3A;
    color: #fff;
    margin: 0 auto;
    text-align: center;
    font-weight: bold;
}
.yqfl-list li>div p {
    padding-top: 24px;
}
.yqfl-list-span {
    font-size: 43px;
}
.yqfl-list-span2 {
    font-size: 22px;
}
.yqfl-list-span3 {
    font-size: 25px;
}
.yqfl-list li:nth-child(2)>div {
    width: 176px;
    height: 166px;
    margin-bottom: 0px;
}
.yqfl-list li:nth-child(2)>div>div {
    width: 156px;
    height: 156px;
}
.yqfl-list li>span {
    color: #e73c3a;
    font-size: 24px;
    line-height: 45px;
}
.yqfl-list-ul {
    width: 950px;
    margin: 0 auto;
    background: #fff3f3;
}
.yqfl-list-ul li {
    height: 60px;
    line-height: 60px;
    border-bottom: 1px solid #d5d5d5;
    color: #fb2826;
    font-size: 22px;
}
.yqfl-list-ul li:first-child {
    height: 76px;
    color: #fff;
    font-size: 28px;
    line-height: 76px;
    background: #e96564;
    border-radius: 20px;
    overflow: hidden;
    border: none;
}
.yqfl-list-ul li p {
    float: left;
    width: 20%;
    text-align: center;
}
.yqfl-list-ul li p {
    float: left;
    width: 20%;
    text-align: center;
}
.yqfl-list-ul li p:nth-child(2) {
    width: 60%;
}
.yqfl-list-ul li p {
	margin-bottom：1px;
    float: left;
    width: 20%;
    text-align: center;
}
.yqfl-cjwt {
    width: 1094px;
    font-size: 24px;
    color: #666666;
    margin: 40px auto 200px;
}
p{
	margin-bottom:1px;
}
.yqfl-cjwt p {
    margin-bottom: 30px;
}
.yqfl-cjwt-p {
    font-size: 32px;
    color: #333;
    margin-bottom: 15px;
}
.yaoqingfl-rule-p2 {
    color: #333;
    font-size: 22px;
    text-align: center;
}
.yaoqingfl-banner {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 760px;
}
    </style>
	</head>
		<meta charset="UTF-8">
		<title></title>
		<link rel="stylesheet" href="css/invite.css" />
			
	<div class="yaoqingfl-banner">
		<img src="/static/imgs/icon/yaoqingfanli.gif" alt="">
	</div>
	<div class="yqfl-font">
		<p class="yqfl-head">邀请方式</p>
		<div class="yqfl-head2">
			<img src="/static/imgs/icon/yqfl_1.png" alt="">
			<span>邀请方式</span>
			<img src="/static/imgs/icon/yqfl_2.png" alt="">
		</div>
		<p class="yqfl-font-p">复制您的专属链接，发给您的qq、微信好友，好友通过您的邀请注册商家并<br>成功开通VIP商家即可获得邀请奖励</p>
		<div class="yqfl-font-copy">
			 <input type="text" value="<?= $url ?>" id="url1" />
                <input class="copyTxt" type="button" onclick="copyUrl('<?= $enable_invite ?>')" value="点击复制" id="button" />
		</div>
		<img class="yqfl_fangshi-bottom-margin" src="/static/imgs/icon/yaoqingfl_fs_bottom.jpg" alt="">
	</div>
	<div class="yqfl-font">
		<p class="yqfl-head">活动内容</p>
		<div class="yqfl-rule">
		<div>
			<img src="/static/imgs/icon/yqfl_rule.png" alt="">
			<p>好友注册</p>
		</div>
		<div>
			<img src="/static/imgs/icon/yqfl_rule3.png" alt="">
		</div>
		
		<div>
			<img src="/static/imgs/icon/yqfl_rule2.png" style="margin-bottom: 12px;" alt="">
			<p>报名活动</p>
		</div>
	</div>
	<p class="yaoqingfl-rule-p">两级关系大奖励</p>
	<p class="yaoqingfl-rule-p2">您邀请的好友每报名1单活动，奖励您0.5元<br>您朋友邀请的朋友每报名1单，奖励您0.5元</p>
	<p class="yaoqingfl-rule-p yaoqingfl-rule-p-margin" style="margin-top: 50px;">好友充值会员，立送推荐人金币</p>
	<p class="yaoqingfl-rule-p2">（本活动长期有效）</p>
	<ul class="yaoqingfl-rule-ul">
		<li>
			<p>好友购买会员</p>
			<p>推荐人获得金币</p>
		</li>
		<li>
			<p>3个月</p>
			<p>900</p>
		</li>
		<li>
			<p>6个月</p>
			<p>1080</p>
		</li>
		<li>
			<p>12个月</p>
			<p>1260</p>
		</li>
		<li>
			<p>24个月</p>
			<p>1440</p>
		</li>
		<li>
			<p>48个月</p>
			<p>1800</p>
		</li>
	</ul>
	<img class="yqfl_fangshi-bottom-margin" src="/static/imgs/icon/yaoqingfl_fs_bottom.jpg" alt="">
	</div>
	<div class="yqfl-font">
		<p class="yqfl-head">活动返利</p>
		<div class="invite_center">
            <p>
                <span>例：您每月邀请10个商家朋友，每个商家月均发布500单活动；</span>
                <span>您朋友也邀请了10个商家，月均发布500单活动</span>
                <span>则您的收益为：</span><br>
                <span>第1个月：10*500*0.5+100*500*0.5=<em>27500</em>元</span><br>
                <span>第2个月：(10*500*0.5+100*500*0.5*2)*2=<em>55000</em>元</span><br>
                <span>第3个月：(10*500*0.5+100*500*0.5)*3=<em>82500</em>元</span><br>
                <span>第4个月：(10*500*0.5+100*500*0.5)*4=<em>110000</em>元</span><br>
                <span>如您邀请的商家为大商家，比如日发100单，则只此1家，</span>
                <span>贡献每月您的收益将达<em>1500</em>元；如果邀请的都是大商家呢？</span>
            </p>
        </div>
            <div class="invite_center1">
            	<p>您邀请的好友每报名1单活动，奖励您0.5元<br>您朋友邀请的朋友每报名1单，奖励您0.5元</p>
            	<a href="/invite/invite_reward" class="btn btn-danger" target="_blank">查看详情</a>
            </div>
        <img class="yqfl_fangshi-bottom-margin" src="/static/imgs/icon/yaoqingfl_fs_bottom.jpg" alt="">
	</div>
	<div class="yqfl-font yqfl-font2">
		<p class="yqfl-head">邀请排行榜</p>
		<ul class="yqfl-list">
		<li>
			<div>
			<div>
				<p>
					<span class="yqfl-list-span"><?= $result['rank_list'][2]['total_reward_points'] ?></span>
					<span class="yqfl-list-span2"></span><br>
					<span class="yqfl-list-span3">第二名</span>
				</p>
			</div>
			</div>
			<span><?= $result['rank_list'][1]['nickname'] ?></span>
		</li>
		<li>
			<div>
			<div>
				<p>
					<span class="yqfl-list-span"><?= $result['rank_list'][2]['total_reward_points'] ?></span>
					<span class="yqfl-list-span2"></span><br>
					<span class="yqfl-list-span3">第一名</span>
				</p>
			</div>
			</div>
			<span><?= $result['rank_list'][0]['nickname'] ?></span>
		</li>
		<li>
			<div>
			<div>
				<p>
					<span class="yqfl-list-span"><?= $result['rank_list'][2]['total_reward_points'] ?></span>
					<span class="yqfl-list-span2"></span><br>
					<span class="yqfl-list-span3">第三名</span>
				</p>
			</div>
			</div>
			<span><?= $result['rank_list'][2]['nickname'] ?></span>
		</li>
	</ul>
	<ul class="yqfl-list-ul">
		<li>
			<p>排名</p>
			<p>用户名</p>
			<p>获得金币</p>
		</li>
		<?php foreach ($result['rank_list'] as $key => $item): ?>
		<li>
			<p style='margin-bottom：1px;'><?= $key + 1 ?></p>
			<p style='margin-bottom：1px;'><?= $item['nickname'] ?></p>
			<p style='margin-bottom：1px;'><?= $item['total_reward_points'] ?></p>
		</li>
		<?php endforeach; ?>
			</ul>
        <img class="yqfl_fangshi-bottom-margin" src="/static/imgs/icon/yaoqingfl_fs_bottom.jpg" alt="">
	</div>
	<div class="yqfl-cjwt">
		<p class="yqfl-cjwt-p">常见问题</p>
		<p>问：我邀请成功好友购买会员后，奖励如何发放给我？<br>答：根据活动规则， 邀请商家获得的奖励将以金币的方式发放到您的商家帐户中； 如您的奖励超2小时未到账，请联系在线客服协助处理。</p>
		<p>问：我邀请的好友，是不是每次续费会员。我都可以获得对应的奖励呢？<br>答：是的。</p>
		<p>问：我获得的金币奖励是否支持提现？<br>答：支持提现。</p>
		<p>问：任务奖励大概什么时间给我？<br>答：您邀请的人以及邀请的人再去邀请，发布的任务均需要在完成之后，系统才会立即会奖励到您账户上；如您的奖励超2小时未到账，请联系在线客服协助处理。</p>
		<p>问：我忘记使用邀请链接了，但他确实是我邀请加入你们的，怎么办？<br>答：如有特殊原因， 请联系在线客服核实处理，核实属实也是同样可以享受邀请奖励的。</p>
	</div>
		
		<script type="text/javascript">
			function copyUrl(_type) {
		        if (_type == '0'){
		            toastr.warning('很抱歉您还未发布过活动，暂时无法进行邀请哦~');
		            return 0 ;
		        } else {
		            var Url = document.getElementById("url1");
		            Url.select();                                   // 选择对象
		            document.execCommand("Copy");                   // 执行浏览器复制命令
		            var btn = document.getElementById("button");
		            btn.setAttribute("value", "复制成功");
		        }
		    }
		</script>
	</body>
</html>
