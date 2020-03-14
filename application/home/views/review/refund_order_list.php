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
<link rel="stylesheet" href="/static/css/handle_order.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<title>待处理订单-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="center_box">
        <div class="handle_wrap">
            <div class="tab" role="tabpanel" style="display: inline-block;width:100%;">
                <ul class="nav nav-tabs" role="tablist" id="myTab_two">
                    <li role="presentation" class="<?= ($t == 1) ? 'active':''; ?>" style="text-align:center;"><a href="/review/refund_order_list">所有订单(<?php echo $order_cnts['all']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 2) ? 'active':''; ?>" style="text-align:center;"><a href="/review/refund_order_list/2" >已接手，待下单(<?php echo $order_cnts['wait_pay']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 3) ? 'active':''; ?>" style="text-align:center;"><a href="/review/refund_order_list/3" >已下单，待审核(<?php echo $order_cnts['wait_print']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 4) ? 'active':''; ?>" style="text-align:center;"><a href="/review/refund_order_list/4" >已申请退款、待淘宝确认(<?php echo $order_cnts['wait_send']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 6) ? 'active':''; ?>" style="text-align:center;"><a href="/review/refund_order_list/6" >已取消(<?php echo $order_cnts['time_out']; ?>)</a></li>
                </ul>
            </div>
            <form id="order_form" action="/review/refund_order_list/<?php echo $t; ?>">
                <div class="search_order">
                    <span>平台：</span>
                    <select  name="plat_id" class="form-control platform_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($plat_list as $k=>$v): ?>
                        <option value="<?php echo $k; ?>" <?php if ($k == $plat_id): ?>selected<?php endif; ?>><?php echo $v['pname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="margin-left:16px;">店铺：</span>
                    <select name="shop_id" class="form-control task_type_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($shop_list as $v): ?>
                        <option value="<?php echo $v->id; ?>" <?php if ($v->id == $shop_id): ?>selected<?php endif; ?>><?php echo $v->shop_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span>关键字：</span>
                    <select name="key" class="form-control buyer_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <option value="1" <?php if ($key == '1'): ?>selected<?php endif; ?>>订单号</option>
                        <option value="2" <?php if ($key == '2'): ?>selected<?php endif; ?>>买号</option>
                    </select>
                    <input type="text" name="val" value="<?php echo $val; ?>" class="form-control" style="padding-left:4px" />
                    <a class="search_btn" onclick="javascript:$('#order_form').submit();" style="width: 86px;">查&nbsp;&nbsp;询</a>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                <tr style="white-space: nowrap">
                    <th><input type="checkbox" id="all_check" class="all_checked"></th>
                    <th>活动编号</th>
                    <th>商品</th>
                    <th>买号/订单号</th>
                    <th>付款金额（元）</th>
                    <th>状态值</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <form id="export_form" action="/export/send_info/<?php echo $t; ?>" method="post">
                <?php foreach ($res as $v): ?>
                <tr>
                    <td><input name="order_id[]" type="checkbox" class="single_checked" value="<?php echo $v->id; ?>"  osn="<?php echo $v->order_sn;?>" /></td>
                    <td>
                        <p><i class="plat_small plat_<?php echo $v->bind_shop->plat_name; ?>"></i><span style="margin-left:4px;"><?php echo $v->bind_shop->shop_name; ?></span></p>
                        <p style="margin-top:6px;"><a href="<?= '/detail/trade/'. $v->trade_id; ?>" target="_blank"><strong class="color_red"><?php echo $v->trade_sn; ?></strong></a></p>
                    </td>
                    <td style="width:256px">
                        <img src="<?= $v->trade_search->search_img. '?imageView/3/w/128/h/128'; ?>" alt="商品检索图" style="margin-right:8px;display:inline-block;float: left;width:60px;height:60px;border:1px solid #ddd;" />
                        <div class="overfloat-hidden-2" style="margin:8px 4px;width:156px;"><?php echo $v->trade_item->goods_name; ?></div>
                    </td>
                    <td>
                        <p><?php echo $v->bind_account->account_name; ?></p>
                        <p style="margin-top:6px;"><a href="/detail/order/<?php echo $v->order_sn; ?>" target="_blank"><?php echo $v->pay_sn; ?></a></p>
                    </td>
                    <td><strong class="color_red"><?php echo $v->order_money; ?></strong></td>
                    <td><strong class="color_red"><?php echo $v->status_text; ?></strong></td>
                    <td><?php if ($v->order_status == '4' || $v->order_status == '6'): ?><a href="/review/confirm_taobao_refund/<?php echo $v->order_sn; ?>" class="btn btn-info">确认退款</a><?php else: ?>——<?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
                </form>
                </tbody>
            </table>
            <div class="pager"><?php echo $pagination; ?></div>
            <?php if(!$res): ?><div class="no_record">暂无记录</div><?php endif; ?>
        </div>
    </div>
</body>
<?php $this->load->view("/common/footer"); ?>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/toast/toastr.min.js"></script>
<script src="/static/My97DatePicker/WdatePicker.js"></script>
<script>
    $(function(){
        $(".handle_nav_item").click(function(event) {
            $(this).addClass('active').siblings().removeClass('active');
        });
        $(".all_checked").change(function(event) {
            if($(this).prop("checked")){
                $(".single_checked").prop("checked",true);
                $(".all_checked").prop("checked",true);
                $(".export_number").text($(".single_checked").length);
            }else{
                $(".single_checked").prop("checked",false);
                $(".all_checked").prop("checked",false);
                $(".export_number").text(0);
            }
        });

        $(".single_checked").change(function(event) {
            if($(this).prop("checked")){
                if($(".single_checked:checked").length == $(".single_checked").length){
                    $(".all_checked").prop("checked",true);
                }
            }else{
                $(".all_checked").prop("checked",false);
            }
            $(".export_number").text($(".single_checked:checked").length);
        });
    });
</script>
</html>