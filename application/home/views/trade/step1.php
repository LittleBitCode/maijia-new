<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>"/>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/static/toast/toastr.min.css"/>
    <link rel="stylesheet" href="/static/css/trade.css?v=<?= VERSION_TXT ?>"/>
    <title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
    <style>
        .shop-item {
            padding: 8px 32px;
            border: 1px dashed #ccc;
            margin: 0 16px;
            color: #444;
            cursor: pointer;
        }

        .shop-items {
            padding: 8px 32px;
            border: 1px dashed #ccc;
            margin: 8px 16px;
            color: #444;
            cursor: pointer;
        }

        .accordion-group.shop-item-active {
            border-color: #219BDA;
            background-color: #e7e7ea !important;
        }

        .shop-items.shop-items-active {
            border-color: #ed702c;
            background: url(/static/imgs/deposit/icon-gou.gif) no-repeat right bottom;
        }

        .accordion .accordion-group {
            position: relative;
            display: block;
            padding: 10px 15px;
            margin: 0 auto;
            border: 1px solid #e1e1e2;
            width: 96%;
            background-color: #ffffff;
        }

        .accordion .accordion-group .accordion-heading a {
            color: #555;
        }

        .accordion .accordion-group .accordion-heading b {
            display: inline-block;
            width: 930px;
        }

        .accordion .accordion-group .accordion-inner {
            color: #888;
        }

        .panel-title a {
            color: #337ab7;
        }
    </style>
