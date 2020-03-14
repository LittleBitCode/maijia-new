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
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/css/center.css" />
<link rel="stylesheet" href="/static/css/handle_order.css" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<title>返款订单-<?php echo PROJECT_NAME; ?></title>
<style type="text/css">
    .eval_img{height: 120px;margin-left: 10px;margin-top: 5px;}
    .msg_st{margin-left: 10px;}
</style>
</head>
<body>

	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="center_box">
        <div class="handle_wrap">
            <form id="search_form" action="/review/refund_list/<?php echo $t; ?>">
                <div class="search_order">
                    <span>平台：</span>
                    <select name="plat_id" class="form-control platform_select" style="width:168px;padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($plat_list as $k=>$v): ?>
                        <option value="<?php echo $k; ?>" <?php if ($k == $plat_id): ?>selected<?php endif; ?>><?php echo $v['pname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="margin-left:16px;">店铺：</span>
                    <select name="shop_id" class="form-control task_type_select" style="width:168px;padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($shop_list as $v): ?>
                        <option value="<?php echo $v->id; ?>" <?php if ($v->id == $shop_id): ?>selected<?php endif; ?>><?php echo $v->shop_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span>关键字：</span>
                    <select name="key" class="form-control buyer_select" style="width:108px;padding-left: 4px;">
                        <option value="">全部</option>
                        <option value="1" <?php if ($key == '1'): ?>selected<?php endif; ?>>订单号</option>
                        <option value="2" <?php if ($key == '2'): ?>selected<?php endif; ?>>买号</option>
                    </select>
                    <input type="text" name="val" value="<?php echo $val; ?>" class="form-control" />
                    <a class="btn search_btn">搜&nbsp;&nbsp;索</a>
                </div>
            </form>

            <div class="tab">
                <ul class="nav nav-tabs" role="tablist" style="margin-top: 8px;" id="myTab_two">
                    <li role="presentation" class="<?= ($t == 1) ? 'active' : ''; ?>"><a href="/review/refund_list">待返款订单(<?php echo $order_cnts->plat_refund; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 3) ? 'active' : ''; ?>"><a href="/review/refund_list/3">买手驳回订单(<?php echo $order_cnts->reject; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 4) ? 'active' : ''; ?>"><a href="/review/refund_list/4">已返款订单(<?php echo $order_cnts->refunded; ?>)</a></li>
                </ul>
            </div>

            <!-- 平台返款 -->
            <?php if ($t == 1): ?>
            <div class="all_return_amount">
                <p class="color_red">以下订单买手已收货好评，请确认买手垫付本金是否准确， 确认后平台将从您活动冻结的押金中扣除相应金额返款给买手</p>
                <a class="all_return_amount_btn" href="javascript:;">一键确认</a>
            </div>
            <form id="batch_plat_refund_form" action="/review/batch_plat_refund" method="post">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr style="white-space: nowrap">
                        <th><input type="checkbox" id="all_check" class="all_checked" /></th>
                        <th>活动编号</th>
                        <th>商品</th>
                        <th>买号&nbsp;/&nbsp;订单号</th>
                        <th>订单截图</th>
                        <th>评价截图</th>
                        <th>评价后截图</th>
                        <th>评价内容</th>
                        <th>评价详情</th>
                        <th>垫付本金（元）</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($res as $k=>$v): ?>
                        <tr>
                            <td><input type="checkbox" class="single_checked" name="order_sns[]" value="<?= $v->order_sn; ?>" /></td>
                            <td>
                                <p class="task_shop_name"><i class="plat_small plat_<?php echo $v->bind_shop->plat_name; ?>"></i>&nbsp;&nbsp;<?php echo $v->bind_shop->shop_name; ?></p>
                                <p style="margin-top:6px;"><a href="/detail/order/<?php echo $v->order_sn; ?>" target="_blank"><?php echo $v->order_sn; ?></a></p>
                                <p style="margin-top:6px;">收货时间： </p>
                                <p style="margin-top:3px;"><?php echo $v->order_info->comment_time?date('Y-m-d H:i', $v->order_info->comment_time):'--'; ?></p>
                            </td>
                            <td>
                                <div style="width:200px; display: flex;justify-content: center" >
                                    <img src="<?= $v->trade_item->goods_img. '?imageView/3/w/128/h/128' ?>" alt="<?= $v->trade_item->goods_name; ?>" class="goods_img" />
                                    <div class="goods_name " style="width: 180px;">
                                        <span style="color: red;"><?php echo $v->type_name; ?></span><br>
                                        <span > <?= $v->trade_item->goods_name; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span><?php echo $v->bind_account->account_name; ?></span>
                                <?php if (strpos($v->pay_sn, 'T') !== false) : ?>
                                    <p class="color_red">暂无订单号</p>
                                <?php else : ?>
                                    <p><?php echo $v->pay_sn; ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($v->order_info->order_img): ?>
                                    <img class="order_screenshot see_img goods_img" src="<?= $v->order_info->order_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $v->order_info->order_img; ?>" alt="订单截图" />
                                <?php else: ?>
                                    <span style="color: red;">暂无截图</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($v->order_info->delivery_img): ?>
                                    <img class="order_screenshot see_img goods_img" src="<?= $v->order_info->delivery_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $v->order_info->delivery_img; ?>" alt="物流截图" />
                                <?php else: ?>
                                    <span style="color: red;">暂无截图</span>
                                <?php endif; ?>
                            </td>

                            <td style="text-align:center">
                                <?php if ($v->order_info->goods_eval_img): ?>
                                    <img class="order_screenshot see_img goods_img" src="<?= $v->order_info->goods_eval_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $v->order_info->goods_eval_img; ?>" alt="评价截图" />
                                <?php else: ?>
                                    <span style="color: red;">暂无截图</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center">
                                <?php if ($v->order_info->goods_comment): ?>
                                    <span style="color: red;"><?php echo $v->order_info->goods_comment ?></span>
                                <?php else: ?>
                                    <span style="color: red;">暂无评价</span>
                                <?php endif; ?>
                            </td>
                            <!--新增评价详情-->
                            <td >
                                <?php if($v->eval_details): ?>
                                <p ><?php echo $v->eval_details->eval_type_name; ?></p>
                                 <?php if(!in_array($v->eval_details->eval_type, [0, 1])): ?>
                                 <p style="margin-top:6px;"><a href="javascript:;"  class="btn_eval_detail"  onclick=show('<?= $v->eval_details->eval_type ?>','<?= $v->order_sn; ?>','<?= $v->trade_id; ?>') >查看详情</a></p>
                                 <?php endif ?>
                                <?php endif ?>
                            </td>
                            <td style="text-align:center">
                                <strong class="color_red"><?php echo $v->order_money; ?></strong>
                                <a class="edit_benjin fr" order_sn="<?php echo $v->order_sn; ?>" href="javascript:;">修改</a>
                            </td>
                            <td>
                                <a class="true_return_amount_btn plat_refund_btn"  order_no="<?= $v->order_sn; ?>"  href="javascript:;">确认返款</a>
                                <?php if ($v->trade_type != '111') : ?>
                                    <a class="fr reject_btn" order_no="<?= $v->order_sn; ?>" href="javascript:;" style="margin-top: 10px;">驳回评价</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
            <?php if (!$res): ?><div class="no_record">没有记录</div><?php endif; ?>

            <!-- 买手驳回订单 -->
            <?php elseif ($t == 3): ?>
            <div class="all_return_amount">
                <p class="color_red">以下订单的返款金额买手有异议，退款已驳回，押金已重新返还到您的<?php echo PROJECT_NAME; ?>账户并冻结；请按照买手修改过的金额，重新确认应返还的金额是否准确，确认无误后请点击“确认返款”</p>
            </div>

            <table class="table table-responsive table-bordered">
                <thead>
                <tr style="white-space: nowrap">
                    <th>活动编号</th>
                    <th>商品</th>
                    <th>买号</th>
                    <th>订单号</th>
                    <th>订单截图</th>
                    <th>垫付本金（元）</th>
                    <th>确认收货时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($res as $v): ?>
                    <tr>
                        <td>
                            <p class="task_shop_name"><i class="plat_small plat_<?php echo $v->bind_shop->plat_name; ?>"></i>&nbsp;&nbsp;<?php echo $v->bind_shop->shop_name; ?></p>
                            <p style="margin-top:6px;"><a href="/detail/order/<?php echo $v->order_sn; ?>" target="_blank"><?php echo $v->order_sn; ?></a></p>
                        </td>
                        <td>
                          <div style="width:300px;">
                            <img src="<?= $v->trade_item->goods_img. '?imageView/3/w/128/h/128' ?>" alt="<?= $v->trade_item->goods_name; ?>" class="goods_img" />
                            <div class="goods_name overfloat-hidden-3" >
                                <span style="color: red;"><?php echo $v->type_name; ?></span><br>
                                <?= $v->trade_item->goods_name; ?>
                            </div>
                          </div>
                        </td>
                        <td><span><?php echo $v->bind_account->account_name; ?></span></td>
                        <td>
                            <?php if (strpos($v->pay_sn, 'T') !== false) : ?>
                                <p class="color_red">暂无订单号</p>
                            <?php else : ?>
                                <p><?php echo $v->pay_sn; ?></p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($v->order_info->order_img): ?>
                                <img class="order_screenshot see_img goods_img" src="<?= $v->order_info->order_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $v->order_info->order_img; ?>" alt="" />
                            <?php else : ?>
                                <span style="color: red;">暂无图片</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center"><strong class="color_red"><?php echo $v->order_money; ?></strong><a class="edit_benjin fr" order_sn="<?php echo $v->order_sn; ?>" href="javascript:;">修改</a></td>
                        <td><strong><?php echo date('Y-m-d H:i', $v->order_info->comment_time); ?></strong></td>
                        <td><a class="true_return_amount_btn" href="/review/reject_refund/<?php echo $v->order_sn; ?>">确认返款</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$res): ?><div class="no_record">没有记录</div><?php endif; ?>

            <!-- 已返款 -->
            <?php elseif ($t == 4): ?>
            <div class="all_return_amount">
                <p class="color_red">以下订单已返款给买手，相应的金额已从您的活动押金中扣除</p> 
            </div>
            <table class="table table-responsive table-bordered">
                <thead>
                <tr style="white-space: nowrap">
                    <th>活动编辑</th>
                    <th>商品</th>
                    <th>买号</th>
                    <th>订单号</th>
                    <th>订单截图</th>
                    <th>垫付本金（元）</th>
                    <th>返款时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($res as $v): ?>
                    <tr>
                        <td>
                            <p><i class="plat_small plat_<?php echo $v->bind_shop->plat_name; ?>"></i>&nbsp;&nbsp;<?php echo $v->bind_shop->shop_name; ?></p>
                            <p style="margin-top:6px;"><a href="/detail/order/<?php echo $v->order_sn; ?>" target="_blank"><?php echo $v->order_sn; ?></a></p>
                        </td>
                        <td>
                         <div style="width:300px;">
                            <img src="<?= $v->trade_item->goods_img. '?imageView/3/w/128/h/128' ?>" alt="<?= $v->trade_item->goods_name; ?>" class="goods_img" />
                            <div class="goods_name overfloat-hidden-3" >
                                <span style="color: red;"><?php echo $v->type_name; ?></span><br>
                                <?= $v->trade_item->goods_name; ?>
                            </div>
                          </div>
                        </td>
                        <td><span><?php echo $v->bind_account->account_name; ?></span></td>
                        <td>
                            <?php if (strpos($v->pay_sn, 'T') !== false) : ?>
                                <p class="color_red">暂无订单号</p>
                            <?php else : ?>
                                <p><?php echo $v->pay_sn; ?></p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($v->order_info->order_img): ?>
                                <img class="order_screenshot see_img goods_img" src="<?= $v->order_info->order_img. '?imageView/3/w/128/h/128'; ?>" data-src="<?= $v->order_info->order_img; ?>" alt="">
                            <?php else : ?>
                                <span style="color: red;">暂无图片</span>
                            <?php endif; ?>
                        </td>
                        <td><strong class="color_red"><?php echo $v->order_money; ?></strong></td>
                        <td><p>已返款</p><p><?php echo $v->order_info->comment_time?date('Y-m-d H:i', $v->order_info->comment_time):''; ?></p></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$res): ?><div class="no_record">没有记录</div><?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="pager"><?php echo $pagination; ?></div>

    </div>
    <?php $this->load->view("/common/footer"); ?>
    <?php $this->load->view("/common/view_big_image"); ?>
    <!-- 修改退款金额 -->
    <div class="popup_wrap edit_amount" style="display: none;">
        <div class="back_money_edit_wrap" style="width: auto;">
            <img class="close" src="/static/imgs/icon/close2.png" alt="">
            <div class="popup_contente" style="padding-left:20px;padding-right:20px; text-align: left;">
                <p class="back_money_title">请输入返款金额</p>
                <form id="update_form" action="/review/update_order_money" method="post">
                <input type="hidden" name="order_sn" />
                <p><input class="form-control edit_back_money_val" onkeyup="value=value.replace(/[^\d.]/g,'')" type="text" name="order_money"></p>
                <p>
                    <textarea  style="width: 200px;height: 86px;padding: 5px;border: 1px solid #bddffd;background-color: #fbfdff;line-height: 23px;" name="money_upmessage" class="message_text money_upmessage" maxlength="255" rows="5" placeholder="请填写原因..."></textarea>
                </p>
                    <div class="edit_btn_wrap">
                    <a class="confirm_btn" href="javascript:;">确认</a>
                    <a class="close_btn close" href="javascript:;">取消</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 驳回评价截图 -->
    <div class="modal fade" id="reject_box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div style="margin: 150px auto;" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="reject_title">驳回评价截图(点击此操作任务会回到待收货状态)</h4>
                </div>
                <input type="hidden" id="hid_reject_no"  />
                <div class="modal-body">
                    <div class="form-group">
                        <div style="padding-left:20px;" class="chradio">
                            <label style="margin-left:16px;"><input type="radio" name="imgbh"  value="1" data-msg="买家实际未评价" /></label><label class="msg_st">买家实际未评价</label><br>
                            <label style="margin-left:16px;"><input type="radio" name="imgbh" value="2"  data-msg="买家评价内容和任务要求不相符" /></label><label class="msg_st">买家评价内容和任务要求不相符</label><br>
                            <label style="margin-left:16px;"><input type="radio" name="imgbh"  value="3"  /></label><label class="msg_st">其他原因</label><br>
                            <textarea  style="width: 400px;height: 86px;padding: 5px;border: 1px solid #bddffd;background-color: #fbfdff;margin: 5px 15px;line-height: 23px;" class="message_text" maxlength="255" rows="5" placeholder="其它原因请输入..."></textarea>
                        </div>
                    </div>
                    <div class="edit_btn_wrap">
                        <a class="reject_subbtn" href="javascript:;" style="background-color: #d2d8f3">确认</a>
                        <a class="reject_close" href="javascript:;"  style="background-color: #d2d8f3;margin-left: 20px;">取消</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--   评论详情弹出提示框-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div style="margin: 150px auto;" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">评论详情</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="eval_lang" for="txt_departmentname"></label>
                    </div>
                    <div id="img_list"  class="form-group">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    // 修改返款金额
    $(".edit_benjin").click(function(event) {
        $('input[name="order_sn"]').val($(this).attr('order_sn'));
        $(".edit_amount").show();
    });
    $(".close").click(function(event) {
        $(this).parents(".popup_wrap").hide();
    });

    // 全选
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
    // 单选
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

    // 修改金额
    $('.confirm_btn').click(function(){
        if($(".edit_back_money_val").val() == ""){
            toastr.warning("请输入返款金额");
            return;
        }
        if ($(".money_upmessage").val()=="") {
            toastr.warning("请填写修改金额原因");
            return;
        }
        $('#update_form').submit();
    });

    $('.all_return_amount_btn').click(function(){
        var checked_item = $('#batch_plat_refund_form').find(".single_checked:checked");
        if (checked_item.length <= 0) {
            toastr.warning("请先勾选待返款的活动单");
            return false
        }
        $('#batch_plat_refund_form').submit();
    });

    $('.search_btn').click(function(){
        $('#search_form').submit();
    });

    //确认返款
    $(".plat_refund_btn").click(function (event) {
        var order_no = $(this).attr('order_no');
        var this_click=$(this);
        $.ajax({
            type: "POST",
            url: "/review/plat_refund",
            data: {
                "order_no": order_no
            },
            dataType: "json",
            success: function (e) {
                if (e.code<1)
                {
                    this_click.parent().parent().remove();
                }
                else {
                    toastr.warning(e.msg);
                }
            }
        });
    });

    //驳回评价截图弹出框
    $(".reject_btn").click(function (event) {
        $("#hid_reject_no").val($(this).attr('order_no'));
        $('#reject_box').modal('show');
    });
    $(".reject_close").click(function (event) {
        $('#reject_box').modal('hide');
        $("#hid_reject_no").val("");
    });

    //驳回评价截图弹出框点击提交
    $("#reject_box .reject_subbtn").click(function (event) {
        var this_click=$(this);
        var order_no = $("#hid_reject_no").val();
        if (!order_no){
            toastr.warning("没有获取到订单信息！");
            return false;
        }

        var radio_val= $("#reject_box .chradio").find('input[type="radio"]:checked');
        if (radio_val.length==0) {
            toastr.warning("没有选择原因，请选择！");
            return false;
        }
        var reason="";
        if (radio_val.val()=="3") {
            reason=$("#reject_box .message_text").val();
            if (!reason) {
                toastr.warning("请填写驳回原因！");
                return false;
            }
            reason="其他原因："+reason;
        }else {
            reason=radio_val.attr("data-msg");
        }

        $.ajax({
            type: "POST",
            url: "/review/reject_evalImg",
            data: {
                "order_no": order_no,
                "radio_val": radio_val.val(),
                "reason": reason
            },
            dataType: "json",
            success: function (e) {
                if (e.code<1)
                {
                    toastr.warning("驳回成功！");
                    $('#reject_box').modal('hide');
                    this_click.parent().parent().remove();
                    $(".reject_btn[order_no=" + order_no + "]").parents().parents("tr").remove();
                }
                else {
                    toastr.warning(e.msg);
                }
            }
        });
    });

