<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css">
<link rel="stylesheet" href="/static/css/swiper-3.3.1.min.css">
<link rel="stylesheet" href="/static/css/pay.css">
<title>支付失败-<?php echo PROJECT_NAME; ?></title>
</head>
<body>

	<div class="content2" >
		<div class="pay_wrap">
        	<div class="complete">
	            <h3>对不起，支付失败</h3>
	            <p><span id="time">5</span>s后自动返回个人中心<a href="/center/index">立即查看 ></a></p>
	        </div>
	    </div>
   
	</div>
	<!-- 示例截图 -->
	<?php //$this->load->view('/common/footer');?>
	
	
	<script type="text/javascript">
		// 支付倒计时
		var start = 5;
	    function count() {
	        $("#time").text(start);
	        start --;
	        if(start < 0){
	        	// 返回个人中心页面
				location.href = '/center/index';		
			}else{
				setTimeout(count, 1000);
			}  
	    }
	    count();
	</script>
</body>
</html>