</head>
<body>
<?php $this->load->view("/common/top", ['site' => 'trade']); ?>
<div class="com_title">商家报名活动</div>
<!-- 发活动顶部活动步骤进度start -->
<div class="trade_top contain">
    <div class="Process">
        <ul class="clearfix">
            <li style="width:20%"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:20%"><em>2</em><span>填写商品信息</span></li>
            <li style="width:20%"><em>3</em><span>选择活动数量</span></li>
            <li style="width:20%"><em>4</em><span>选增值服务</span></li>
            <li style="width:20%"><em>5</em><span>支付</span></li>
            <li style="width:20%" class="Processlast"><em>6</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <!-- 发活动顶部活动步骤进度start -->
    <div class="step1_box">
        <div class="tit_two">1、选择活动类型</div>
        <div class="tab" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="myTab_two">
                <?php foreach ($bind_shop_cnt_list as $k => $v): ?>
                    <li role="presentation" class="<?= ($trade_info->plat_id == $k) ? 'active' : ''; ?>">
                        <a href="#Section_<?= $v['name'] ?>" aria-controls="home" role="tab" data-toggle="tab"
                           data-plat="<?= $k ?>"><?= $v['pname'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content tabs">
                <?php foreach ($bind_shop_cnt_list as $k => $v): ?>
                    <?php if ($v['bind_shop_list'] && $v['cnt'] > 0): ?>
                        <div role="tabpanel"
                             class="tab-pane bind_empty <?= ($trade_info->plat_id == $k) ? 'active' : ''; ?>"
                             id="Section_<?= $v['name'] ?>" style="text-align: left;">
                            <?php foreach ($v['bind_shop_list'] as $item): ?>
                                <label class="shop-items <?= ($trade_info->shop_id == $item->id) ? 'shop-items-active' : ''; ?>"
                                       data-shop_ww="<?= $item->shop_ww ?>"
                                       data-id="<?= $item->id ?>"><?php echo $item->shop_name; ?></label>
                            <?php endforeach; ?>
                            <?php if (count($v['bind_shop_list']) < MAX_BIND_SHOP_CNT): ?>
                                <p><span>（还可绑定<?php echo MAX_BIND_SHOP_CNT - count($v['bind_shop_list']); ?>
                                        个店铺）</span><a
                                            href="/center/bind/<?php echo $bind_shop_cnt_list[$trade_info->plat_id]['name']; ?>"
                                            class="get_bind_url" target="_blank">+&nbsp;绑定更多店铺</a></p>
                            <?php else: ?>
                                <p><span>（已绑满）</span></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div role="tabpanel"
                             class="tab-pane bind_empty <?= ($trade_info->plat_id == $k) ? 'active' : ''; ?>"
                             id="Section_<?= $v['name'] ?>">
                            <div><img src="\static\imgs\trade\no.png"></div>
                            <h3>您当前还未绑定店铺，无法报名活动，请先绑定店铺后再报名活动</h3>
                            <p><span>（最多可绑定<?= MAX_BIND_SHOP_CNT ?>个店铺）</span><a
                                        href="/center/bind/<?php echo $bind_shop_cnt_list[$trade_info->plat_id]['name']; ?>"
                                        class="get_bind_url">&nbsp;前去绑定店铺&gt;</a></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <p class="panel-title" style="margin-left: 21px;margin-bottom: 15px">活动类型<a
                    href="<?= HELP_CENTER_URL . '/archives/38' ?>" target="_blank" style="margin-left: 8px;">
                <small style="color:#1491fc">（点击查看活动收费规则）</small>
            </a></p>
        <div class="accordion">
            <?php foreach ($trade_type_list as $k => $v): ?>
                <div class="accordion-group <?= ($trade_info->trade_type == $k) ? 'shop-item-active' : ''; ?>"
                     data-id="<?= $k ?>">
                    <div class="accordion-heading">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2"
                           href="#collapse_<?= $k ?>">
                            <label style="cursor:pointer;white-space: nowrap;">
                                <i class="check"></i>
                                <b><?php echo $v['type_name']; ?></b>
                                <span class="red"><span class="f18"><?php echo $v['limit_point']; ?></span>金币起</span>
                            </label>
                        </a>
                    </div>
                    <div id="collapse_<?= $k ?>" class="accordion-body collapse" style="height: 0px;">
                        <div class="accordion-inner"><p><?php echo $v['comment']; ?></p></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="next_box">
        <a href="javascript:;" class="next_step">下一步</a>
        <span>（填写商品信息）</span>
    </div>
</div>
<form action="<?= '/trade/step/' . $trade_info->id ?>" method="post" id="frm_plat">
    <input type="hidden" name="plat_id" value="<?= $trade_info->plat_id ?>"/>
</form>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    $(function () {
        $(".shop-items").click(function () {
            var $this = $(this);
            $this.addClass("shop-items-active").siblings().removeClass("shop-items-active");
            var _plat = $(".nav-tabs").find(".active>a"), _plat_id = _plat.data("plat"), _tab_name = _plat.attr("href");
            var _shop = $(".tab-content").find(_tab_name).find(".shop-items-active");
            if (_shop.length != 1) {
                toastr.warning("请先选择店铺");
                return false;
            }
            if (_plat_id == '1' || _plat_id == '2') {
                var _shop_ww = _shop.data("shop_ww");
                // isBuyOrAuth(_shop_ww)
            }
        });
        $(".accordion-group").click(function () {
            var $this = $(this);
            if ($this.hasClass("disabled")) return false;
            $this.addClass("shop-item-active").siblings().removeClass("shop-item-active");
        });
        $('#myTab_two li>a').click(function (e) {
            var $this = $(this), _plat = $this.data('plat'), $frm_plat = $('#frm_plat');
            $frm_plat.find('input[name="plat_id"]').val(_plat);
            $frm_plat.submit();
        });

        // 刚进入页面 判断是否授权
        $(document).ready(function () {
            var _plat = $(".nav-tabs").find(".active>a"), _plat_id = _plat.data("plat"), _tab_name = _plat.attr("href");
            var _shop = $(".tab-content").find(_tab_name).find(".shop-items-active");
            if (_shop.length != 1) {
                toastr.warning("请先选择店铺");
                return false;
            }
            if (_plat_id == '1' || _plat_id == '2') {
                var _shop_ww = _shop.data("shop_ww");
                // isBuyOrAuth(_shop_ww)
            }
        })

        function isBuyOrAuth(shop_ww) {
            $.ajax({
                type: "POST",
                url: "/trade/ddx_auth",
                data: {"shop_ww": shop_ww},
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {  // 未订购 未授权
                        return true;
                    } else if (res.code == 1) {  // 未订购 未授权
                        $('#authModal').modal('show');
                        return false;
                    } else if (res.code == 2) {  // 订购过期，需要重新订购
                        $('#authModal').modal('show');
                        return false;
                    } else if (res.code == 3) {  // 授权过期，需要重新授权
                        $('#authModal1').modal('show');
                    } else if (res.code == 4) {  // 接口请求失败
                        toastr.warning("接口请求失败");
                        return false;
                    } else {  // 其他错误
                        toastr.warning(res.msg);
                        return false;
                    }
                }
            });
        }

        // 第一步提交
        $('.next_step').click(function () {
            var _plat = $(".nav-tabs").find(".active>a"), _plat_id = _plat.data("plat"), _tab_name = _plat.attr("href");
            var _shop = $(".tab-content").find(_tab_name).find(".shop-items-active");
            _shop_ww = _shop.data("shop_ww");
            if (_shop.length != 1) {
                toastr.warning("请先选择店铺");
                return false;
            }
            var _shop_id = _shop.data("id");
            var _trade_type_obj = $(".accordion").find(".shop-item-active");
            if (_trade_type_obj.length != 1) {
                toastr.warning("请先选择活动类型");
                return false;
            }
            var _trade_type = _trade_type_obj.data("id");
            //if (_plat_id == 1 || _plat_id == 2) {
            //    $.ajax({
            //        type: "POST",
            //        url: "/trade/ddx_auth",
            //        data: {"shop_ww": _shop_ww},
            //        dataType: "json",
            //        success: function (res) {
            //            console.log(res)
            //            if (res.code == 0) {  // 已订购 已授权
            //                $.ajax({
            //                    type: "POST",
            //                    url: "/trade/step1_submit/<?php //echo $trade_info->id; ?>//",
            //                    data: {
            //                        "plat_id": _plat_id,
            //                        "shop_id": _shop_id,
            //                        "trade_type": _trade_type
            //                    },
            //                    dataType: "json",
            //                    success: function (d) {
            //                        if (d == 0)
            //                            location.href = '/trade/step/<?php //echo $trade_info->id; ?>//';
            //                        if (d == 1)
            //                            location.href = '/user/login';
            //                        if (d == 2)
            //                            alert('数据异常');
            //                    }
            //                });
            //            } else if (res.code == 1) {  // 未订购 未授权
            //                $('#authModal').modal('show');
            //            } else if (res.code == 2) {  // 订购过期，需要重新订购
            //                $('#authModal').modal('show');
            //            } else if (res.code == 3) {  // 授权过期，需要重新授权
            //                $('#authModal1').modal('show');
            //            } else if (res.code == 4) {  // 接口请求失败
            //                toastr.warning("接口请求失败");
            //            } else {  // 其他错误
            //                toastr.warning(res.msg);
            //            }
            //        }
            //    });
            //} else {
                $.ajax({
                    type: "POST",
                    url: "/trade/step1_submit/<?php echo $trade_info->id; ?>",
                    data: {
                        "plat_id": _plat_id,
                        "shop_id": _shop_id,
                        "trade_type": _trade_type
                    },
                    dataType: "json",
                    success: function (d) {
                        if (d == 0)
                            location.href = '/trade/step/<?php echo $trade_info->id; ?>';
                        if (d == 1)
                            location.href = '/user/login';
                        if (d == 2)
                            alert('数据异常');
                    }
                });
            // }

        });

        var _show_tips = '<?= $show_tips ?>';
        $(document).ready(function (event) {
            if (_show_tips != '1') {
                $('.show-tips').modal({backdrop: 'static', keyboard: false});
            }
        });
    })