</script>

<script type="text/javascript">
    //弹出评论详情框
    function show(eval_type,order_no,tid)
    {
        if (!eval_type && !order_no && !tid) {
            toastr.warning("参数值不存在");
            return;
        }
        $.ajax({
            type: "POST",
            url: "/review/get_eval_details",
            data: {
                "eval_type": eval_type,
                "order_no": order_no,
                "tid": tid
            },
            dataType: "json",
            success: function (res) {
                if (res.code<1)
                {
                    var type=["4", "5"];
                    var imglist="";
                    $("#myModalLabel").text(res.eval_type_name);
                    if (type.includes(res.eval_type))
                    {
                        $(".eval_lang").text(res.setting_img.content);//评价内容
                        //图文评价
                        if (res.eval_type==4) {
                            if (res.setting_img.img1 != "") {
                                imglist += "<img class='eval_img' src='" + res.setting_img.img1 + "'>";
                            }
                            if (res.setting_img.img2 != "") {
                                imglist += "<img class='eval_img' src='" + res.setting_img.img2 + "'>";
                            }
                            if (res.setting_img.img3 != "") {
                                imglist += "<img class='eval_img' src='" + res.setting_img.img3 + "'>";
                            }
                            if (res.setting_img.img4 != "") {
                                imglist += "<img class='eval_img' src='" + res.setting_img.img4 + "'>";
                            }
                            if (res.setting_img.img5 != "") {
                                imglist += "<img class='eval_img' src='" + res.setting_img.img5 + "'>";
                            }
                        }
                        //视频评价
                        else {
                            imglist +=  "<video width=\"320\" height=\"240\" controls autoplay>\n" +
                                "  <source src="+ res.setting_img.video +" type=\"video/ogg\">\n" +
                                "  <source src="+ res.setting_img.video +" type=\"video/mp4\">\n" +
                                "  <source src="+ res.setting_img.video +" type=\"video/webm\">\n" +
                                "  <object data=\"movie.mp4\" width=\"320\" height=\"240\">\n" +
                                "    <embed width=\"320\" height=\"240\"  src='" + res.setting_img.video + "'>\n" +
                                "  </object>\n" +
                                "</video>";
                        }
                        $("#img_list").html(imglist);
                    }
                    //文字类型评价
                    else {
                        var  content="";
                        if (res.setting_eval!=null||res.setting_eval!="null")
                        {
                            content=  res.setting_eval.content;
                        }
                        $(".eval_lang").text(content);//评价内容
                        //清空html
                        $("#img_list").html("");
                    }
                    $('#myModal').modal();
                }
                else {
                    toastr.warning(res.msg);
                }
            }
        });

    }

</script>

</html>