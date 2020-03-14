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
<body>
    <?php $this->load->view("/common/top", ['site' => 'trade']); ?>
    <div class="com_title">商家报名活动</div>
    <!-- 发活动顶部活动步骤进度start -->
    <div class="trade_top">
        <div class="Process">
            <ul class="clearfix">
                <li style="width:20%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
                <li style="width:20%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
                <li style="width:20%" class="cur"><em class="Processyes">3</em><span>选择活动数量</span></li>          
                <li style="width:20%" class="cur"><em class="Processyes">4</em><span>选增值服务</span></li>
                <li style="width:20%"><em class="Processyes">5</em><span>支付</span></li>
                <li style="width:20%" class="Processlast"><em>6</em><span>发布成功</span></li>
            </ul>
        </div>
    </div>
    <div style="clear: both;"></div>
    <form action="/trade/pic_eval_step5_submit/<?php echo $trade_info->id; ?>" method="post" id="pay_business_task">
    <div class="trade_box">
        <div class="step5_box">
            <h1>5.支付<span>&nbsp;&nbsp;&nbsp;充值到账可能会有延时，若<span class="red">30分钟</span>内未到账请联系客服</span></h1>
            <div class="row text-center white_box" style="margin: 0">
                <div class="col-xs-4">
                    <p>本次活动发布</p>
                    <p><?php echo $trade_info->total_num; ?>单</p>
                </div>
                <div class="col-xs-4">
                    <p>需押金</p>
                    <p><span class="red"><?php echo $trade_info->trade_deposit; ?></span>元</p>
                </div>
                <div class="col-xs-4">
                    <p>金币</p>
                    <p><span class="red"><?php echo $trade_info->trade_point; ?></span></p>
                </div>
            </div>
            <div class="white_box">
                <h3>支付方式</h3>
                <div class="pay_type">
                    <div class="pay_list_box">
                        <div class="pay_check_points cur">
                            <!-- <label><input type="checkbox" name="has_point" checked /> -->
                            <h5><img src="/static/imgs/icon/ic1.png">金币支付</h5>
                            (可用金币：<span><?php echo $user_info->user_point; ?></span>) 1金币 = 1元<!-- </label> -->
                            <p>支付：<span class="points_pay_number"><?php echo $trade_info->trade_point; ?></span> 金币
                                <?php if($trade_info->trade_point>$user_info->user_point): ?>
                                    &nbsp;&nbsp;&nbsp;金币不足，还差<i style="color:#f00;"><?php echo ($trade_info->trade_point-$user_info->user_point); ?></i>金币&nbsp;&nbsp;<a href="/recharge/point" target="_blank" style="color:#219bda;">前去充值&gt;</a>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="pay_check cur">
                            <!-- <label><input type="checkbox" name="has_deposit" /> -->
                            <h5><img src="/static/imgs/icon/ic6.png">押金支付</h5>
                            (可用押金：<span><?php echo $user_info->user_deposit; ?></span>元)<!-- </label> -->
                            <p>支付：<span class="points_pay_number cur"><?php echo $trade_info->trade_deposit; ?></span>元
                                <?php if($trade_info->trade_deposit>$user_info->user_deposit): ?>
                                    &nbsp;&nbsp;&nbsp;押金不足，还差<i style="color:#f00;"><?php echo ($trade_info->trade_deposit-$user_info->user_deposit); ?></i>元&nbsp;&nbsp;<a href="/recharge/deposit" target="_blank" style="color:#219bda;">前去充值&nbsp;&gt;</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="next_box">
            <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
            <div class="text-center" style="display: inline-block"><a href="javascript:;" data-plat_id="<?= $trade_info->plat_id ?>" data-shop_ww="<?= $shop_ww ?>" class="pay_but">确认付款并报名活动</a></div>
            <!-- 这个地方PHP计算，金币足够支付展示pay_but，否则展示后面2个 -->
            <!-- 这个地方PHP计算，金币足够支付展示pay_but，否则展示后面2个 -->
            <!--
            <a href="javascript:;" class="pay_but_disabled">付款并报名活动</a>
            <a href="javascript:;" class="pay_but_points">金币还差<span></span>点，请前去充值&gt;</a>
            -->
        </div>





    </div>
