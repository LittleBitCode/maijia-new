<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css?v=1" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css?v=1" />
<link rel="stylesheet" href="/static/css/deposit.css?v=1.1" />
<link rel="stylesheet" href="/static/toast/toastr.min.css?v=1" />
<title>充值押金-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'recode']); ?>
    <div class="center_box">
        <div class="center_r">
            <div class="recharge_box" style="min-height: 512px;">
                <h1>押金充值<span>充值一般<em>2-3</em>分钟后自动到账，若<em>30分钟</em>内未到账请联系客服</span></h1>
                <h3>选择支付方式<span>温馨提示：请确保支付的银行账户为本人账户，平台押金提现都是原路退回</span></h3>
                <div class="choice">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="canChoice alipay <?= ('alipay' == $type) ? 'active' : ''; ?>" data-type="alipay">支付宝</li>
                        <li class="canChoice bank <?= ('bank' == $type) ? 'active' : ''; ?>" data-type="bank">银行转账</li>
                        <li class="canChoice unionpay <?= ('unionpay' == $type) ? 'active' : ''; ?>" data-type="unionpay">网银支付</li>
                    </ul>
                </div>
                <div class="choiceInfo tab-content">
                    <?php if ('alipay' == $type): ?>
                    <div class="choiceStyle">
                        <div class="alipay_box">
                            <div style="margin-top: 32px;">
                                <div style="padding-left: 128px;text-align: center;float: left; width: 60%">
                                    <div><img src="/static/imgs/alipay/import_logo.png"><span style="margin-left:16px;">使用支付宝二维码扫描支付</span></div>
                                    <div>
                                        <img src="<?= $alipay_info['qrcode']; ?>" style="width:294px; height:294px;">
                                        <div style="color: #18bbf5; font-size: 18px;font-weight: bold"><?= $alipay_info['name']; ?></div>
                                    </div>
                                    <div style="margin-top: 4px;letter-spacing: 4px;">平台最低<span class="red">500</span>起充</div>
                                </div>
                                <div><img src="/static/imgs/alipay/tips.png"></div>
                            </div>
                            <div class="f24 j-random" style="margin-top:50px;margin-left:228px;font-size:30px;font-weight:600;letter-spacing:4px;color: #646464">转账时请务必备注：<span class="red"></span></div>
                            <div class="f20" style="margin-left: 128px;margin-top:8px;">（此码每次仅可使用一次，如需再次充值请刷新页面重新获取）</div>
                            <div class="red f14" style="margin: 16px 128px;">温馨提示：<small>如您备注错填、漏填、或者支付完成后超过10分钟还没有到账的，请提供充值截图给客服帮您处理。</small></div>
                        </div>
                    </div>
                    <?php elseif ('wx' == $type): ?>
                        <div class="choiceStyle">
                            <div class="wx_box">
                                <div style="margin-top: 32px;">
                                    <div style="padding-left: 128px;text-align: center;float: left; width: 60%">
                                        <div><img src="/static/imgs/alipay/import_logo.png"><span style="margin-left:16px;">使用微信二维码扫描支付</span></div>
                                        <div>
                                            <img src="<?= $wx_info['qrcode']; ?>" style="width:294px; height:294px;">
                                            <div style="color: #18bbf5; font-size: 18px;font-weight: bold"><?= $wx_info['name']; ?></div>
                                        </div>
                                        <div style="margin-top: 4px;letter-spacing: 4px;">平台最低<span class="red">500</span>起充</div>
                                    </div>
                                    <div><img src="/static/imgs/alipay/wx_tips.png"></div>
                                </div>
                                <div class="f24 j-random" style="margin-top:50px;margin-left:228px;font-size:30px;font-weight:600;letter-spacing:4px;color: #646464">转账时请务必备注：<span class="red"></span></div>
                                <div class="f20" style="margin-left: 128px;margin-top:8px;">（此码每次仅可使用一次，如需再次充值请刷新页面重新获取）</div>
                                <div class="red f14" style="margin: 16px 128px;">温馨提示：<small>如您备注错填、漏填、或者支付完成后超过10分钟还没有到账的，请提供充值截图给客服帮您处理。</small></div>
                            </div>
                        </div>
                    <?php elseif ('bank' == $type): ?>
                    <div class="choiceStyle <?= ('bank' == $type) ? 'show' : 'hide'; ?>" id="bank_box">
                        <h2 style="font-size:18px;margin-top:30px;font-weight:800;display:block;">接受汇款的银行账户<span style="font-size: 16px;font-weight: normal;margin-left: 20px;">充值一般<em class="red">2-3</em>分钟后自动到账<em></em></span></h2>
                        <div class="ZXBank">
                            <div class="ZXbank_left"><img src="/static/imgs/account/yinhangka.png" style="width:410px;" alt=""/></div>
                            <div class="ZXBank_info">
                                <span>户&nbsp;&nbsp;名：<strong><?= $bank_info['account_name']; ?></strong></span>
                                <span>开户行：<strong><?= $bank_info['bank_name']; ?></strong></span>
                                <span>账&nbsp;&nbsp;号：<strong><?= implode("&nbsp;&nbsp;", str_split($bank_info['account'], 4)); ?></strong></span>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <div style="text-align: center;margin-top:48px;">
                            <div class="f24 j-random" style="margin:50px 0 70px 0;font-size:30px;font-weight:600;letter-spacing:4px;color: #646464">转账时请务必添加<em class="red">转账附言</em>：<span class="red"></span></div>
                            <div style="letter-spacing: 4px;font-size:20px;">平台最低<span class="red">500</span>起充</div>
                            <div class="f20" style="margin-top:8px;">（此码每次仅可使用一次，如需再次充值请刷新页面重新获取）</div>
                            <div class="red f14" style="margin: 16px 0;">温馨提示：<small>如您备注错填、漏填、或者支付完成后超过10分钟还没有到账的，请提供充值截图给客服帮您处理。</small></div>
                        </div>
                    </div>
                    <?php elseif ('unionpay' == $type): ?>
                    <div class="choiceStyle <?= ('unionpay' == $type) ? 'show' : 'hide'; ?>">
                    <div class="box_2" >
                        <ul id="myTab_two" class="nav nav-tabs myTab" style="margin-top:20px;">
                            <li class="active"><a href="#save" data-toggle="tab">储蓄卡</a></li>
                            <li><a href="#credit" data-toggle="tab">信用卡</a></li>
                        </ul>
                        <div class="tab-content" style="padding:0;border:0;">
                            <div class="tab-pane fade tast-tab-pane active in" id="save">
                                <form id="deposit_form" action="/recharge/deposit_submit" method="post" target="_blank">
                                    <input type="hidden" name="pay_id">
                                    <input type="hidden" name="cart_type" value="1" />
                                    <div class="bank_list">
                                        <ul>
                                            <?php $idx = 0; ?>
                                            <?php foreach ($bank_pay_list as $key => $item): if ($item['is_show'] != '1') continue; $idx++; ?>
                                                <li pay_id="<?= $key ?>" style="<?= ($idx % 5 == 0) ? 'margin-right:0' : ''; ?>" class="<?= ($idx==1) ? 'cur' : ''; ?>"><i></i><img src="<?= $item['img'] ?>" alt="" style="top:0;margin-right:6px;"/><?= $item['title']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="recharge_number" style="">
                                        <h5>填写充值金额</h5>
                                        <div class="recharge_number_con">
                                            <p>账户余额：<?= $user_info->user_deposit; ?>元</p>
                                            <div>充值金额：<input type="text" name="recharge_number" class="form-control" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" autocomplete="off" disableautocomplete="">元<span>(最低500元起充)</span></div>
                                            <input type="button" class="recharge_butt" value="确&nbsp;&nbsp;认" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade tast-tab-pane" id="credit">
                                <form id="deposit_form" action="/recharge/deposit_submit" method="post" target="_blank">
                                    <input type="hidden" name="pay_id">
                                    <input type="hidden" name="cart_type" value="2" />
                                    <div class="bank_list">
                                        <ul>
                                            <?php $idx = 0; ?>
                                            <?php foreach ($bank_pay_list as $key => $item): if ($item['is_show'] != '1' || $item['credit_cart'] != 1) continue; $idx++; ?>
                                                <li pay_id="<?= $key ?>" style="<?= ($idx % 5 == 0) ? 'margin-right:0' : ''; ?>" class="<?= ($idx==1) ? 'cur' : ''; ?>"><i></i><img src="<?= $item['img'] ?>" alt="" style="top:0;margin-right:6px;"/><?= $item['title']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="recharge_number" style="">
                                        <h5>填写充值金额</h5>
                                        <div class="recharge_number_con">
                                            <p>账户余额：<?= $user_info->user_deposit; ?>元</p>
                                            <div>充值金额：<input type="text" name="recharge_number" class="form-control" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" autocomplete="off" disableautocomplete="">元<span>(最低500元起充)</span></div>
                                            <input type="button" class="recharge_butt" value="确&nbsp;&nbsp;认" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- 弹出框 -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title red" id="myModalLabel">充值成功</h4>
                </div>
                <div class="modal-body">
                    <div class="pay-info">
                        <span>转账充值操作成功！即将跳转到充值押金记录 ...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default" href="/">返回首页</a>
                </div>
            </div>
        </div>
    </div>