</script>
</body>
<?php $this->load->view("/common/footer"); ?>
<!-- 模态框（Modal） 未授权 未购买 -->
<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="border:none !important;">
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
                <a href="<?= HELP_CENTER_URL . '/archives/315' ?>" style="display:block">
                    <button style="background:#05a2ff;width:200px;height:40px;color:white;border-radius:5px;">前去购买授权
                    </button>
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!-- 未授权 -->
<div class="modal fade" id="authModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="border:none !important;">
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
                <a href="<?= HELP_CENTER_URL . '/archives/330' ?>" style="display:block">
                    <button style="background:#05a2ff;width:200px;height:40px;color:white;border-radius:5px;">前去授权
                    </button>
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<div class="modal small fade show-tips" tabindex="0" role="dialog" aria-labelledby="myLargeModalLabel" style="top:30%;">
    <div class="modal-dialog" role="document">
        <div style="position: absolute;top: -20px;z-index: 1002;width: 600px;text-align: center;"><img
                    src="/static/imgs/trade/taoke_tips.png"/></div>
        <div class="modal-content" style="background:#fff100;width:600px;height:365px;">
            <div class="modal-body text-center">
                <p style="padding-top:124px;font-size:22px;color:#000;">任务期间请关闭淘客、村淘、分享有赏等淘客活动</p>
                <p style="font-size:22px;color:#000;">若因此引起的佣金支出由商家自己承担！</p>
            </div>
            <div style="padding:16px;text-align:center">
                <button type="button" class="btn" data-dismiss="modal"
                        style="font-size:20px;width:165px;height:60px;color:#fff;border-radius:5px;background-color:#fc3131;-moz-box-shadow:0 6px 6px rgba(0, 0, 0, 0.3);-webkit-box-shadow:0 6px 6px rgba(0, 0, 0, 0.3);box-shadow:0 6px 6px rgba(0, 0, 0, 0.3); ">
                    关闭
                </button>
            </div>
        </div>
    </div>
</div>
</html>