</form>

<?php $this->load->view("/common/footer"); ?>

<!-- <div class="pay_pop_box" style="position:fixed; width:100%; height:100%; top:0; left:0; background:rgba(0,0,0,0.6); z-index:99; display:none;">
    <div class="pay_pop" style="width:680px; background:#fff; position:absolute; top:50%; left:50%; border-radius:5px; margin:-130px 0 0 -360px; padding:20px;">
        <h1 style="font-size:20px; padding-bottom:15px; border-bottom:1px solid #ddd; margin-bottom:20px;">请到打开的新窗口进行银行卡支付：
            <a href="javascript:;" style="display:inline-block; width:25px; height:25px; float:right; font-size:28px; color:#999; line-height:25px;" class="pay_pop_close">×</a>
        </h1>
        <h5 style="font-size:15px; line-height:30px; padding-left:15px;">支付小贴士：</h5>
        <p style="font-size:14px; line-height:160%; padding-left:15px;">1. 付款未完成前请不要关闭本页面，您在银行端完成付款后本页面会自动刷新。</p>
        <p style="font-size:14px; line-height:160%; padding-left:15px;">2. 如果银行页面没有打开，请您设置您的浏览器为允许弹出，并确保已经安装了银行的 ActiveX 安全控件， 然后点击下面的"返回支付页面，重新选择"按钮，重新支付。</p>
        <div style="margin:30px 0; overflow:hidden;">
            <a href="/center" style="display:inline-block; width:118px; height:38px; background:#4495e0; margin:0 20px 0 200px; font-size:18px; color:#fff; text-align:center; line-height:38px; border:1px solid #4495e0; border-radius:5px;">已完成付款</a>
            <a href="javascript:;" style="font-size:14px; line-height:40px; color:#17baf5; display:inline-block;" class="pay_pop_close">返回重新选择付款方式</a>
        </div>
    </div>
