<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<!--顶部状态栏开始 -->
<div class="state">
    <div class="wrap">
    	<!-- 状态栏右侧 -->
        <span class="fr"><?php echo PROJECT_NAME; ?>:<strong><?php echo $total_point; ?></strong>点 </span>
        <span class="fr">
            <a href="/recharge/point" style="color:#00a3ef;" target="_blank">充值金币</a>｜ 
            <a href="/group/pay_group" style="color:#00a3ef;" target="_blank">续费会员</a>
        </span>
        <!-- 状态栏左侧 -->
        <span class="fl"><a href="javascript:;"><?php echo $this->session->userdata('mobile');?></a> ｜ <a href="/user/logout">退出</a></span>
    </div>
</div>
<!--顶部状态栏结束-->
<!-- 导航 start -->
<div class="header">
    <div class="wrap">
        <!-- 买手导航左右部分 start -->
        <a class="logo fl" style="width:160px;padding-top: 15px;height: 50px;" href="/"><img src="/static/imgs/hulifu.png" style="float:left;"></a>
        <menu class="business-menu-info fr">
            <div class="shopping_cart task_card">
                <a href="javascript:;" class="renwu"><i>待加入购物车</i>(<font id="add_cart_nums"><?php echo count($wait_add_cart); ?></font>)</a>
                <div class="shopping_cart_wrap" style="display: none;">
                    <p class="shopping_cart_title">我的活动</p>
                    <div class="shopping_cart_head">
                        <p class="task_type">活动类型</p>
                        <p class="task_state">活动状态</p>
                        <p class="task_store">商家</p>
                        <p class="task_price">垫付资金（元）</p>
                        <p class="task_commission">佣金金币</p>
                        <p class="task_duobaobi">奖励夺宝币</p>
                        <p class="task_buyer">接手买号</p>
                    </div>
                    <?php if ($wait_add_cart): ?>
                        <?php foreach ($wait_add_cart as $v): ?>
                        <div class="task_detail">
                            <p class="task_type color_orange">回访订单</p>
                            <p class="task_state">常规</p>
                            <p class="task_store"><?php echo $v->bus_nickname; ?></p>
                            <p class="task_price"><?php echo $v->order_money; ?></p>
                            <p class="task_commission"><?php echo $v->total_reward; ?></p>
                            <p class="task_duobaobi"><?php echo $v->snatch_gold; ?></p>
                            <p class="task_buyer"><?php echo $v->account_name; ?></p>
                        </div>
                        <div class="go_do_task_wrap">
                            <p>您需垫付总金额：<span><?php echo $v->order_money; ?></span>元，押金：<span>1</span>金币；活动完成将获得佣金：<span><?php echo $v->total_reward; ?></span>金币 <a class="cancel_task cancle_order" order_id="<?php echo $v->id; ?>" href="javascript:;">放弃活动</a></p>
                            <a class="go_task_btn" href="/order/step/<?php echo $v->trade_id; ?>">前去做活动&gt;</a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <p class="shopping_cart_none">我的活动为空，请在活动列表接手活动</p>
                    <div class="go_do_task_wrap">
                        <p>您需垫付总金额：<span>0</span>元，押金：<span>0</span>金币；活动完成将获得佣金：<span>0</span>金币 <a class="cancel_task" href="javascript:;">放弃活动</a></p>
                        <a class="go_task_btn" href="/lists/apply">前去做活动&gt;</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="add_order task_card">
                <a href="javascript:;" class="renwu2"><i>待下单</i>(<font id="add_order_nums"><?php echo count($wait_pay_order); ?></font>)</a>
                <div class="shopping_cart_wrap" style="display: none;">
                    <p class="shopping_cart_title">我的活动</p>
                    <div class="shopping_cart_head">
                        <p class="task_type">活动类型</p>
                        <p class="task_state">活动状态</p>
                        <p class="task_store">商家</p>
                        <p class="task_price">垫付资金</p>
                        <p class="task_commission">佣金金币</p>
                        <p class="task_duobaobi">奖励夺宝币</p>
                        <p class="task_buyer">接手买号</p>
                        <p class="task_cancle_time">取消时间</p>
                        <p class="task_edit">操作</p>
                    </div>
                    <?php if ($wait_pay_order): ?>
                        <?php foreach ($wait_pay_order as $v): ?>
                        <div class="task_detail">
                            <p class="task_type color_orange"><?php echo $v->type_name; ?></p>
                            <p class="task_state">常规</p>
                            <p class="task_store"><?php echo $v->bus_nickname; ?></p>
                            <p class="task_price"><?php echo $v->order_money; ?></p>
                            <p class="task_commission"><?php echo $v->total_reward; ?></p>
                            <p class="task_duobaobi"><?php echo $v->snatch_gold; ?></p>
                            <p class="task_buyer"><?php echo $v->account_name; ?></p>
                            <p class="task_cancle_time"><?php echo $v->cancel_time; ?></p>
                            <p class="task_edit"><a class="go_add_order" href="/order/step/<?php echo $v->trade_id; ?>">前去下单</a><a class="cancle_order" order_id="<?php echo $v->id; ?>" href="javascript:;">放弃活动</a></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <p class="shopping_cart_none">我的活动为空，请在活动列表接手活动</p>
                    <?php endif; ?>
                    <div class="go_do_task_wrap">
                    </div>
                </div>
            </div>
            <a class="my_center" href="/center">个人中心</a>
        </menu>
        <!-- 买手导航左右部分 end -->
		<!-- 买手导航中间部分 start -->
        <div class="business-menu fl" style="display:inline-block; width:440px;">
            <a href="/lists/apply" target="_blank">
                活动列表  
            </a>
            <a href="/invite/invite_url" style="position:relative;" target="_blank">
                邀请返利
                <img style="left:48px;" src="/static/imgs/hots.gif" width="38" height="19">
            </a>
            <a href="http://m.qiuyunqi.com/welcome/lucky_roll" style="position:relative;" target="_blank">
                求运气夺宝
                <img style="left:48px; top:-15px;" src="/static/imgs/new.gif" width="31" height="17">
            </a>
            <a href="http://quan.zhongguohuo.com" style="position:relative;" target="_blank">
                值得买
                <img style="left:48px; top:-15px;" src="/static/imgs/new.gif" width="31" height="17">
            </a>
        </div>
        <!-- 买手导航中间部分 end -->
       
       
    </div>
