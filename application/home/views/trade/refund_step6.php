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
<link rel="stylesheet" href="/static/css/trade.css" />
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
</head>
<body onLoad="count();">
	<?php $this->load->view("/common/top", ['site' => 'trade']); ?>
    <div class="trade_box" style="min-height: 68vh">
    	<!-- 发活动顶部活动步骤进度start -->
    	<div class="trade_top">
        	<div class="Process">
                <ul class="clearfix">
                  <li style="width:20%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
                  <li style="width:20%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
                  <li style="width:20%" class="cur"><em class="Processyes">3</em><span>选择活动数量</span></li>          
                  <li style="width:20%" class="cur"><em class="Processyes">4</em><span>选增值服务</span></li>
                  <li style="width:20%" class="cur"><em class="Processyes">5</em><span>支付</span></li>
                  <li style="width:20%" class="Processlast"><em class="Processyes">6</em><span>发布成功</span></li>
                </ul>
        	</div>
        </div>
        <!-- 发活动顶部活动步骤进度start -->
        
        <div class="step5_box">
            <div class="scontent">
                <div class="succeed">
                    <img src="/static/imgs/trade/pay_success.png" style="width: 64px; height: 64px;" />
                    <h3>支付成功，活动已发布等待客服审核</h3>
                    <div class="succeed_p"><span id="time">5</span>s后自动跳转到个人中心<a href="/center">立即前往&nbsp;&gt;</a></div>
                </div>
            </div>
            
        </div>
        
    </div>
    
    <?php $this->load->view("/common/footer"); ?>
    
    
<script type="text/javascript">
//5s后自动跳转
var start = 5;
var step = -1;
function count() {
    document.getElementById("time").innerHTML = start;
    start += step;
    if (start < 0)
        location.href = "/center";
    setTimeout("count()", 1000);
}
</script>
</body>
</html>