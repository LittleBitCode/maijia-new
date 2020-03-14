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
<title>押金提现-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'recode']); ?>
    <div class="center_box">
        <div class="withdrawal_wrap">
            <h1>押金提现</h1>
            <form id="with_form" action="/withdrawal/with_deposit_submit" method="post">
                <div class="withdrawal_form">
                    <div class="withdrawal_form_item">
                        <label>押金：</label>
                        <div class="deposit_balance">
                            <span class="deposit_balance_num"><?php echo $user_info->user_deposit; ?></span>元
                        </div>
                    </div>
                    <div class="withdrawal_form_item">
                        <label>退款方式：</label>
                        <div class="withdrawal_type">
                            <p class="withdrawal_input_wrap"><input type="radio" checked="true">原路退回 (预计3-7个工作日)<span class="f12 cor999" style="margin-left: 20px;">钱款将原路退回到您之前充值的账户（网银，支付宝、信用卡账户等）</span></p>
                        </div>
                    </div>
                    <div class="withdrawal_form_item">
                        <label>提现金额：</label>
                        <div class="withdrawal_sum">
                            <p class="withdrawal_input_wrap"><input class="form-control withdrawal_input withdrawal_num_input" name="with_deposit" type="text" onKeyUp="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')"><span class="f12 cor999">单次最少提现5元</span></p>
                            <p class="withdrawal_require">提现操作平台将收取<?php echo BUS_WITH_PERCENT*100; ?>%的手续费，手续费最低是2元/笔，不足2元的，按2元收取。</p>
                            <p class="withdrawal_rule">预计2个工作日内（国家法定假日和双休日顺延）平台完成提现操作到账时间以各大银行为准，预计3-5工作日左右</p>
                        </div>
                    </div>
                    <div class="withdrawal_form_item">
                        <label>实际到账金额：</label>
                        <div class="actual_arrival ">
                            <span class="actual_arrival_num">0</span>元
                        </div>
                    </div>
                    <div class="withdrawal_form_item">
                        <label>提现密码：</label>
                        <div class="withdrawal_pwd">
                            <p class="withdrawal_input_wrap"><input class="form-control withdrawal_input withdrawal_pwd_input" name="with_password" type="password"><a href="/center/user_info">找回提现密码</a></p>
                            <p class="error_wrap withdrawal_pwd_error">
                                <!-- <span class="error">提现密码错误</span> -->
                            </p>
                        </div>
                    </div>
                    <div class="withdrawal_btn_wrap">
                        <a class="withdrawal_btn" href="javascript:;">申请提现</a>
                    </div>
                </div>
            </form>
            <div class="withdrawal_prompt">
                <h3>温馨提示</h3>
                <ul style="margin-left: 16px;">
                    <li>1、请确保您输入的提现金额，以及支付宝或银行卡账号信息准确无误。</li>
                    <li>2、如果您填写的提现信息不正确也可能会导致提现失败，由此产生的提现费用将不予返还。</li>
                    <li>3、在国家法定假日和双休日期间，用户可以申请提现，<?php echo PROJECT_NAME; ?>会在7个工作日内进行处理。由此造成的不便，请多多谅解!</li>
                    <li>4、平台禁止洗钱、信用卡套现、虚假交易等行为，一经发现并确认，将终止该账号的使用。</li>
                    <li>5、平台操作提现后，到账金额可能会分为几笔打入您的账户，查询时请注意计算到账总金额。</li>
                    <li>6、商家查账仅处理近10天的提现，如有问题，请商家在规定时间内咨询，超时概不负责。敬请谅解！</li>
                </ul>
            </div>
        </div>
    </div>
    
    <?php $this->load->view("/common/footer"); ?>

<script type="text/javascript">
	$(function(){
        // 实际到账金额
        $(".withdrawal_num_input").change(function(event) {
            var with_amount = parseFloat($(".withdrawal_num_input").val());
            var fee = with_amount * parseFloat("<?php echo BUS_WITH_PERCENT; ?>");
            fee = Math.max(fee, 2);
            if(with_amount - fee > 0){
                $(".actual_arrival_num").text(with_amount - fee);
            }else{
                $(".actual_arrival_num").text('0');
            }
        }); 
        // 提现金额和押金比较
        $(".withdrawal_num_input").blur(function(event) {
            $(".withdrawal_num_error").children('.error').remove();
            var withdrawal_num = $(".withdrawal_num_input").val();
            var deposit_balance_num = $(".deposit_balance_num").text();
            if(parseInt(withdrawal_num) > parseInt(deposit_balance_num)){
                $(".actual_arrival_num").text('0');
                toastr.warning("提现金额大于押金");
                return false;
            }

            if(withdrawal_num<5){
                $(".actual_arrival_num").text('0');
                toastr.warning("提现金额最少5元");
                return false;
            }
        });
		// 提现按钮点击
		$(".withdrawal_btn").click(function(){
			var withdrawal_num = $('.withdrawal_num_input').val();
            var deposit_balance_num = $(".deposit_balance_num").text();
			if( withdrawal_num == '' ){
                $(".actual_arrival_num").text('0');
                toastr.warning("提现金额不能为空");
                return false;
			}else if( parseInt(withdrawal_num)<parseInt(5) ){
                $(".actual_arrival_num").text('0');
                toastr.warning("提现金额要大于5元");
                return false;
			}else if(parseInt(withdrawal_num) > parseInt(deposit_balance_num)){
                $(".actual_arrival_num").text('0');
                toastr.warning("提现金额大于押金");
                return false;
            }
            var withdrawal_pwd = $(".withdrawal_pwd_input").val();
            if( withdrawal_pwd == '' ){
                toastr.warning("提现密码不能为空");
                return false;
            }

            $('#with_form').submit();
		})
	})
</script>
</body>
</html>