</div>
<div class="popup_bg cancle_task_popup">
    <div class="cancle_task_wrap">
        <img class="cancle_task_close close_cancle_btn" src="/static/imgs/icon/close2.png" alt="">
        <?php if ($cancel_times <= 2): ?>
        <p>放弃第<span><?php echo $cancel_times; ?></span>个活动，系统不扣除活动押金<span>1</span>金币，</p>
        <p>（放弃<span>2</span>个以上活动会被扣除活动押金<span>1</span>金币）</p>
        <?php else: ?>
        <p>活动未完成，现在放弃活动将扣除活动押金<span>1</span>金币</p>
        <?php endif; ?>
        <p class="cancle_task_btn_wrap">
            <a class="ture_cancle" href="javascript:;">确&nbsp;认</a><a class="close_cancle close_cancle_btn" href="javascript:;">关&nbsp;闭</a>
        </p>
    </div>
</div>
<!-- 首页常见问题 -->
    <a class="index_problem_btn" href="javascript:;">常见问题</a>
    <div class="index_problem" style="display: none;">
        <a href="javascript:;" class="index_problem_close">x</a>
        <h3>常见问题<a href="javascript:;" target="_blank">其他问题</a></h3>
        <ul>
            <li><a href=" javascript:;" target="_blank">· 虚拟商品及物流订单确认收货时间 </a></li>
            <li><a href=" javascript:; " target="_blank">· 【快递发货】商家不发货怎么办？</a></li>
            <li><a href=" javascript:;" target="_blank">· 买手该如何确认平台返款活动的金额？</a></li>
            <li><a href=" javascript:;" target="_blank">· 做活动用错买号怎么办？</a></li>
            <li><a href=" javascript:;" target="_blank">· 商家客服不在线/不回复，应该如何处理？</a></li>
        </ul>
    </div>
<!-- 导航 end -->
<script>
    $(".task_card").hover(function() {
        $(this).children('.shopping_cart_wrap').show().siblings('a').addClass('shopping_cart_check');
    }, function() {
        $(this).children('.shopping_cart_wrap').hide().siblings('a').removeClass('shopping_cart_check');
    });
    // 关闭放弃活动弹窗
    $(".close_cancle_btn").click(function(event) {
        $(".cancle_task_popup").hide();
    });


    $('body').on('click','.cancle_order',function(){
        $(".cancle_task_popup").show();

        var order_id = $(this).attr('order_id');

        $('.ture_cancle').attr('href', "/order/cancel/" + order_id);
    });
     // 常见问题展开和关闭
    $(".index_problem_btn").click(function(event) {
        $(this).hide();
        $(".index_problem").show();
    });
    $(".index_problem_close").click(function(event) {
        $(".index_problem").hide();
        $(".index_problem_btn").show();
    });
</script>
