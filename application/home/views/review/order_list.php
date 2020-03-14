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
                    <li role="presentation" class="<?= ($t == 1) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list">所有订单(<?php echo $order_cnts['all']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 2) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list/2" >已接手，待下单(<?php echo $order_cnts['wait_pay']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 3) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list/3" >已下单，待打印快递单(<?php echo $order_cnts['wait_print']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 4) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list/4" >已下单，待商家发货(<?php echo $order_cnts['wait_send']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 5) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list/5" >已发货(<?php echo $order_cnts['send_out']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 6) ? 'active':''; ?>" style="text-align:center;"><a href="/review/order_list/6" >已取消(<?php echo $order_cnts['time_out']; ?>)</a></li>
                </ul>
            </div>
            <form id="order_form" action="/review/order_list/<?php echo $t; ?>">
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
                    <a class="export_btn" href="/export/send_info_all/<?php echo $t; ?>">导出全部活动信息</a>
                    <a class="export_btn" href="javascript:$('#export_form').submit();">批量导出<span class="export_number">0</span>条活动信息</a>
                    <?php if ($t == 4): ?>
                        <p style="font-size: 17px;color: red;margin-top: 5px;">
                            请核对订单，付款金额与发货地址，如果不对请联系客服！
                            <a class="export_btn" id="batch_btn" style="cursor:pointer;">批量操作发货</a>
                        </p>
                    <?php endif; ?>
                    <?php if ($t == 6): ?>
                    <div style="display:block;margin-top:8px;">
                        <span>筛选时间：</span>
                        <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $start_time ?>" style="padding-left:4px;margin-left:0" autocomplete="off"/><span>－</span>
                        <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $end_time ?>" style="padding-left:4px;margin-left:0" autocomplete="off" />
                    </div>
                    <?php endif; ?>
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
                    <?php if ($t == 4): ?>
                        <th>发货地址</th>
                    <?php endif; ?>
                    <th>状态值</th>
                    <th>快递信息</th>
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
                    <td>
                        <strong class="color_red"><?php echo $v->order_money; ?></strong>
                        <?php if ( in_array($v->order_status,[1,2])) : ?>
 <!--金额修改 -->       <a class="edit_benjin fr" order_sn="<?php echo $v->order_sn; ?>" href="javascript:;">修改</a>
                        <?php endif ?>
                    </td>
                    <?php if ($t == 4): ?>
                    <td>
                        <strong class="color_red">
                            <?php echo $v->bind_account->province.$v->bind_account->city.$v->bind_account->region.$v->bind_account->address; ?>
                        </strong>
                    </td>
                    <?php endif; ?>
                    <td><strong class="color_red"><?php echo $v->status_text; ?></strong></td>
                    <td>
                        <?php if (empty($v->express_type)): ?>
                            <?php if(empty($v->service_shipping)): ?>
                                <p>需自行在淘宝发货</p>
                            <?php else: ?>
                                <p><?= $shiping_type_list[$v->service_shipping]['name']; ?></p>
                                <p>系统打印中 ...</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p><?= $shiping_type_list[$v->express_type]['name'] ?><a type="button" class="click_copy" style="float:right">点击复制</a></p>
                            <input type="text" value="<?= $v->express_sn ?>" style="border: none;background-color:transparent;width: inherit">
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($v->order_status == '2'): ?>
                            <a href="/review/send_out/<?php echo $v->order_sn; ?>" class="btn btn-info">确认发货</a>
                        <?php else: ?>
                            <?php echo $v->status_text; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </form>
                </tbody>
            </table>
            <div class="pager"><?php echo $pagination; ?></div>
            <?php if(!$res): ?><div class="no_record">暂无记录</div><?php endif; ?>
        </div>
    </div>

    <!-- 修改付款金额 -->
    <div class="popup_wrap edit_amount" style="display: none;">
        <div class="back_money_edit_wrap">
            <img class="close" src="/static/imgs/icon/close2.png" alt="">
            <div class="popup_contente">
                <p class="back_money_title">请输入付款金额</p>
                <form id="update_form" action="/review/update_order_money" method="post">
                    <input type="hidden" name="order_sn" />
                    <p><input class="form-control edit_back_money_val" onkeyup="value=value.replace(/[^\d.]/g,'')" type="text" name="order_money"></p>
                    <div class="edit_btn_wrap">
                        <a class="confirm_btn" href="javascript:;">确认</a>
                        <a class="close_btn close" href="javascript:;">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script language="javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<script>
    $(function(){
        $("#batch_btn").click(function(){
            if(!confirm("确认批量操作发货？")) {
                return false;
            }

            $.post("/review/batch_send_out",function(result){
                if(result.error == 0){
                    location.href = "/review/order_list/5";
                }else{
                    alert(result.msg);
                }
            },"json");

        });

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

        // 内容复制操作
        $('.click_copy').click(function () {
            var $this = $(this), _input = $this.parents('td').find('input');
            _input.each(function (idx, obj) {
                obj.select();
                document.execCommand('Copy');
                toastr.info('快递运单号复制成功！');
            })
        });
    });

    // 修改付款金额弹窗
    $(".edit_benjin").click(function(event) {
        $('input[name="order_sn"]').val($(this).attr('order_sn'));
        $(".edit_amount").show();
    });
    $(".close").click(function(event) {
        $(this).parents(".popup_wrap").hide();
    });

    // 修改金额
    $('.confirm_btn').click(function(){
        if($(".edit_back_money_val").val() == ""){
            toastr.warning("请输入返款金额");
            return;
        }
        $('#update_form').submit();
    });

</script>
</html>