</body>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
    $(function () {
        var _random_code = '0', _interval = null, _url_type = '<?= $type; ?>' ;
        $(window).load(function (e) {
            if (_url_type == 'alipay' || _url_type == 'bank' || _url_type == 'wx') {
                $.ajax({
                    type: "post",
                    url: "/recharge/get_random_code",
                    dataType: "json",
                    success: function (data) {
                        _random_code = data;
                        if (_url_type == 'alipay') {
                            $('.alipay_box').find('.j-random span').text(data);
                        } else if(_url_type == 'wx') {
                            $('.wx_box').find('.j-random span').text(data);
                        } else if(_url_type == 'bank'){
                            $('#bank_box').find('.j-random span').text(data);
                        }
                        // 启动定时检查充值记录
                        _interval = setInterval("interval_check_status(" + _random_code + ")", 5000);
                    }
                });
            }
        });

        // 充值提交按钮验证
        $(".recharge_butt").click(function () {
            var _box_form = $(this).parents('form');
            var recharge_number = _box_form.find('input[name="recharge_number"]').val();
            if (recharge_number == '') {
                toastr.warning('请填写预备充值的金额');
                return false;
            } else if (parseInt(recharge_number) < parseInt(500)) {
                toastr.warning('充值金额大于500元的整数');
                return false;
            } else if (parseInt(recharge_number) > parseInt(50000)) {
                toastr.warning('为了您的支付安全，如单笔金额>5万元，请分多笔充值！');
            } else {
                var pay_id = _box_form.find('.bank_list li.cur').attr('pay_id');
                _box_form.find('input[name="pay_id"]').val(pay_id);
                _box_form.submit();
            }
        });

        // 选择支付方式
        $(".choice ul .canChoice").click(function(e) {
            var _type = e.target.dataset.type;
            location.href = '/recharge/deposit/' + _type;
        });

        // 选择网银支付
        $(".bank_list ul li").click(function(){
            $(this).addClass("cur").siblings(".bank_list ul li").removeClass("cur");
        });

    });

    function interval_check_status(code) {
        if (code == '' || code.toString().length != 6) {
            return false;
        }

        $.post('/recharge/check_recharge_status', {code: code}, function (data) {
            if (data == '1'){
                $('#myModal').modal('show');
                setTimeout(function (e) {
                    location.href = '/center/record_list';
                }, 2000);
            }
        })
    }
</script>
</html>
