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
<title>待处理流量订单-<?= PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top1", ['site' => 'traffic']); ?>
    <div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
    <div class="center_box" style="width: 947px;float: left;">
        <div class="handle_wrap" style="width: 947px;">
            <div class="tab" role="tabpanel" style="display: inline-block;width:100%;">
                <ul class="nav nav-tabs" role="tablist" id="myTab_two">
<!--                    <li role="presentation" class="--><?//= ($t == 1) ? 'active':''; ?><!--" style="text-align:center;"><a href="/review/traffic_list">所有订单(--><?//= $order_cnts['all']; ?><!--)</a></li>-->
                    <li role="presentation" class="<?= ($t == 2) ? 'active':''; ?>" style="text-align:center;"><a href="/review/traffic_list/2" >已提交、待审核(<?= $order_cnts['status_1']; ?>)</a></li>
<!--                    <li role="presentation" class="--><?//= ($t == 3) ? 'active':''; ?><!--" style="text-align:center;"><a href="/review/traffic_list/3" >已审核通过订单(--><?//= $order_cnts['status_2']; ?><!--)</a></li>-->
                    <li role="presentation" class="<?= ($t == 4) ? 'active':''; ?>" style="text-align:center;"><a href="/review/traffic_list/4" >审核不通过订单(<?= $order_cnts['status_3']; ?>)</a></li>
                    <li role="presentation" class="<?= ($t == 6) ? 'active':''; ?>" style="text-align:center;"><a href="/review/traffic_list/6" >已取消(<?= $order_cnts['status_9']; ?>)</a></li>
                </ul>
            </div>
            <form id="order_form" action="/review/traffic_list/<?= $t; ?>">
                <div class="search_order">
                    <span>平台：</span>
                    <select  name="plat_id" class="form-control platform_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($plat_list as $k=>$v): ?>
                        <option value="<?= $k; ?>" <?php if ($k == $plat_id): ?>selected<?php endif; ?>><?= $v['pname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="margin-left:16px;">店铺：</span>
                    <select name="shop_id" class="form-control task_type_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <?php foreach ($shop_list as $v): ?>
                        <option value="<?= $v->id; ?>" <?php if ($v->id == $shop_id): ?>selected<?php endif; ?>><?= $v->shop_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span>关键字：</span>
                    <select name="key" class="form-control buyer_select" style="padding-left: 4px;">
                        <option value="">全部</option>
                        <option value="1" <?php if ($key == '1'): ?>selected<?php endif; ?>>订单号</option>
                        <option value="2" <?php if ($key == '2'): ?>selected<?php endif; ?>>买号</option>
                        <option value="3" <?php if ($key == '3'): ?>selected<?php endif; ?>>活动编号</option>
                    </select>
                    <input type="text" name="val" value="<?= $val; ?>" class="form-control" style="padding-left:4px" />
                    <a class="search_btn" onclick="javascript:$('#order_form').submit();" style="width: 86px;">查&nbsp;&nbsp;询</a>
                    <!-- <a class="export_btn" href="/export/send_info_all/--><?//= $t; ?><!--">导出全部活动信息</a>-->
                    <!-- <a class="export_btn" href="javascript:$('#export_form').submit();">批量导出<span class="export_number">0</span>条活动信息</a>-->

                    <div style="display:block;margin-top:8px;">
                        <span>筛选时间：</span>
                        <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $start_time ?>" style="padding-left:4px;margin-left:0" autocomplete="off"/><span>－</span>
                        <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $end_time ?>" style="padding-left:4px;margin-left:0" autocomplete="off" />
                    </div>

                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                <tr >
                    <th><input type="checkbox" id="all_check" class="all_checked"></th>
                    <th>活动编号/买号</th>
                    <th style="width:200px">商品</th>
                    <th>关键词</th>
                    <th>商品顶部</th>
                    <th>商品底部</th>
                    <th>收藏商品</th>
                    <th>关注店铺</th>
                    <th>加入购物车</th>
                    <th>领取优惠券</th>
                    <th>浏览商品评价</th>
                    <th>个人资料</th>
                    <th>货比三家1</th>
                    <th>货比三家2</th>
                    <th>货比三家3</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                <form id="export_form" action="/export/send_info/<?= $t; ?>" method="post">
                <?php $last_traffic_status = 0 ; ?>
                <?php foreach ($res as $v): ?>
                <tr>
                    <td><input name="order_id[]" type="checkbox" class="single_checked" value="<?= $v->order_id; ?>"  osn="<?= $v->order_sn;?>" /></td>
                    <td>
                        <p><i class="plat_small plat_<?= $v->bind_shop->plat_name; ?>"></i><span style="margin-left:4px;"><?= $v->bind_shop->shop_name; ?></span></p>
                        <p style="margin-top:6px;"><a href="<?= '/detail/trade/'. $v->trade_id; ?>" target="_blank"><strong class="color_red"><?= $v->order_sn; ?></strong></a></p>
                        <p><?= $v->bind_account->account_name; ?></p>
                    </td>
                    <td>
                        <img src="<?= $v->trade_search->search_img. '?imageView/3/w/128/h/128'; ?>" alt="商品检索图" style="margin-right:8px;display:inline-block;float: left;width:60px;height:60px;border:1px solid #ddd;" />
                        <div class="overfloat-hidden-3" style="margin:2px;width:132px;"><?= $v->trade_item->goods_name; ?></div>
                    </td>
                    <td><?php if(isset($v->img_list['key_words_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['key_words_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['key_words_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['goods_top_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['goods_top_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['goods_top_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['goods_bottom_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['goods_bottom_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['goods_bottom_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['collect_goods_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['collect_goods_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['collect_goods_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['collect_shop_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['collect_shop_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['collect_shop_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['shop_cart_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['shop_cart_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['shop_cart_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['coupon_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['coupon_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['coupon_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['goods_eval_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['goods_eval_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['goods_eval_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['user_info_img'])): ?><a href="javascript:;" data-url="<?= $v->img_list['user_info_img'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['user_info_img']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['compare_goods_img1'])): ?><a href="javascript:;" data-url="<?= $v->img_list['compare_goods_img1'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['compare_goods_img1']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['compare_goods_img2'])): ?><a href="javascript:;" data-url="<?= $v->img_list['compare_goods_img2'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['compare_goods_img2']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><?php if(isset($v->img_list['compare_goods_img3'])): ?><a href="javascript:;" data-url="<?= $v->img_list['compare_goods_img3'] ?>" class="j-show-order-imgs"><img src="<?= $v->img_list['compare_goods_img3']. '?imageView/1/w/45/h/45'; ?>" /></a><?php endif; ?></td>
                    <td><strong class="color_red"><?= $v->status_text; ?></strong></td>
                    <?php $last_traffic_status = $v->traffic_status; ?>
                </tr>
                <?php if ($last_traffic_status == '3'): ?>
                <tr><td colspan="2">审核不通过原因：</td><td colspan="10"><?= $v->unchecked_reason ?></td></tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </form>
                </tbody>
            </table>
            <div class="pager"><?= $pagination; ?></div>
            <?php if(!$res): ?><div class="no_record">暂无记录</div><?php endif; ?>
        </div>
    </div>
    </div> 
</body>
<div class="modal fade" id="J-show-big-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" style="max-height:1024px;">
                <img src="" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script language="javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
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
    // 查看大图
    $('.j-show-order-imgs').on('click', function () {
        var _img_url = $(this).data('url');
        var _big_img_box = $('#J-show-big-image');
        _big_img_box.find('img').attr('src', _img_url);
        _big_img_box.modal('show');
    });
});
</script>
</html>