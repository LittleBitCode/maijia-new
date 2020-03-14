<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/css/swiper-3.3.1.min.css">
<link rel="stylesheet" href="/static/css/pay.css">
<title>微信支付-<?php echo PROJECT_NAME; ?></title>
</head>
<body>

	<div class="content2" >
		<div class="pay_wrap">
			<p class="pay_title">需微信支付&nbsp;:&nbsp;<span class="color_red"><?php echo sprintf("%2d",$amount); ?></span>元</p>
			<p class="all_pay_type"><a href="javascript:;" onclick="self.close()">&lt;选择其他支付方式</a></p>
			<p class="count_down">距离二维码过期还剩<span class="time color_red">600</span>秒，过期后请刷新页面，重新获取二维码</p>
			<p class="be_overdue" style="display: none;"><span class="color_red">二维码已过期，<a class="color_blue" onclick="window.location.reload();" href="javascript:;">刷新</a>页面重新获取二维码</span></p>
			<div class="qr_code_wrap">
				<div class="qr_code">
					<img src="<?php echo $img; ?>" width="300" height="300" alt="">
					<img src="/static/imgs/pay/wxpay_prompt.jpg" alt="">
				</div>
				<div class="scan_code_sample">
					<img src="/static/imgs/pay/wxpay_sample.jpg" alt="">
				</div>
			</div>
			<div class="pay_order">
				<p>请您及时付款，以便订单尽快处理！订单号&nbsp;:&nbsp;<?php echo $pay_sn ?></p>
				<p>应付金额：<span class="f20 color_red"><?php echo sprintf("%2d",$amount); ?></span>元</p>
			</div>
		</div>
	</div>
	<!-- 示例截图 -->
	<?php //$this->load->view('/common/footer');?>
	
	<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
	<script type="text/javascript">
		// 支付倒计时
		var start = 600;
	    function count() {
//	    	if(start == 0)return;
	    	start --;
	        $(".time").text(start);
	        
	        if (start < 0) {
	        	clearTimeout(timer);
	    		$(".be_overdue").show();
	    		$(".count_down").hide();
	    	}
			var url = '/recharge/check_pay';
	    	var pay_sn = "<?php echo $pay_sn; ?>";
	    	var data = {pay_sn:pay_sn};
	    	$.post(url, data,function(res) {
	    		if (res.status == '1') {
	    			window.location.href = '/pay/success';
	    		}
	    	},'json')
	        var timer = setTimeout(count, 1000);
	    }
	    count();
	</script>
</body>
</html>