<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/business_center.css?v=<?= VERSION_TXT ?>" />
<title>待处理活动-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="center_box">
        <div class="business_center">
            <!-- 待处理活动start -->
            <div class="box_2">
                <?php $status_0 = 0; $status_1 = 0; $status_2 = 0; $status_3 = 0; $status_4 = 0; $status_99 = 0; $traffic_1 = 0; $refunds = 0; ?>
                <?php foreach ($status_list as $item) { $status_2 += intval($item['status_2']); $status_4 += intval($item['status_4']); $status_0 += intval($item['status_0']); $status_1 += intval($item['status_1']); $status_3 += intval($item['status_3']); $status_99 += intval($item['status_99']); $traffic_1 += intval($item['traffic_1']); $refunds += intval($item['refunds']);} ?>
                <ul id="myTab_two" class="nav nav-tabs myTab">
                    <li class="active"><a href="#refunds" data-toggle="tab">退款订单（<span class="<?= $refunds ? 'red' : ''; ?>"><?= $refunds ?></span>）</a></li>
                    <li><a href="#fh_task" data-toggle="tab">待操作发货（<span class="<?= $status_2 ? 'red' : ''; ?>"><?= $status_2 ?></span>）</a></li>
                    <li><a href="#tk_task" data-toggle="tab">待操作退款（<span class="<?= $status_4 ? 'red' : ''; ?>"><?= $status_4 ?></span>）</a></li>
                    <li><a href="#wait-pay" data-toggle="tab">已接手，待下单（<span class="<?= $status_0 ? 'red' : ''; ?>"><?= $status_0 ?></span>）</a></li>
                    <li><a href="#wait-print" data-toggle="tab">已下单，待打印快递单（<span class="<?= $status_1 ? 'red' : ''; ?>"><?= $status_1 ?></span>）</a></li>
                    <li><a href="#send-out" data-toggle="tab">已发货（<span class="<?= $status_3 ? 'red' : ''; ?>"><?= $status_3 ?></span>）</a></li>
                    <!-- <li><a href="#time-out" data-toggle="tab">超时未提交（<span class="--><?//= $status_99 ? 'red' : ''; ?><!--">--><?//= $status_99 ?><!--</span>）</a></li> -->
                    <li><a href="#traffic" data-toggle="tab">流量订单（<span class="<?= $traffic_1 ? 'red' : ''; ?>"><?= $traffic_1 ?></span>）</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade tast-tab-pane" id="fh_task">
                        <h6 class="f14">买手已付款，待发货<span class="black">（快递单每天打印时间为9:00至21:00，请在打印出来24小时内操作发货，否则会导致物流不更新且平台上会默认48小时后操作发货）</span></h6>
                        <?php if ($status_2 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/order_list/4?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_2'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="tk_task">
                        <h6  class="f14">买手已收货，待退款<span class="black">（请在48小时内操作退款，否则将扣除活动押金中的退款保证金）</span></h6>
                        <?php if ($status_4 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/refund_list/?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_4'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="wait-pay">
                        <h6 class="f14">买手已接手，待下单<span class="black">（买手已接单，预计2小时内完成）</span></h6>
                        <?php if ($status_0 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/order_list/2?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_0'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="wait-print">
                        <h6 class="f14">买手已下单，待打印快递单<span class="black">（快递单预计2小时内打印完成 （快递打印时间为9:00-21点，请耐心等待））</span></h6>
                        <?php if ($status_1 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/order_list/3?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_1'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="send-out">
                        <h6 class="f14">已发货<span class="black"></span></h6>
                        <?php if ($status_3 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/order_list/5?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_3'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="time-out">
                        <h6 class="f14">买手超时未提交订单<span class="black">（买家接到任务没有完成提交且主动放弃,2小时候任务自动超时,请仔细核对,防止买家已经真实下单,导致真实发出）</span></h6>
                        <?php if ($status_99 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/order_list/6?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['status_99'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <div class="tab-pane fade tast-tab-pane" id="traffic">
                        <h6 class="f14">已提交、待审核流量订单<span class="black">（买家提交的截图,客服审核通过系统只保留七天,七天之后系统自动删除,无法再次查看，审核不通过的订单截图单笔任务金币会在任务结束以后进行结算返还至您的账户，如有问题请及时联系在线客服。）</span></h6>
                        <?php if ($traffic_1 > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/traffic_list/1?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['traffic_1'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><div style="padding: 12px 25%;font-size:18px;color:#999;">暂无活动</div><?php endif; ?>
                    </div>
                    <!-- 退款订单 -->
                    <div class="tab-pane fade in active tast-tab-pane" id="refunds">
                        <h6 class="f14">退款订单<span class="black">（买家已在淘宝申请了退款，并上传退款截图，请及时确认）</span></h6>
                        <?php if ($refunds > 0): ?>
                            <ul class="list-inline">
                                <?php foreach ($status_list as $k => $item): ?>
                                    <li>
                                        <img src="/static/imgs/icon/<?= $item['name'] ?>.png"/>
                                        <span><?= $item['pname'] ?> <a href="/review/refund_order_list/4?plat_id=<?= $k; ?>"><span class="black">待处理(<span class="red"><?= $item['refunds'] ?></span>)</span></a></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?><a href="/review/refund_order_list" target="_blank"><div style="padding: 12px 25%;font-size:18px;">查看退款订单</div></a><?php endif; ?>
                    </div>
                </div>
            </div>
            <iframe src="/frame/trade_list_frame" id="trade_list" frameborder="0" scrolling="no" width="100%"></iframe>
        </div>
    </div>
    <div id="iframe_popup"></div>
</body>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
</html>