</div> -->
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    //选择支付以及支付金额计算方法
    // points_pay("<?php echo $trade_info->trade_deposit; ?>","<?php echo $user_info->user_deposit; ?>");
    pay("<?php echo $trade_info->trade_deposit; ?>","<?php echo $user_info->user_deposit; ?>","<?php echo $trade_info->trade_point; ?>","<?php echo $user_info->user_point; ?>");
    function pay(trade_deposit,user_deposit,trade_point,user_point){
        var t_deposit = parseFloat(trade_deposit)*10000; // 需要支付押金
        var u_deposit = parseFloat(user_deposit)*10000; // 可用押金
        var t_point = parseFloat(trade_point)*10000; // 需要支付金币
        var u_point = parseFloat(user_point)*10000; // 可用金币

        // 判断金币是否够支付
        if(u_point - t_point<0){
            //var difference_point = (t_point - u_point)/10000;
            //$('.lack_deposit').show().prepend('<span>金币还差'+difference_point+ '点</span>');
            $('.pay_but').addClass('pay_but_disabled').removeClass('pay_but');
        };
        // 判断押金是否够支付
        if(u_deposit - t_deposit<0){
            //var difference_deposit = (t_deposit - u_deposit)/10000;
            //$('.lack_deposit').show().prepend("<span>押金还差"+difference_deposit+ "元</span>");
            $('.pay_but').addClass('pay_but_disabled').removeClass('pay_but');
        };
    }
    // function points_pay(points_number,recharge){
    // 	var points_number = parseFloat(points_number); //需要支付金额
    // 	var recharge      = parseFloat(recharge); //可用押金

    // 	if( recharge>0 ){
    // 		$(".pay_check").addClass("cur");
    // 		$(".pay_check label input").attr("checked",true);

    // 		//1.押金足够支付的情况
    // 		if( recharge>points_number || recharge==points_number ){
    // 			$(".pay_type_check").hide();
    // 			$(".pay_type_check label input").attr("checked",false);
    // 			$(".pay_type_check label input").attr("disabled",true);
    // 			$(".pay_type_check").eq(0).show().removeClass("cur");
    // 			$(".pay_type_check").eq(0).children("a").hide();
    // 			$(".pay_type_check").eq(0).children("p").hide();
    // 			$(".pay_check").find(".points_pay_number").html(points_number);
    // 		}else{
    // 			$(".pay_type_check").hide();
    // 			$(".pay_type_check label input").attr("disabled",false);
    // 			$(".pay_type_check").eq(0).children("label").children("input").attr("checked",true);
    // 			$(".pay_type_check").eq(0).show().addClass("cur");
    // 			$(".pay_type_check").eq(0).children("a").show();
    // 			$(".pay_type_check").eq(0).children("p").show();
    // 			$(".pay_check").find(".points_pay_number").html(recharge);
    // 			$(".pay_type_check").find(".points_pay_number").html( points_number-recharge );
    // 		}
    // 	}else{
    // 		$(".pay_check label input").attr("checked",false);
    // 		$(".pay_check label input").attr("disabled",true);

    // 		$(".pay_type_check").hide();
    // 		$(".pay_type_check label input").attr("disabled",false);
    // 		$(".pay_type_check").eq(0).children("label").children("input").attr("checked",true);
    // 		$(".pay_type_check").eq(0).show().addClass("cur");
    // 		$(".pay_type_check").eq(0).children("a").show();
    // 		$(".pay_type_check").find(".points_pay_number").html( points_number-recharge );
    // 	}
    // }

    //是否选择押金支付
    // $(".pay_check label").click(function(){
    // 	pay_check();
    // })

    // function pay_check(){
    // 	var points_number = parseFloat(<?php echo $trade_info->trade_deposit; ?>); //需要支付金额
    // 	var recharge      = parseFloat(<?php echo $user_info->user_deposit; ?>); //可用押金
    // 	if( $(".pay_check label input").is(":checked") ){
    // 		$(".pay_check").addClass("cur");
    // 		//1.押金足够支付的情况
    // 		if( recharge>points_number || recharge==points_number ){
    // 			$(".pay_type_check").hide();
    // 			$(".pay_type_check label input").attr("checked",false);
    // 			$(".pay_type_check label input").attr("disabled",true);
    // 			$(".pay_type_check").eq(0).show().removeClass("cur");
    // 			$(".pay_type_check").eq(0).children("a").hide();
    // 			$(".pay_type_check").eq(0).children("p").hide();
    // 			$(".pay_check").find(".points_pay_number").html(points_number);
    // 		}else{
    // 			$(".pay_type_check").hide();
    // 			$(".pay_type_check label input").attr("disabled",false);
    // 			$(".pay_type_check").eq(0).children("label").children("input").attr("checked",true);
    // 			$(".pay_type_check").eq(0).show().addClass("cur");
    // 			$(".pay_type_check").eq(0).children("a").show();
    // 			$(".pay_type_check").eq(0).children("p").show();
    // 			$(".pay_check").find(".points_pay_number").html(recharge);
    // 			$(".pay_type_check").find(".points_pay_number").html( points_number-recharge );
    // 		}
    // 	}else{
    // 		$(".pay_check").removeClass("cur");
    // 		//alert(points_number);
    // 		$(".pay_type_check").hide();
    // 		$(".pay_type_check label input").attr("disabled",false);
    // 		$(".pay_type_check").eq(0).children("label").children("input").attr("checked",true);
    // 		$(".pay_type_check").eq(0).show().addClass("cur");
    // 		$(".pay_type_check").eq(0).children("a").show();
    // 		$(".pay_type_check").eq(0).children("p").show();
    // 		$(".pay_type_check").find(".points_pay_number").html( points_number );
    // 	}
    // }

    $(function(){
        //点击展开支付方式列表
        // $(".pay_list_type .pay_type_check a").click(function(){
        // 	$(this).hide();
        // 	$(".pay_type_check").show();
        // })

        //选择支付方式
        // $(".pay_type_check label").click(function(){
        // 	if( $(this).find("input").is(":checked") ){
        // 		$(".pay_type_check").removeClass("cur");
        // 		$(this).parent(".pay_type_check").addClass("cur");
        // 	}
        // })
        $('.next_box').on('click','.pay_but',function(e){
            // $(".pay_pop_box").fadeIn();
            var plat_id = e.currentTarget.dataset.plat_id
            var shop_ww = e.currentTarget.dataset.shop_ww
            if (plat_id == '1' || plat_id == '2') {
                $.ajax({
                    type: "POST",
                    url: "/trade/ddx_auth",
                    data: {"shop_ww":shop_ww},
                    dataType: "json",
                    success: function(res) {
                        if (res.code == 0) {  // 未订购 未授权
                            $("#pay_business_task").submit();
                        } else if (res.code == 1) {  // 未订购 未授权
                            $('#authModal').modal('show');
                        } else if (res.code == 2) {  // 订购过期，需要重新订购
                            $('#authModal').modal('show');
                        } else if (res.code == 3) {  // 授权过期，需要重新授权
                            $('#authModal1').modal('show');
                        } else if (res.code == 4) {  // 接口请求失败
                            toastr.warning("接口请求失败");
                        } else {  // 其他错误
                            toastr.warning(res.msg);
                        }
                    }
                });
            } else{
                $("#pay_business_task").submit();
            }
        });

        //支付成功的弹窗关闭
        // $(".pay_pop_close").click(function(){
        // 	$(".pay_pop_box").fadeOut();
        // })

        //有多少金币
        // var points_num = parseFloat($(".pay_check_points label span").html());
        //需要支付的金币
        // var points_pay = parseFloat($(".points_pay_number").html());
        /*
        if( points_pay>points_num ){
            $(".pay_but").hide();
            $(".pay_but_disabled").show();
            $(".pay_but_points").show().children("span").html(parseFloat(points_pay-points_num).toFixed(2));
        }else{
            $(".pay_but").show();
            $(".pay_but_disabled").hide();
            $(".pay_but_points").hide();

        }
        */

    })


