<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/css/center.css" />
<link rel="stylesheet" href="/static/css/order_task.css">
<title>活动详情-<?php echo PROJECT_NAME; ?></title>
<style>.dl-horizontal dt {width: 118px !important; } .dl-horizontal dd {margin-left: 128px !important; }</style>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
	<div class="task_message">
		<div class="task_message_left">
			<p class="task_detail_title">活动信息</p>
            <dl class="dl-horizontal" style="margin-bottom: 0;">
                <dt>活动类型：</dt><dd><?= $trade_type_list[$trade_order->trade_type] ?></dd>
                <dt>下单终端：</dt><dd><?= ($trade_order->channel == '1') ? '电脑' : '手机|Pad'; ?></dd>
                <dt>买家账号：</dt><dd><?= $buy_nickname; ?></dd>
                <dt>买号：</dt><dd><?php echo $bind_account->account_name; ?></dd>
                <dt>垫付本金：</dt><dd><span class="color_red"><?php echo $trade_order->order_money*1; ?></span> 元</dd>
            </dl>
            <dl class="dl-horizontal" style="margin-bottom: 0;">
                <dt>返款类型：</dt><dd><?php if ($trade_order->plat_refund): ?>平台退款<?php else: ?>商家退款<?php endif; ?></dd>
                <dt>返款金额：</dt><dd><?php echo $trade_order->order_money*1; ?>元</dd>
            </dl>
            <dl class="dl-horizontal" style="margin-bottom: 0;">
                <dt>活动编号：</dt><dd><?php echo $trade_order->order_sn; ?></dd>
                <dt>活动接手时间：</dt><dd><?php echo date('Y-m-d H:i:s',$trade_order->first_start_time); ?></dd>
                <dt>店铺：</dt><dd><i class="plat_small plat_<?php if (isset($bind_shop->plat_name)) echo $bind_shop->plat_name; ?>"></i>&nbsp;<?php if (isset($bind_shop->shop_name)) echo $bind_shop->shop_name; ?></dd>
            </dl>
		</div>
		<div class="task_message_rgt">
			<div class="back_money_type_logo"></div>
			<?php if ($trade_order->order_status == '0'): ?>
			<p class="task_state">活动状态： 已接手，待开始</p>
			<?php elseif (in_array($trade_order->order_status, ['1','2'])): ?>
			<p class="task_state">活动状态： 已付款，待发货</p>
                <?php if($trade_order->order_status == '2'): ?>
                    <p style="padding-left:16px;font-size:15px;">请自行在淘宝发货后再在平台点击确认发货</p>
                    <p style="padding: 16px;"><a href="<?= '/review/send_out/'. $trade_order->order_sn; ?>" class="btn btn-info">确认发货</a></p>
                <?php endif; ?>
			<?php elseif ($trade_order->order_status == '3'): ?>
			<p class="task_state">活动状态：商家已发货</p>
			<?php elseif ($trade_order->order_status == '4'): ?>
			<p class="task_state">活动状态：已收货，待商家确认返款</p>
			<?php elseif ($trade_order->order_status == '5'): ?>
			<p class="task_state">活动状态： 商家已返款，待买手确认</p>
			<?php elseif ($trade_order->order_status == '6'): ?>
			<p class="task_state">活动状态：金额已驳回</p>
			<?php elseif ($trade_order->order_status == '7'): ?>
			<p class="task_state">活动状态：已完成</p>
			<?php elseif ($trade_order->order_status == '97'): ?>
			<p class="task_state">活动状态：活动已取消</p>
			<?php elseif ($trade_order->order_status == '98'): ?>
			<p class="task_state">活动状态：活动已取消</p>
			<?php elseif ($trade_order->order_status == '99'): ?>
			<p class="task_state">活动状态：活动超时取消</p>
			<?php endif; ?>
		</div>
	</div>
	<div class="content2" style="width: 1170px;">
		<div class="task_wrap">
			<p class="task_detail_title">活动进展</p>
			<table class="table table-bordered table-hover table-striped">
				<thead><tr><th>服务项目</th><th>完成时间</th><th>状态</th></tr></thead>
				<tbody>
					<!-- 浏览店铺及聊天只有PC端活动有  手机端没有 -->
					<?php if ($trade_order->channel == '1'): ?>
					<tr>
						<td>浏览店铺及在线客服聊天</td>
						<?php if ($order_info->talk_time > 0): ?>
						<td><?php echo date('Y-m-d H:i:s', $order_info->talk_time); ?></td>
						<td>
							<p class="task_correct">买手已完成</p>
							<?php if (isset($order_info->view_goods_url1) && $order_info->view_goods_url1): ?>
							<p><?php echo $order_info->view_goods_url1; ?>&nbsp;&gt;&nbsp;<a class="J_copytext goods_url_copy" data-copy="<?php echo $order_info->view_goods_url1; ?>" href="javascript:;">复制</a></p>
							<?php endif; ?>
							<?php if (isset($order_info->view_goods_url2) && $order_info->view_goods_url2): ?>
							<p><?php echo $order_info->view_goods_url2; ?>&nbsp;&gt;&nbsp;<a class="J_copytext goods_url_copy" data-copy="<?php echo $order_info->view_goods_url2; ?>" href="javascript:;">复制</a></p>
							<?php endif; ?>
							<?php if (isset($order_info->view_goods_url3) && $order_info->view_goods_url3): ?>
							<p><?php echo $order_info->view_goods_url3; ?>&nbsp;&gt;&nbsp;<a class="J_copytext goods_url_copy" data-copy="<?php echo $order_info->view_goods_url3; ?>" href="javascript:;">复制</a></p>
							<?php endif; ?>
							<?php if (isset($order_info->view_goods_url4) && $order_info->view_goods_url4): ?>
							<p><?php echo $order_info->view_goods_url4; ?>&nbsp;&gt;&nbsp;<a class="J_copytext goods_url_copy" data-copy="<?php echo $order_info->view_goods_url4; ?>" href="javascript:;">复制</a></p>
							<?php endif; ?>
							<p>在线客服聊天&nbsp;&nbsp&gt;&gt;&nbsp;

							<a target="_blank" href="<?php if (isset($order_info->talk_img) && $order_info->talk_img) echo $order_info->talk_img; ?>">查看截图</a></p>
