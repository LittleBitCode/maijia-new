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
<link rel="stylesheet" href="/static/css/task_detail.css" />
<script src="/static/js/jquery-1.12.4.min.js"></script>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<title>活动详情-<?php echo PROJECT_NAME; ?></title>
<style>
    .dl-horizontal { padding: 10px 5px; border-bottom: 1px solid #E7E7E7; }
    .dl-horizontal dt {width:128px !important;} .dl-horizontal dd {margin-left: 138px !important;} .dl-horizontal dd span { color: #f00;}
    #trade_yq tbody tr td{text-align:left;padding-left: 30px;}
</style>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="center_box">
        <div class="task_wrap1">
            <div class="task_wrap_left">
                <div class="task_left_content">
                    <h3>活动信息</h3>
                    <div class="task_detail_item">
                        <dl class="dl-horizontal" style="margin-bottom: 0;min-height: 164px;">
                            <dt>活动类型：</dt><dd><?= $trade_type_text ?></dd>
                            <dt>活动总单数：</dt><dd><span><?= $trade_info->total_num; ?></span>单</dd>
                            <?php if ($trade_info->is_pc): ?>
                                <dt>PC端：</dt><dd><span><?= $trade_info->total_num; ?></span>单</dd>
                            <?php endif; ?>
                            <?php if ($trade_info->is_phone): ?>
                                <dt>手机|Pad端：</dt><dd><span><?= $trade_info->total_num; ?></span>单</dd>
                            <?php endif; ?>
                            <dd>进行中：<span><?= $order_cnts->ongoing; ?></span>单，未接手：<span><?php echo $order_cnts->not_started; ?></span>单，已完成：<span><?php echo $order_cnts->finished; ?></span>单</dd>
                            <?php if($trade_info->trade_type == '4'): ?>
                                <dt>聚划算类型：</dt><dd><?= ($trade_search[0]->kwd == 'jhs_link') ? '手机聚划算—链接直拍' : '手机淘宝—聚划算'; ?></dd>
                                <?php if($trade_search[0]->order_way): ?>
                                <dt>商品分类：</dt><dd><?= $trade_search[0]->order_way ?></dd>
                                <?php endif; ?>
                            <?php elseif ($trade_info->trade_type == '5'): ?>
                                <dt>淘抢购类型：</dt><dd><?= $trade_search[0]->kwd ?></dd>
                                <dt>淘抢购开抢时间：</dt><dd><?= date('m-d H:i',  $trade_search[0]->classify1) ?></dd>
                                <dt>任务起止时间：</dt><dd><?= date('m-d H:i',  $trade_search[0]->classify2). ' - '. date('m-d H:i',  $trade_search[0]->classify3); ?></dd>
                            <?php else: ?>
                                <dt>商品搜索关键词：</dt>
                                <?php $plat_names = ['1' => '淘宝', '2' => '天猫', '淘宝APP', '4' => '京东', '5' => '会场', '14' => '拼多多']; ?>
                                <?php foreach ($trade_search as $v) : ?>
                                <dd><?php echo $plat_names[$v->plat_id]; ?>搜索关键词：<?php echo $v->kwd; ?></dd>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </dl>
                        <dl class="dl-horizontal" style="margin-bottom: 0;">
                            <dt>活动编号：</dt><dd><?= $trade_info->trade_sn ?></dd>
                            <dt>活动发布时间：</dt><dd><?= date('Y-m-d H:i:s', $trade_info->created_time); ?></dd>
                            <dt>店铺：</dt><dd><i class="plat_small plat_<?php echo $bind_shop->plat_name; ?>"></i> <?php echo $bind_shop->shop_name; ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="task_wrap_rgt">
                <div class="task_state_wrap">
                    <!-- 未支付 -->
                    <?php if ($trade_info->trade_status == '0'): ?>
                    <div class="task_wait_pay">
                        <h3>活动状态：活动已提交，等待付款</h3>
                        <div class="task_edit">
                            <span>您还可以：</span>
                            <a href="/trade/step/<?php echo $trade_info->id; ?>">修改活动</a>
                            <a class="revoke_task_btn" href="javascript:;">撤销活动</a>
                        </div>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                        <ul class="task_state_prompt">
                            <li>注意</li>
                            <li>报名活动支付未成功解决方法如下：</li>
                            <li>1.修改活动进行下一步，按照报名活动步骤重新报名活动支付即可。</li>
                            <li>2.撤销活动重新编辑活动，按照提示重新报名活动即可。</li>
                        </ul>
                    </div>
                    <!-- 已支付 -->
                    <?php elseif ($trade_info->trade_status == '1'): ?>
                    <div class="task_wait_examine">
                        <h3>活动状态：活动已提交，等待客服审核</h3>              
                        <p class="examine_prompt">客服预计在：2小时内完成审核（客服审核活动的时间是早9:00-晚9:00）</p>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                            <div class="task_edit">
                                <span>审核之前您还可以：</span>
                                <a class="revoke_task_btn" href="javascript:;">撤销活动</a>
                            </div>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- 进行中 -->
                    <?php elseif ($trade_info->trade_status == '2'): ?>
                    <div class="task_wait_pay">
                        <?php if ($order_cnts->not_started == $trade_info->total_num): ?>
                        <h3>活动状态：活动已提交，等待买手接手</h3>
                        <?php else: ?>
                        <h3>活动状态：买手正在接手中</h3>
                        <p class="buyer_catcher"><a href="<?= '/review/order_list/4?plat_id='. $trade_info->plat_id; ?>" target="_blank">买手已付款，待发货（<?php echo $order_cnts->wait_send; ?>）</a></p>
                        <p class="buyer_catcher"><a href="javascript:;">买手已收货，待退款（<?php echo $order_cnts->wait_refund; ?>）</a></p>
                        <?php endif; ?>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                            <div class="task_edit">
                                <span>您还可以：</span>
                                <a class="revoke_task_btn" href="javascript:;">撤销未接手活动</a>
                            </div>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- 已完成 -->
                    <?php elseif ($trade_info->trade_status == '3'): ?>
                    <div class="task_state_revoke">
                        <h3>活动已完成</h3>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>

                    <?php elseif ($trade_info->trade_status == '4'): ?>
                        <div class="task_wait_pay">
                                <h3>活动状态：已全部接手，待买手完成</h3>
                                <p class="buyer_catcher"><a href="<?= '/review/order_list/4?plat_id='. $trade_info->plat_id; ?>" target="_blank">买手已付款，待发货（<?php echo $order_cnts->wait_send; ?>）</a></p>
                                <p class="buyer_catcher"><a href="javascript:;">买手已收货，待退款（<?php echo $order_cnts->wait_refund; ?>）</a></p>
                            <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                                <p class="task_state_money">
                                    平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                                    平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                                    平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                                </p>
                                <p class="task_state_money">
                                    已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                                    待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                                </p>
                            <?php endif; ?>
                        </div>

                    <!-- 审核不通过 -->
                    <?php elseif ($trade_info->trade_status == '5'): ?>
                    <div class="task_state_revoke">
                        <h3>活动审核不通过</h3>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- 已暂停 -->
                    <?php elseif ($trade_info->trade_status == '6'): ?>
                    <div class="task_state_revoke">
                        <h3>活动已暂停</h3>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                            <div class="task_edit">
                                <span>您还可以：</span>
                                <a class="revoke_task_btn" href="javascript:;">撤销未接手活动</a>
                            </div>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- 已取消 -->
                    <?php elseif ($trade_info->trade_status == '9'): ?>
                    <div class="task_state_revoke">
                        <h3>活动已撤销</h3>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                        <p class="task_state_money">
                            平台返款冻结押金：<span><?= number_format($trade_info->trade_deposit, 2) ?></span>元&nbsp;
                            平台已返还：<span><?= number_format($order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            平台待返还：<span><?= number_format($trade_info->trade_deposit - $order_cnts->finished * $deposit_subtotal, 2) ?></span>元/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <p class="task_state_money">
                            已发放佣金：<span><?= number_format($order_cnts->finished * $point_subtotal, 2) ?></span>金币/<span><?= $order_cnts->finished ?></span>笔&nbsp;
                            待发放佣金：<span><?= number_format(($trade_info->total_num - $order_cnts->finished) * $point_subtotal, 2) ?></span>金币/<span><?= $trade_info->total_num - $order_cnts->finished ?></span>笔
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($trade_info->trade_type != 10): ?>
        <iframe src="/frame/order_list_frame/<?php echo $trade_info->id; ?>" id="order_list" frameborder="0" scrolling="no" width="100%" height="1000"></iframe>
        <?php endif;?>
        <div class="task_wrap2">
            <h3>下单要求</h3>
            <table class="table table-hover table-bordered task_goods"  id="trade_yq">
                <tbody>
                <tr>
                    <td ><?=$task_requirements["chat"]==0?"不需要小二聊天":"要与小二先聊天" ?></td>
                </tr>
                <tr>
                    <td ><?php if ($task_requirements["coupon"]==0) {echo "不领优惠券";}else{echo "需要先领取优惠券、然后再下单;"; if ($task_requirements["coupon_link"]){echo "优惠券:".$task_requirements["coupon_link"];}else {echo "商品下方领取优惠券";} } ?> </td>
                </tr>
                <tr>
                    <td ><?=$task_requirements["credit"]==0?"禁止使用信用卡、花呗付款":"可以使用信用卡、花呗付款" ?></td>
                </tr>
                <tr>
                    <td ><?= $task_requirements["is_post"]==0?"商品不包邮  无需买手联系客服。商家每单额外支出10元作为运费押金，活动完成后运费押金将全部退还给商家":"商品本身包邮  买手按照商品实际金额下单" ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php if (count($trade_scans) > 0) : ?>
            <div class="task_wrap2">
                <h3>浏览任务</h3>
                <table class="table table-hover table-bordered task_goods">
                    <thead>
                    <th width="480" colspan="2">商品</th>
                    <th width="120">关键词</th>
                    <th width="120">店铺名称</th>
                    <th width="360">商品链接</th>
                    </thead>
                    <tbody>
                    <?php foreach ($trade_scans as $trade_scan) : ?>
                        <tr>
                            <td width="75"><img src="<?= $trade_scan->search_img; ?>" alt="" style="width:55px;height:55px;display:block;border:1px solid #ddd;" /></td>
                            <td width="405"><?php echo $trade_scan->goods_name; ?></td>
                            <td width="120"><?=  $trade_scan->kwd; ?></td>
                            <td width="120"><?=  $trade_scan->shop_name; ?></td>
                            <td width="360"><?=  $trade_scan->goods_url; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="task_wrap2">
            <h3>活动商品</h3>
            <table class="table table-hover table-bordered task_goods">
                <thead><th width="480" colspan="2">商品</th><th width="120">单价</th><th width="240">每单刷*个</th><th width="138">小计</th></thead>
                <tbody>
                    <tr>
                        <td width="75"><?php if ($trade_search): ?><img src="<?= $trade_item->goods_img; ?>" alt="" style="width:55px;height:55px;display:block;border:1px solid #ddd;" /><?php endif; ?></td>
                        <td width="405"><?php echo $trade_item->goods_name; ?></td>
                        <td width="120"><span><?php echo $trade_info->price*1; ?></span>元</td>
                        <td width="240"><?= ($trade_info->trade_type == '10') ? $trade_item->buy_num.' - '. $trade_item->weight : $trade_info->buy_num; ?></td>
                        <td width="138"><span><?php echo $trade_info->price*$trade_info->buy_num; ?></span>元</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="task_wrap2">
            <h3>费用合计</h3>
            <?php if($trade_info->trade_type == '10'): ?>
                <table class="table table-hover table-bordered task_cost">
                    <thead>
                    <tr><th width="15%">分类</th><th colspan="4">费用明细</th><th width="20%">合计</th></tr>
                    </thead>
                    <tbody>
                    <?php $index = 0; $total_amount = 0; ?>
                    <?php foreach ($traffic_list as $key => $item): ?>
                    <tr data-type="<?= $key ?>">
                        <?php if (0 == $index): ?><td rowspan="8"><b class="red">服务费</b></td><?php endif; ?>
                        <td><?= $item['title'] ?></td><td><?= $item['days'] ?>天</td><td><?= $item['cnts'] ?>粉丝</td><td><?= $item['price'] ?>金币</td><td><?= $item['amount'] ?>金币</td>
                    </tr>
                    <?php $index++; $total_amount += floatval($item['amount']); ?>
                    <?php endforeach; ?>
                    <tr style="background-color:#eee"><td colspan="5" style="text-align:right;padding-right:6%">费用合计：<span><?= number_format(round($total_amount, 1), 1) ?></span>金币</td></tr>
                    </tbody>
                    <tfoot data-amount="<?= $total_amount ?>">
                    <?php $added_fee_amount = 0; ?>
                    <?php if ($trade_service): ?>
                    <?php foreach ($trade_service as $key => $item): ?>
                    <tr>
                        <?php if($key == 0): ?><td rowspan="100"><b class="red">增值服务</b></td><?php endif; ?>
                        <td colspan="2"><?= $item->comments ?></td><td colspan="2"><?= floatval($item->pay_point) ?>金币</td><td>--</td>
                    </tr>
                    <?php $added_fee_amount += floatval($item->pay_point); ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td rowspan="100"><b class="red">增值服务</b></td><td colspan="2">--</td><td colspan="2">--</td><td>--</td></tr>
                    <?php endif; ?>
                    <tr style="background-color:#eee"><td colspan="5" style="text-align:right;padding-right:6%">费用合计：<span class="red"><?= number_format(round(floatval($added_fee_amount), 1), 1); ?></span>金币</td></tr>
                    </tfoot>
                </table>
                <div class="price_sum" style="margin-right:5%;">费用合计&nbsp;&nbsp;&nbsp;金币：<span><?= number_format(round(floatval($added_fee_amount + $total_amount), 1), 1); ?></span></div>
            <?php else: ?>
                <table class="table table-hover table-bordered task_cost">
                    <thead>
                        <th width="110">分类</th>
                        <th width="220">费用明细</th>
                        <th width="90">小计</th>
                        <th width="198">单数</th>
                        <th width="135">优惠折扣</th>
                        <th width="225">合计</th>
                    </thead>
                    <tbody>
                        <?php if(!in_array($trade_info->trade_type, ['10', '115'])): ?>
                        <tr>
                            <td width="110"><span>押金</span></td>
                            <td width="220">
                                <p>押金：<?php echo $goods_val; ?>元/单 </p>
                                <?php if ($trade_info->post_fee > 0): ?>
                                <p>运费保证金：<?php echo $trade_info->post_fee; ?>元/单</p>
                                <?php endif; ?>
                            </td>
                            <td width="90"><?php echo $deposit_subtotal; ?>元</td>
                            <td class="rowspans" rowspan="3" width="198"><?php echo $trade_info->total_num; ?>单</td>
                            <td width="135">--</td>
                            <td width="225"><?php echo $deposit_subtotal; ?>&nbsp;x&nbsp;<?php echo $trade_info->total_num; ?>&nbsp;=&nbsp;<span><?php echo bcmul($deposit_subtotal, $trade_info->total_num, 2); ?></span>元</td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td width="110"><span>服务费</span></td>
                            <td width="220">
                                <p>套餐服务费：<?php echo $trade_info->total_fee; ?>金币/单 </p>
                                <?php if ($trade_info->is_phone): ?>
                                <p>手机端加成：<?php echo ORDER_DIS_PRICE; ?>金币/单</p>
                                <?php endif; ?>
                            </td>
                            <td width="90"><?php echo $point_subtotal; ?>金币</td>
                            <?php if(in_array($trade_info->trade_type, ['10', '115'])): ?>
                            <td class="rowspans" rowspan="2" width="198">&nbsp;</td>
                            <?php endif; ?>
                            <td width="135">--</td>
                            <td width="225"><?php echo $point_subtotal; ?>&nbsp;x&nbsp;<?php echo $trade_info->total_num; ?>&nbsp;=&nbsp;<span><?php echo bcmul($point_subtotal, $trade_info->total_num, 2); ?></span>金币</td>
                        </tr>
                        <tr>
                            <td width="110"><span>增值服务</span></td>
                            <td width="220">
                                <?php foreach ($trade_service as $v): ?>
                                <?php if($v->service_name == 'custom_time_price'): ?>
                                    <span class="col-xs-12 text-nowrap">分时发布：<?= number_format($v->pay_point, 2). '金币（'. date('Y-m-d', $trade_info->start_time) .'）'; ?></span>
                                    <?php $param_list = json_decode($v->param, true); ?>
                                    <div class="col-xs-2">&nbsp;</div>
                                    <div class="col-xs-10">
                                        <?php foreach ($param_list as $key => $item): ?>
                                        <div class="red text-right col-xs-6" style="padding: 0;float:left;"><?= $key. '时 => '. $item. '单'  ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php elseif($v->service_name == 'set_time'): ?>
                                    <span class="col-xs-12">定时发布时间：<?= number_format($v->pay_point, 2). '金币'; ?></span><div class="text-right red"><?= $v->param ?></div>
                                <?php elseif($v->service_name == 'set_over_time'): ?>
                                    <span class="col-xs-12">定时结束时间：<?= number_format($v->pay_point, 2). '金币'; ?></span><div class="text-right red"><?= $v->param ?></div>
                                <?php elseif($v->service_name == 'traffic_list'): ?>
                                    <span class="col-xs-12">人气权重：<?= number_format($v->pay_point, 2). '金币'; ?></span>
                                    <?php $traffic_list = unserialize($v->param); $traffic_txt_list = ['normal_price' => '浏览商品', 'collect_goods' => '收藏商品', 'add_to_cart' => '加购商品', 'collect_shop' => '收藏店铺', 'get_coupon' => '申请优惠券', 'item_evaluate' => '查看宝贝评价']; ?>
                                    <div class="col-xs-2">&nbsp;</div>
                                    <div class="col-xs-10">
                                        <?php foreach ($traffic_list as $key => $item): ?>
                                            <div class="red text-right" style="padding-right:4px;"><?= $traffic_txt_list[$key]. '：'. $item. '访客/单'  ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p><?= $v->comments . '：' . number_format(round($v->pay_point, 2), 2); ?>金币</p>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td width="90"><?php echo $trade_info->service_point; ?>金币</td>

                            <td width="135">--</td>
                            <td width="225"><span><?php echo $trade_info->service_point; ?></span>金币</td>
                        </tr>
                    </tbody>
                </table>
                <div class="price_sum">费用合计&nbsp;&nbsp;押金：<span><?php echo $trade_info->trade_deposit; ?></span>元&nbsp;金币：<span><?php echo $trade_info->trade_point; ?></span></div>
            <?php endif; ?>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <div class="popup_bg back_money_wrap" style="display: none;">
        <div class="popup_wraps">
            <img class="close" src="/static/imgs/business/record_close.png" alt="">
            <div class="popup_contente">
                <p>撤销活动押金<strong>103.95</strong>元，<?php echo PROJECT_NAME; ?>金币<span>82.49</span>点已退至您的账号</p>
                <p><em class="timer">5</em>s后窗口自动关闭</p>
                <div class="edit_btn_wrap">
                    <a class="close_btn close" href="javascript:;">关闭</a>
                </div>
            </div>
        </div>
    </div>
    <div class="popup_bg cancel_task_wrap" style="display: none;">
        <div class="popup_wraps">
            <img class="close2" src="/static/imgs/business/record_close.png" alt="">
            <div class="popup_contente">
                <p>当前活动还有<span><?php echo $cancel_refund->surplus_num; ?>单</span>未接手，撤销未接手活动</p>
                <p>将解冻押金<span><?php echo $cancel_refund->deposit; ?></span>元，退回金币<span><?php echo sprintf("%.2f", $cancel_refund->point) ; ?></span></p>
                <div class="edit_btn_wrap">
                    <a class="confirm_btn" href="javascript:;">确认</a>
                    <a class="close_btn close2" href="javascript:;">关闭</a>
                </div>
            </div>
        </div>
    </div>

    <form id="cancel_form" action="/trade/cancel/<?php echo $trade_info->id; ?>" method="post"></form>

    <script>
        // 撤销活动弹窗关闭
        $(".close").click(function(event) {
            $(this).parents(".popup_bg").hide();
        });
        // 撤销活动弹窗显示
        $(".revoke_task_btn").click(function(event) {
            $(".cancel_task_wrap").show();
        });
        // 确认撤销活动
        $(".confirm_btn").click(function(event) {
            $(this).parents(".popup_bg").hide();
            $('#cancel_form').submit();
            // $(".back_money_wrap").show();
            // start = 5;
            // count();
        });
        // 撤销活动返还冻结押金和金币弹窗关闭
        $(".close2").click(function(event) {
           $(".cancel_task_wrap").hide();
           // 撤销完成后需要进行的操作
        });

        // 确认买号倒计时
        var start = 5;
        function count() {
            $(".timer").text(start);
            start --;
            if(start < 0){
                $(".back_money_wrap").hide();
                // 撤销完成后需要进行的操作
            }else{
                setTimeout(count, 1000);
            }  
        }
        
    </script>
</body>
</html>