</script>
</body>
<!-- 模态框（Modal） 未授权 未购买 -->
<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:30%;">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header"style="border:none !important;">
                <h4 class="modal-title" id="myModalLabel" style="text-align:center;font-size:30px;margin-top:20px;">
                    <img src="/static/imgs/bindimg.png">
                    &nbsp;&nbsp;您的店铺需要进行授权后才可使用
                </h4>
            </div>
            <div class="modal-body" style="text-align:center;font-size:15px;">
                <span>请使用当前绑定的店铺旺旺前去购买服务并授权，即可使用</span><br>
                <span>该服务仅用做网站核对活动信息使用</span><br>
            </div>
            <div class="modal-footer" style="border:none !important;text-align:center!important;">
                <a href="<?= HELP_CENTER_URL.'/archives/315' ?>" style="display:block"><button style="background:#05a2ff;width:200px;height:40px;color:white;border-radius:5px;">前去购买授权</button></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!-- 未授权 -->
<div class="modal fade" id="authModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:30%;">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header"style="border:none !important;">
                <h4 class="modal-title" id="myModalLabel" style="text-align:center;font-size:30px;margin-top:20px;">
                    <img src="/static/imgs/bindimg.png">
                    &nbsp;&nbsp;您的店铺需要进行授权后才可使用
                </h4>
            </div>
            <div class="modal-body" style="text-align:center;font-size:15px;">
                <span>请使用当前绑定的店铺旺旺前去授权即可使用</span><br>
                <span>该服务仅用做网站核对活动信息使用</span><br>
            </div>
            <div class="modal-footer" style="border:none !important;text-align:center!important;">
                <a href="<?= HELP_CENTER_URL.'/archives/330' ?>" style="display:block"><button style="background:#05a2ff;width:200px;height:40px;color:white;border-radius:5px;">前去授权</button></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
</html>