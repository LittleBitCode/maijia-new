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
<link rel="stylesheet" href="/static/css/center.css" />
<link rel="stylesheet" href="/static/css/deposit.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<title>充值押金-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'recode']); ?>
    <div class="center_box">
        <div class="center_r">
        	<div class="recharge_box">
            	<h1>押金充值<span>充值到账可能会有延时，若<em>30分钟</em>内未到账请联系客服</span></h1>
                <form id="deposit_form" action="/recharge/deposit2_submit" method="post" target="_blank">
                <input type="hidden" id="recharge_img_base64" name="recharge_img_base64" />
                <div class="recharge_number">
                    <div class="recharge_number_con">
                        <p>账户余额：<?php echo $user_info->user_deposit; ?>元</p>
                        <p>开户银行：民生银行 - 杭州分行</p>
                        <p>银行卡号：*******************</p>
                    	<p>开户姓名：******</p>
                        <div><label>充值金额：</label><input type="text" name="recharge_number" class="form-control" onKeyUp="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" />元<span>最低500元起充</span></div>
                        <div style="margin-top: 5px;"><label>备注信息：</label><input type="text" name="recharge_note" class="form-control" style="width:256px;" /><span>请填写银行账户【真实姓名】</span></div>
                        <div style="margin-top: 5px;"><label>转账截图：</label><input type="file" style="border:none;width: 200px;" name="recharge_img" onChange="javascript:setImagePreview(this);" /><span>截图格式:jpg,jpeg,png</span></div>
                        <input type="button" class="recharge_butt" value="确&nbsp;&nbsp;认" />
                    </div>
                </div>
                </form>  
                <!-- <a target="_blank" href="http://q.url.cn/CD10kY?_type=wpa&qidian=true" class="lack_deposit" style="color:#f00">押金需要充值，请联系客服QQ：800828297进行充值操作</a> -->
            </div>
        </div>
        
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script src="/static/js/exif.js"></script>
<script src="/static/js/lrz.js"></script>
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
			var recharge_number = $('input[name="recharge_number"]').val();
			if( recharge_number == '' ){
				toastr.warning("充值金额不能为空");
                return false;
			}

            if( parseInt(recharge_number)<parseInt(500)){
				toastr.warning("充值金额必须为大于500元的整数");
                return false;
			}

            if ($('input[name="recharge_note"]').val() == '') {
                toastr.warning("请填写备注信息");
                return false;
            }

            if ($('#recharge_img_base64').val() == '') {
                toastr.warning("请上传转账截图");
                return false;
            }

            $('#deposit_form')[0].submit();
		});
	});

    function setImagePreview(obj) {
        lrz(obj.files[0],  function(res){
            $("#recharge_img_base64").val(res.base64);
        });
    }
</script>
</body>
</html>