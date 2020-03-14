<!DOCTYPE html>
<html lang="zh-CN" style="background: #F2F2F2">
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
<title>充值押金-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
        
        <div class="center_r" style="float: none;margin: 0 auto;">
        	<div class="recharge_box" style="height: 820px;">
            	<h1>押金充值<span>充值到账可能会有延时，若<em>30分钟</em>内未到账请联系客服</span></h1>
                <h3>选择支付方式<span>温馨提示：请确保支付的银行账户为本人账户，平台押金提现都是原路退回</span></h3>
                
                <form id="deposit_form" action="/recharge/deposit_submit" method="post" target="_blank">
                <input type="hidden" name="pay_id" />
                <div class="bank_list">
                	<span>更多银行<i></i></span>
                	<ul>
                    	<li class="cur" pay_id="7"><i></i>快钱</li>
                        <li pay_id="1"><i></i>工商银行</li>
                        <li pay_id="19"><i></i>微信</li>
                        <li pay_id="18"><i></i>支付宝</li>
                        <li pay_id="2"><i></i>建设银行</li>
                        <li pay_id="3"><i></i>中国银行</li>
                        <li pay_id="4"><i></i>农业银行</li>
                        <li pay_id="5"><i></i>民生银行</li>
                        <li pay_id="6"><i></i>招商银行</li>
                    </ul>
                </div>
                <div class="recharge_number">
                	<h5>填写充值金额</h5>
                    <div class="recharge_number_con">
                    	<p>账户余额：<?php echo $user_info->user_deposit; ?>元</p>
                        <div><label><i>*</i>充值金额：</label><input type="text" name="recharge_number" onKeyUp="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" />元<span>最低500元起充</span></div>
                        <p class="recharge_error"></p>
                        <input type="button" class="recharge_butt" value="确&nbsp;&nbsp;认" />
                    </div>
                </div>
                </form>
                <!-- <a target="_blank" href="http://q.url.cn/CD10kY?_type=wpa&qidian=true" class="lack_deposit" style="color:#f00">押金需要充值，请联系客服QQ：800828297进行充值操作</a> -->
            </div>
        </div>
        
    <!-- </div> -->
    
    <!-- <?php //$this->load->view("/common/footer"); ?> -->
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
	$(function(){
		// 选择支付方式效果
		$('.bank_list li').click(function(){
			$('.bank_list li').removeClass("cur");
			$(this).addClass("cur");
		});
		var $blankpay = $('.bank_list li:gt(3)');
		$('.bank_list span').click(function(){
			if($('.bank_list li:eq(4)').is(':hidden')){
				$blankpay.show();
			}else{
				$blankpay.hide();
			}
		});
		
		// 充值提交按钮验证
		$(".recharge_butt").click(function(){
			$(".recharge_error").empty();
			var recharge_number = $('input[name="recharge_number"]').val();
			if( recharge_number == '' ){
				$(".recharge_error").append('<span class="error">充值金额不能为空</span>');
			}else if( parseInt(recharge_number)<parseInt(500) ){
				$(".recharge_error").append('<span class="error">充值金额大于500元的整数</span>');
			}else{

                var pay_id = $('.bank_list li.cur').attr('pay_id');

                $('input[name="pay_id"]').val(pay_id);

                $('#deposit_form')[0].submit();
			}
		});
	})
</script>
</body>
</html>