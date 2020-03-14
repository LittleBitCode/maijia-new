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
<link rel="stylesheet" href="/static/css/task_detail.css" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<title>活动详情-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
    <div class="task_wrap2">
        <div class="buyer_list">
            <table class="table table-hover table-bordered buyer_list_table">
                <thead>
                    <th width="130">买号</th>
                    <th width="140">下单终端</th>
                    <th width="100">关键字</th>
                    <th width="150">订单号</th>
                    <th width="178">
                        <select style="width:128px;padding: 0; height: 24px;" id="order_status" class="form-control">
                            <?php foreach ($status_type_list as $k=>$v): ?>
                            <option value="<?php echo $k; ?>" <?php if ($k==$status_type): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th width="145">快递信息</th>
                    <th width="100">退款金额</th>
                </thead>
                <tbody>
                    <?php if ($res): ?>
                        <?php foreach ($res as $v): ?>
                        <tr>
                            <td width="130"><?php echo $v->account_name; ?></td>
                            <td width="140">
                                <?php if ($v->channel == '1'): ?>
                                <i class="pc_icon"></i>电脑
                                <?php else: ?>
                                <i class="phone_icon"></i>手机
                                <?php endif; ?>
                            </td>
                            <td width="100"><?= $v->kwd ?></td>
                            <td width="150">
                                <?php if ($v->pay_sn): ?>
                                <p><?php echo $v->pay_sn; ?></p>
                                <a href="/detail/order/<?php echo $v->order_sn; ?>" target="_blank">查看详情&gt;</a>
                                <?php else: ?>
                                <p>--</p>
                                <?php endif; ?>
                            </td>
                            <td width="178"><?= $status_text_list[$v->order_status]; ?></td>
                            <td width="145">
                                <?php if ($v->express_type):  ?><p><?= $shipping_type_list[$v->express_type]['name'] ?></p><?php endif; ?>
                                <?php if ($v->express_sn) echo $v->express_sn; else echo '--'; ?>
                            </td>
                            <td width="100"><?php if ($v->order_money > 0) echo $v->order_money.'元'; else echo '--'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7"><h5>暂无活动记录</h5></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- 这边需要一个分页信息 -->
            <div class="">
                
            </div>
            <div class="export_user_info"><a class="export_user_info_btn" href="javascript:;">导出买手信息</a></div>
            <form id="export_form" action="/export/account_info" target="_parent" method="post">
                <input type="hidden" name="trade_id" value="<?php echo $trade_id; ?>" />
            </form>
        </div>
    </div>

    <script src="//cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(window.parent.document).find("#order_list").css("height",$("body").height());
    </script>

    <script>
        $('#order_status').change(function(){
            location.href = "/frame/order_list_frame/<?php echo $trade_id; ?>?status_type=" + $(this).val();
        });

        $('.export_user_info_btn').click(function (){
            $('#export_form').submit();
        });
    </script>
</body>
</html>