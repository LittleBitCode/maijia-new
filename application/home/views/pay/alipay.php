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
    <link rel="stylesheet" type="text/css" href="/static/css/alipay/combined.css?v=1500121548" charset="UTF-8">
    <title>支付宝付款-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
<?php $this->load->view("/common/top", ['site' => 'recode']); ?>
<!--header结束-->
<div class="content">
    <div class="order">
        <div class="order_content detail">
            <ul class="clearfix" style="margin-bottom:0">
                <li><span>付款金额：</span><strong>￥<input type="text" id="j-amount" value="<?= $amount ?>" readonly style="width: 240px;"/></strong><a href="javascript:copyUrl('j-amount');">【复制】</a></li>
                <li><span>收款人：</span><strong><input type="text" id="j-opt-email" value="<?= $opt_email ?>" readonly /></strong>（<?= $opt_name ?>）<a href="javascript:copyUrl('j-opt-email');">【复制】</a></li>
                <li><span>付款说明：</span><strong><input type="text" id="j-title" value="<?= $title ?>" readonly style="width:247px"/></strong> &nbsp;<a href="javascript:copyUrl('j-title');">【复制】</a></li>
            </ul>
        </div>
    </div>

    <div style="padding:32px;border: 1px solid #E0E0E0;border-top: 0;min-height:600px;">
        <ul id="myTab_two" class="nav nav-tabs myTab">
            <li class="active"><a href="#phone_tab" data-toggle="tab">手机支付宝支付</a></li>
            <li><a href="#web_tab" data-toggle="tab">网页支付宝转账</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="phone_tab">
                <div class="content_left">
                    <h4><span>充值流程：打开手机支付宝扫描二维码，输入付款金额和备注支付即可到账</span></h4>
                    <ul class="clearfix hided">
                        <li><span>1、首先打开手机支付宝钱包</span></li>
                        <li><span>2、扫描右则二维码</span></li>
                        <li><span>3、付款金额填写：<strong class="f20"><?= '￥'. number_format($amount, 2); ?></strong></span><img src="/static/imgs/alipay/import_logo.png" style="margin-left: 32px;" /></li>
                        <li><span>4、备注填写：<strong class="f20">【<?= $title; ?>】</strong></span><img src="/static/imgs/alipay/import_logo.png" style="margin-left: 32px;" /></li>
                        <li><span>温馨提示：请勿修改付款金额和备注，<strong>否则不返数据</strong></span></li>
                        <li><span>到账时间：付款成功后，耐心等待<strong>10秒钟</strong></span></li>
                        <li><span><strong>注意事项：</strong><strong>请正确填写备注，否则无法自动到账</strong></span></li>
                    </ul>
                </div>
                <div class="content_right_m">
                    <img src="/static/imgs/alipay/QR.png" style="width:220px; height: 220px;" />
                    <div class="botton-title">金牌卖家</div>
                </div>
            </div>
            <div class="tab-pane fade" id="web_tab">
                <div class="content_left">
                    <h4><span>充值流程：确认充值账号后,登录网页支付宝向我们的支付宝转账.</span></h4>
                    <ul class="clearfix">
                        <li><span>1、首先请登录网页支付宝</span></li>
                        <li><span>2、向本站支付宝账号：<strong class="f20"><?= $opt_email ?></strong> 转账<strong class="f20">￥<?= number_format($amount, 2) ?></strong> 元</span><img src="/static/imgs/alipay/import_logo.png" style="margin-left: 32px;" /></li>
                        <li><span>3、付款说明请填写：<strong class="f20">【<?= $title;?>】</strong></span><img src="/static/imgs/alipay/import_logo.png" style="margin-left: 32px;" /></li>
                        <li><span>温馨提示：请勿修改付款金额和备注，<strong>否则不返数据</strong></span></li>
                        <li><span>到账时间：付款成功后，耐心等待<strong>10秒钟</strong></span></li>
                        <li><span><strong>注意事项：请正确填写备注，否则无法自动到账</strong></span></li>
                    </ul>
                    <div class="btn"><a target="_blank" href="https://auth.alipay.com/login/index.htm?goto=https://shenghuo.alipay.com/send/payment/fill.htm?title=<?= $title;?>" id="sino_Href">登录支付宝付款</a></div>
                </div>
                <div class="content_right_pc">
                    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="alipay" align="middle" width="400" height="400">
                        <param name="movie" value="/static/imgs/alipay/alipay.swf">
                        <param name="quality" value="high">
                        <param name="bgcolor" value="#ffffff">
                        <param name="play" value="true">
                        <param name="loop" value="true">
                        <param name="wmode" value="window">
                        <param name="scale" value="showall">
                        <param name="menu" value="true">
                        <param name="devicefont" value="false">
                        <param name="salign" value="">
                        <param name="allowScriptAccess" value="sameDomain">
                        <!--<![endif]-->
                    </object>
                    <!--[if !IE]>-->
                    <object type="application/x-shockwave-flash" data="/static/imgs/alipay/alipay.swf" width="400" height="400">
                        <param name="movie" value="/static/imgs/alipay/alipay.swf">
                        <param name="quality" value="high">
                        <param name="bgcolor" value="#ffffff">
                        <param name="play" value="true">
                        <param name="loop" value="true">
                        <param name="wmode" value="window">
                        <param name="scale" value="showall">
                        <param name="menu" value="true">
                        <param name="devicefont" value="false">
                        <param name="salign" value="">
                        <param name="allowScriptAccess" value="sameDomain">
                        <!--<![endif]-->
                        <a href="http://www.adobe.com/go/getflash">
                            <img src="/static/imgs/alipay/get_flash_player.gif" alt="获得 Adobe Flash Player">
                        </a>
                        <!--[if !IE]>-->
                    </object>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">注意事项</h4>
            </div>
            <div class="modal-body">
                <img src="/static/imgs/alipay/tips.jpg" />
                <div class="pay-info">
                    <div class="money"><?= $amount ?></div>
                    <div class="comments"><?= $title ?></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">我已知晓进入下一步》》</button>
            </div>
        </div>
    </div>
</div>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $('#myModal').modal('show');
    });

    function copyUrl(obj) {
        var _obj = document.getElementById(obj);
        _obj.select(); // 选择对象
        document.execCommand("Copy", false, null); // 执行浏览器复制命令
    }
</script>
<?php $this->load->view("/common/footer"); ?>
</body>
</html>