<!-- 							<p>重新上传截图：</p>
							<p><input type="file" onChange="javascript:upload_order(this);" id="order_img1" uploaded="0"><a class="upload_btn upload_btn1" href="javascript:;">上传</a></p>
							<p><span style="display: inline-block;" class="correct">图片上传好了！</span><a target="_blank" href="http://7xswg2.com1.z0.glb.clouddn.com/static/order/20170522/1a3649ddc6e68c1bcc9845acb0812556.png">预览图片</a></p>
							<span class="error">图片格式错误</span> -->
						</td>
						<?php else: ?>
						<td width="240">--</td>
						<td width="430">
							<p>未开始</p>
						</td>
						<?php endif; ?>
					</tr>
					<?php endif; ?>
					<tr>
						<td width="260">下单和支付</td>
						<td width="240">
							<?php 
								if ($trade_order->pay_sn && $trade_order->pay_time)
									echo date('Y-m-d H:i:s', $trade_order->pay_time);
								else
									echo '--';
							?>
						</td>
						<td width="430">
							<?php if ($trade_order->pay_sn && $trade_order->pay_time): ?>
							<p class="task_correct" style="margin-right:16px;float:left;height:50px;line-height:50px;">买手已完成，订单号：<?php echo $trade_order->pay_sn; ?></p>
							<p>
								<img src="<?= $order_info->order_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $order_info->order_img; ?>" height="50" width="50" class="see_img" />
								<?php if ($order_info->order_img2): ?>
								<img src="<?= $order_info->order_img2. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $order_info->order_img2; ?>" height="50" width="50" class="see_img" />
								<?php endif; ?>
							</p>

