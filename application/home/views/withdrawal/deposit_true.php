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
<link rel="stylesheet" href="/static/css/center.css" />
<link rel="stylesheet" href="/static/css/deposit.css" />
<title>押金提现-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'recode']); ?>
    <div class="center_box">
    	<?php $this->load->view("/common/left"); ?>
        
        <div class="center_r">
        	<div class="withdrawal_wrap">
            	<h1>押金提现</h1>
                
                
                <div class="withdrawal_form">
                    <p class="deposit_true">您已成功申请<span>500</span>元押金提现</p>
                    <p class="deposit_true">预计两个工作日内（国家法定假日和双休日延顺）平台完成提现操作，具体到账时间以各种银行卡实际到帐时间为准</p>
                    <p class="deposit_true">注意：提现金额可能会分为几笔打入您的账户，查询时请注意计算到账总金额</p>
                    <p class="deposit_true"><a href="javascript:;">查看提现记录</a></p>
                </div>
              
            </div>
        </div>
        
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script type="text/javascript">
	$(function(){
       
	})
</script>
</body>
</html>