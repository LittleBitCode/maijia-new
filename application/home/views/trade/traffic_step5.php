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
                <li style="width:25%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
                <li style="width:25%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
                <li style="width:25%" class="cur"><em class="Processyes">4</em><span>选增值服务</span></li>
                <li style="width:25%"><em class="Processyes">5</em><span>支付</span></li>
                <li style="width:25%" class="Processlast"><em>6</em><span>发布成功</span></li>
            </ul>
        </div>
    </div>
    <div style="clear: both;"></div>
    <form action="/trade/char_eval_step5_submit/<?php echo $trade_info->id; ?>" method="post" id="pay_business_task">
    <div class="trade_box">
        <!-- 发活动顶部活动步骤进度start -->
        <div class="step5_box">
            <h1>5.支付<span>&nbsp;&nbsp;&nbsp;充值到账可能会有延时，若<span class="red">30分钟</span>内未到账请联系客服</span></h1>
            <div class="row text-center white_box" style="margin: 0">
                <div class="col-xs-4">
                    <p>本次活动发布</p>
                    <p><?php echo $trade_info->total_num; ?>单</p>
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
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="next_box">
        <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
        <div class="text-center" style="display: inline-block"><a href="javascript:;" data-plat_id="<?= $trade_info->plat_id ?>" data-shop_ww="<?= $shop_ww ?>" class="pay_but">确认付款并报名活动</a></div>
    </div>
</form>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    //选择支付以及支付金额计算方法
    pay("<?php echo $trade_info->trade_deposit; ?>","<?php echo $user_info->user_deposit; ?>","<?php echo $trade_info->trade_point; ?>","<?php echo $user_info->user_point; ?>");
    function pay(trade_deposit,user_deposit,trade_point,user_point){
        var t_deposit = parseFloat(trade_deposit)*10000; // 需要支付押金
        var u_deposit = parseFloat(user_deposit)*10000; // 可用押金
        var t_point = parseFloat(trade_point)*10000; // 需要支付金币
        var u_point = parseFloat(user_point)*10000; // 可用金币

        // 判断金币是否够支付
        if(u_point - t_point<0){
            $('.pay_but').addClass('pay_but_disabled').removeClass('pay_but');
        }
        // 判断押金是否够支付
        if(u_deposit - t_deposit<0){
            $('.pay_but').addClass('pay_but_disabled').removeClass('pay_but');
        }
    }

    $(function(){
        // 支付提交
        $('.next_box').on('click','.pay_but',function(e){
            // $(".pay_pop_box").fadeIn();
            $("#pay_business_task").submit();
            // var plat_id = e.currentTarget.dataset.plat_id
            // var shop_ww = e.currentTarget.dataset.shop_ww
            // if (plat_id == '1' || plat_id == '2') {
            //     $.ajax({
            //         type: "POST",
            //         url: "/trade/ddx_auth",
            //         data: {"shop_ww":shop_ww},
            //         dataType: "json",
            //         success: function(res) {
            //             if (res.code == 0) {  // 未订购 未授权
            //                 $("#pay_business_task").submit();
            //             } else if (res.code == 1) {  // 未订购 未授权
            //                 $('#authModal').modal('show');
            //             } else if (res.code == 2) {  // 订购过期，需要重新订购
            //                 $('#authModal').modal('show');
            //             } else if (res.code == 3) {  // 授权过期，需要重新授权
            //                 $('#authModal1').modal('show');
            //             } else if (res.code == 4) {  // 接口请求失败
            //                 toastr.warning("接口请求失败");
            //             } else {  // 其他错误
            //                 toastr.warning(res.msg);
            //             }
            //         }
            //     });
            // } else{
            //     $("#pay_business_task").submit();
            // }
        });
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