<!-- 							<p>重新上传截图：</p>
							<p><input type="file" onChange="javascript:upload_order(this);" id="order_img2" uploaded="0"></p>
							<p><input type="file" onChange="javascript:upload_order(this);" id="order_img3" uploaded="0"><a class="upload_btn upload_btn2" href="javascript:;">上传</a></p>
							<p class="uploaded_success"><span style="display: inline-block;" class="correct">图片上传好了！</span><a target="_blank" href="http://7xswg2.com1.z0.glb.clouddn.com/static/order/20170522/1a3649ddc6e68c1bcc9845acb0812556.png">预览图片</a></p>
							<span class="error">图片格式错误</span> -->
							<?php else: ?>
							<p>未开始</p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td width="260">商家发货</td>
						<?php if ($trade_order->send_time > 0): ?>
						<td width="240"><?php echo date('Y-m-d H:i:s', $trade_order->send_time); ?></td>
						<td width="430">
							<p class="task_correct">商家已完成</p>
						</td>
						<?php else: ?>
						<td width="240">--</td>
						<td width="430">
							<p>未开始</p>
						</td>
						<?php endif; ?>

					</tr>
					<tr style="border: none;">
						<td width="260">
							<p>收货并好评</p>
						</td>
						<?php if ($order_info->comment_time > 0): ?>
						<td width="240"><?php echo date('Y-m-d H:i:s', $order_info->comment_time); ?></td>
						<td width="430">
							<p class="task_correct">买手已确认收货并好评</p>
						</td>
						<?php else: ?>
						<td width="240">--</td>
						<td width="430">
							<p>未开始</p>
						</td>
						<?php endif; ?>
					</tr>
					<?php if ($order_info->comment_time > 0): ?>
					<tr>
						<td colspan="3">
							<ul>
								<li>买号：<?php echo $bind_account->account_name; ?></li>
								<li>商品名称：<?php echo $trade_item->goods_name; ?></li>
								<li>评价内容：<?php echo $order_info->goods_comment; ?></li>
<!-- 								<li class="modify_evaluate_wrap">
									<span>评价内容：</span>
									<p>
										<textarea class="modify_evaluate" name="" id="" cols="30" rows="10"></textarea>
										<a class="submit_modify_evaluate" href="javascript:;">修改</a>
									</p>
								</li> -->

								<li>
                                    <?php if ($order_info->kwd_img && $kwdimg_show): ?>
                                        <!--  关键词截图  -->
                                        <span>关键词截图：</span>
                                        <img src="<?= $order_info->kwd_img. '?imageView/1/w/64/h/64'; ?>" data-src="<?= $order_info->kwd_img ?>" class="see_img" style="margin:8px;" />
                                    <?php endif; ?>
                                    <span>物流、评价截图：</span>
                                    <img src="<?= $order_info->delivery_img. '?imageView/1/w/64/h/64'; ?>" data-src="<?= $order_info->delivery_img ?>" class="see_img" style="margin:8px;" />
                                    <img src="<?= $order_info->goods_eval_img. '?imageView/1/w/64/h/64'; ?>" data-src="<?= $order_info->goods_eval_img ?>" class="see_img" style="margin:8px;" />

                                </li>
							</ul>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td width="260"><?php if ($trade_order->plat_refund): ?>平台<?php else: ?>商家<?php endif; ?>返款</td>
						<?php if ($trade_order->confirm_time > 0): ?>
						<td width="240"><?php echo date('Y-m-d H:i:s', $trade_order->confirm_time); ?></td>
						<td width="430">
							<p class="task_correct">商家已确认返款金额：<span class="color_red"><?php echo $trade_order->order_money; ?></span>元</p>
							<?php if ($trade_order->plat_refund): ?>
							<p class="color_red">平台将会在买手确认返款金额无误后，将垫付本金打入买手账户</p>
							<?php else: ?>
							<p class="color_red">平台将会在买手确认返款金额无误后，将垫付本金打入买手账户</p>
							<?php endif; ?>
						</td>
						<?php else: ?>
						<td width="240">--</td>
						<td width="430">
							<p>未开始</p>
						</td>
						<?php endif; ?>
					</tr>
					<tr>
						<td width="260"><?php if ($trade_order->plat_refund): ?>平台<?php else: ?>商家<?php endif; ?>返款完成</td>
						<?php if ($trade_order->refund_time > 0): ?>
						<td width="240"><?php echo date('Y-m-d H:i:s', $trade_order->refund_time); ?></td>
						<td width="430">
							<p class="task_correct">已完成,买手确认<?php if ($trade_order->plat_refund): ?>平台<?php else: ?>商家<?php endif; ?>返款</p>
						</td>
						<?php else: ?>
						<td width="240">--</td>
						<td width="430">
							<p>未开始</p>
						</td>
						<?php endif; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="content2" style="width:1170px;">
		<div class="task_wrap">
			<p class="task_detail_title">商品信息</p>
			<table class="table table-bordered table-hover">
				<thead>
					<th colspan="2">商品</th>
					<th>单价（元）</th>
					<th>数量</th>
					<th>运费（元）</th>
				</thead>
				<tbody>
					<tr>
						<td style="width:64px">
							<?php if ($trade_item->goods_img): ?><img src="<?= $trade_item->goods_img. '?imageView/3/w/128/h/128'; ?>" width="55" height="55" /><?php endif; ?>
						</td>
						<td><?php echo $trade_item->goods_name; ?></td>
						<td><?php if (isset($trade_info->price)) echo $trade_info->price*1; ?></td>
						<td><?php if (isset($trade_info->buy_num)) echo $trade_info->buy_num; ?></td>
						<td><?php if (isset($trade_info->post_fee)) echo $trade_info->post_fee*1; ?>元( 快递 )</td>
					</tr>
				</tbody>
			</table>
			<p class="buyer_goods_price">买手实付款：<span class="color_red f18"><?php echo $trade_order->order_money; ?>元</span></p>
		</div>
	</div>

	<div class="content2" style="width:1170px;">
		<div class="task_wrap">
			<p class="task_detail_title">评价信息</p>
			<?php if (isset($trade_info->eval_type) && in_array($trade_info->eval_type, ['0','1','2','3','4'], true)): ?>
            <p>评价类型：<?php echo ['0' => '自由好评', '1' => '默认好评', '2' => '关键词好评', '3' => '指定好评', '4' => '图文好评'][$trade_info->eval_type]; ?></p>
        	<?php endif; ?>
			<?php if ($setting_eval): ?>
			<p>指定评价内容：<?php echo $setting_eval->content; ?></p>
			<?php endif; ?>
			<?php if (isset($kwds) && $kwds): ?>
			<p>指定关键词：<?php echo $kwds; ?></p>
			<?php endif; ?>
			<?php if ($setting_img): ?>
			    <p class="task_detail_title">上传图片：</p>
				<?php if ($setting_img->img1): ?>
				<img src="<?php echo $setting_img->img1; ?>" alt="" width="100" height="100">
				<?php endif; ?>

				<?php if ($setting_img->img2): ?>
				<img src="<?php echo $setting_img->img2; ?>" alt="" width="100" height="100">
				<?php endif; ?>

				<?php if ($setting_img->img3): ?>
				<img src="<?php echo $setting_img->img3; ?>" alt="" width="100" height="100">
				<?php endif; ?>

				<?php if ($setting_img->img4): ?>
				<img src="<?php echo $setting_img->img4; ?>" alt="" width="100" height="100">
				<?php endif; ?>

				<?php if ($setting_img->img5): ?>
				<img src="<?php echo $setting_img->img5; ?>" alt="" width="100" height="100">
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</body>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view("/common/view_big_image"); ?>
<script type="text/javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function () {
    // 重新上传图片
    $(".upload_btn1").click(function (event) {
        $(".error").hide();
        var img = $(".order_img1").attr("uploaded");
        if (img == 0) {
            $(".order_img1").parent().siblings(".error").show();
        }
    });
    $(".upload_btn2").click(function (event) {
        $(".error").hide();
        var img1 = $(".order_img2").attr("uploaded");
        var img2 = $(".order_img3").attr("uploaded");
        if (img1 == 0 || img2 == 0) {
            $(".order_img3").parent().siblings(".error").show();
        }
    });
});

//绑定买号上传图片验证
function upload_order(event) {
    var docObj = document.getElementById(event.id);
    lrz(event.files[0], function (res) {
        $(event).attr('base64', res.base64);
        $(event).attr('uploaded', 1);
    });
    $(event).parent().siblings('.error').hide();
    //验证图片格式
    if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])) {

        $(event).attr('base64', "");
        $(event).attr('uploaded', 0);
        $(event).parent().siblings('.error').show();
        return false;
    }
    else {
        //验证图片大小
        if (docObj.files[0]['size'] > 1024 * 1024) {
            $(event).attr('base64', "");
            $(event).attr('uploaded', 0);
            $(event).parent().siblings('.error').show();
            return false;
        }
    }
    $(event).parent().siblings('.correct').show();
}

// 复制
$.getScript('/static/js/jquery.zclip.min.js', function () {
    $('.J_copytext').zclip({
        path: '/static/js/ZeroClipboard.swf',
        copy: function () {
            return $(this).attr('data-copy');
        },
        afterCopy: function () {
            $(this).css({'color': 'red'}).text('复制成功');
        }
    });
});
</script>
</html>