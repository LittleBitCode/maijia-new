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
    <link rel="stylesheet" href="/static/css/trade.css" />
    <link rel="stylesheet" href="/static/toast/toastr.min.css" />
    <title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
<?php $this->load->view("/common/top", ['site' => 'trade']); ?>
<!-- 注意：这里是定义的活动单数、和活动件数 -->
<?php $task_num = $trade_info->total_num; $task_url = $trade_info->buy_num; ?>
<div class="com_title">商家报名活动</div>
<!-- 发活动顶部活动步骤进度start -->
<div class="trade_top">
    <div class="Process">
        <ul class="clearfix">
            <li style="width:25%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:25%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
            <li style="width:25%"><em class="Processyes">3</em><span>选增值服务</span></li>
            <li style="width:25%"><em>4</em><span>支付</span></li>
            <li style="width:25%" class="Processlast"><em>5</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <!-- 发活动顶部活动步骤进度start -->
    <div class="step4_box">
        <h1>3.选择增值服务<p>已选择：<span><?= $trade_select['plat_name']. '&nbsp;|&nbsp;'. $trade_select['shop_name']. '&nbsp;|&nbsp;'. $trade_select['type_name']. '&nbsp;|&nbsp;'. $trade_select['total_num']; ?>单</p></h1>
        <div class="service_box">
            <h3 class="tit_img"><img src="/static/imgs/icon/ic3.png" />访客入店时间分布</h3>
            <div class="time_task_box step4_white j-dist-box">
                <div class="limitBox" style="padding-left:0">
                    <label><input type="checkbox" value="6" <?= $has_type_custom ? 'checked' : ''; ?> data-type="custom" name="dist_shop"><i></i><em>自定义</em><span class="text-grey" style="font-size:14px;">（+6金币）</span></label>
                    <label><input type="checkbox" value="6" <?= $has_type_curve ? 'checked' : ''; ?> data-type="curve" name="dist_shop"><i></i><em>网购用户习惯曲线分布</em><span class="text-grey" style="font-size:14px;">（+6金币）</span></label>
                    <label><input type="checkbox" value="3" <?= $has_type_random ? 'checked' : ''; ?> data-type="random" name="dist_shop"><i></i><em>随机分布</em><span class="text-grey" style="font-size:14px;">（+3金币）</span></label>
                    <div class="grey_bgg time_dist" style="margin-left: 12px;padding-bottom: 16px;width:90%;display: <?= $has_type_custom ? 'block' : 'none'; ?>">
                        <p style="display:inline-block">任务开始时间：</p>
                        <div style="margin-left:32px;">
                            <div>
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">0点 ~ 5点</span><input type="text" class="blue_input" value="<?= isset($custom_list[0]) ? $custom_list[0] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">5点 ~ 9点</span><input type="text" class="blue_input" value="<?= isset($custom_list[1]) ? $custom_list[1] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">9点 ~ 12点</span><input type="text" class="blue_input" value="<?= isset($custom_list[2]) ? $custom_list[2] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            </div>
                            <div style="margin-top: 16px;">
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">12点 ~ 15点</span><input type="text" class="blue_input" value="<?= isset($custom_list[3]) ? $custom_list[3] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">15点 ~ 19点</span><input type="text" class="blue_input" value="<?= isset($custom_list[4]) ? $custom_list[4] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            <span style="display: inline-block;padding-right: 8px;text-align: right;width: 108px;font-weight: 600;color: inherit;">19点 ~ 23点</span><input type="text" class="blue_input" value="<?= isset($custom_list[5]) ? $custom_list[5] : ''; ?>" style="width:64px;"/><span style="color:inherit;margin-left:-6px;font-weight:600">%</span>
                            </div>
                            <p style="display:inline-block">提示：首日任务当前时间段剩余的时间，可能无法完成当前任务量，未完成的任务将会在下一个时间段继续进行；所有时间段的访客占比总合为100%。</p>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic8.png" />指定访客类型</h3>
            <div class="time_task_box step4_white j-custom-type">
                <div class="limitBox" style="padding-left:0">
                    <label><input onclick="toggle(this)" type="checkbox" value="<?= 0.3 * $task_num ?>" <?= $has_sex_limit ? 'checked' : ''; ?> name="choiceSex"><i></i>性别选择<span class="text-grey" style="font-size:14px;">（仅限选择性别用户可接该活动，+<?= 0.3 ?>金币/单）</span></label>
                    <div class="grey_bgg choiceSex" style="margin-left: 12px;padding-bottom: 16px;width:90%;display: <?= $has_sex_limit ? 'block' : 'none'; ?>">
                        <label class="radio"><input type="radio" name="sex" <?= ($sex_limit_val == '1') ? 'checked':''; ?> value="1"><i></i> 男</label>
                        <label class="radio"><input type="radio" name="sex" <?= ($sex_limit_val == '2') ? 'checked':''; ?> value="2"><i></i> 女</label>
                        <div style="clear:both;"></div>
                    </div>
                </div>
                <div class="limitBox" style="padding-left:0">
                    <label><input onclick="toggle(this)" type="checkbox" value="<?= 0.3 * $task_num ?>" name="area" <?= $has_area_limit ? 'checked':''; ?> />地域限制<span class="text-grey">（最多只能选择<span class="red">3</span>个地区限制，+<?= 0.3 ?>金币/单）</span></label>
                    <div class="city_box grey_bgg toggle" style="margin-left: 12px;padding-bottom: 16px;width:90%;display: <?= $has_area_limit ? 'block' : 'none'; ?>">
                        <div style="padding:8px 0 0 12px;">以下所选地区<span class="red">不可接</span>该活动：</div>
                        <div class="city_area">
                            <div><label class="checkbox" style="font-size:15px;">华东</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('上海市', $area_limit_list) ? 'checked' : ''; ?> value="上海市"><i></i>上海</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('江苏省', $area_limit_list) ? 'checked' : ''; ?> value="江苏省"><i></i>江苏</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('浙江省', $area_limit_list) ? 'checked' : ''; ?> value="浙江省"><i></i>浙江</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('安徽省', $area_limit_list) ? 'checked' : ''; ?> value="安徽省"><i></i>安徽</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('江西省', $area_limit_list) ? 'checked' : ''; ?> value="江西省"><i></i>江西</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">华北</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('北京市', $area_limit_list) ? 'checked' : ''; ?> value="北京市"><i></i>北京</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('天津市', $area_limit_list) ? 'checked' : ''; ?> value="天津市"><i></i>天津</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('山西省', $area_limit_list) ? 'checked' : ''; ?> value="山西省"><i></i>山西</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('山东省', $area_limit_list) ? 'checked' : ''; ?> value="山东省"><i></i>山东</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('河北省', $area_limit_list) ? 'checked' : ''; ?> value="河北省"><i></i>河北</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('内蒙古自治区', $area_limit_list) ? 'checked' : ''; ?> value="内蒙古自治区"><i></i>内蒙古</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">华中</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('湖南省', $area_limit_list) ? 'checked' : ''; ?> value="湖南省"><i></i>湖南</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('湖北省', $area_limit_list) ? 'checked' : ''; ?> value="湖北省"><i></i>湖北</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('河南省', $area_limit_list) ? 'checked' : ''; ?> value="河南省"><i></i>河南</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">华南</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('广东省', $area_limit_list) ? 'checked' : ''; ?> value="广东省"><i></i>广东</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('广西壮族自治区', $area_limit_list) ? 'checked' : ''; ?> value="广西壮族自治区"><i></i>广西</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('福建省', $area_limit_list) ? 'checked' : ''; ?> value="福建省"><i></i>福建</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('海南省', $area_limit_list) ? 'checked' : ''; ?> value="海南省"><i></i>海南</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">东北</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('辽宁省', $area_limit_list) ? 'checked' : ''; ?> value="辽宁省"><i></i>辽宁</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('吉林省', $area_limit_list) ? 'checked' : ''; ?> value="吉林省"><i></i>吉林</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('黑龙江省', $area_limit_list) ? 'checked' : ''; ?> value="黑龙江省"><i></i>黑龙江</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">西北</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('陕西省', $area_limit_list) ? 'checked' : ''; ?> value="陕西省"><i></i>陕西</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('新疆维吾尔自治区', $area_limit_list) ? 'checked' : ''; ?> value="新疆维吾尔自治区"><i></i>新疆</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('甘肃省', $area_limit_list) ? 'checked' : ''; ?> value="甘肃省"><i></i>甘肃</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('宁夏回族自治区', $area_limit_list) ? 'checked' : ''; ?> value="宁夏回族自治区"><i></i>宁夏</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('青海省', $area_limit_list) ? 'checked' : ''; ?> value="青海省"><i></i>青海</label></div>
                        </div>
                        <div class="city_area" style="clear:both;">
                            <div><label class="checkbox" style="font-size:15px;">西南</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('重庆市', $area_limit_list) ? 'checked' : ''; ?> value="重庆市"><i></i>重庆</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('云南省', $area_limit_list) ? 'checked' : ''; ?> value="云南省"><i></i>云南</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('贵州省', $area_limit_list) ? 'checked' : ''; ?> value="贵州省"><i></i>贵州</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('西藏自治区', $area_limit_list) ? 'checked' : ''; ?> value="西藏自治区"><i></i>西藏</label></div>
                            <div><label class="checkbox"><input type="checkbox" name="city" <?= in_array('四川省', $area_limit_list) ? 'checked' : ''; ?> value="四川省"><i></i>四川</label></div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <h3 class="tit_img"><img src="/static/imgs/icon/ic2.png" />加快活动进度</h3>
            <div class="fase_task_box step4_white">
                <div class="first_box" style="padding-left:0">
                    <h5>1、优先审单：<span>选择此服务后，<?php echo PROJECT_NAME; ?>将会优先审核您发布的活动</span></h5>
                    <div><label><input type="checkbox" <?= $has_first_check ? 'checked' : ''; ?> value="5" name="first" />订单优先审核（<span class="red">5</span>金币）</label></div>
                </div>
                <div class="jiashang_box">
                    <h5>2、加赏活动佣金：<span>增加金币数越多，买手完成活动的积极性越大，买手会优先做此类活动</span></h5>
                    <div>
                        <label><input type="checkbox" value="<?= $task_num*$add_reward_val; ?>" name="jiashang" <?= $has_add_reward ? 'checked' : ''; ?> />每单加赏佣金</label>
                        <input type="number" value="<?= $add_reward_val ?>" name="jiashang_text" class="jiashang_text" min="0.2" maxleng="3" step="0.1" />金币
                        <i>（最低为0.2金币）</i><b>&nbsp;&nbsp;&nbsp;共计：<?php echo $task_num; ?>单&nbsp;x&nbsp;<em><?= $add_reward_val ?></em>金币&nbsp;=&nbsp;<span><?php echo $task_num * $add_reward_val; ?></span>金币</b>
                    </div>
                </div>
                <div class="jiashang_box j-first-exec">
                    <h5>3、优先执行：<span>当商家创建活动较多时，将会优先执行您的活动</span></h5>
                    <div>
                        <label><input type="checkbox" value="10" <?= $has_first_exec ? 'checked' : ''; ?> name="first_exec" /><em>优先执行</em>（<span class="red">10</span>金币）</label>
                        <label><input type="checkbox" value="3" <?= $has_disposable ? 'checked' : ''; ?> name="disposable" /><em>一次性投放</em>（<span class="red">3</span>金币）</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="next_box">
    <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
    <a href="javascript:;" class="next_step">下一步</a>
</div>
<div class="service_total">
    <h3>费用合计</h3>
    <table class="table table-bordered" style="margin-bottom:0">
        <thead>
        <tr><th width="15%">分类</th><th colspan="4">费用明细</th><th width="20%">合计</th></tr>
        </thead>
        <tbody>
        <?php $index = 0; $total_amount = 0; ?>
        <?php foreach ($traffic_list as $key => $item): ?>
        <tr data-type="<?= $key ?>">
            <?php if (0 == $index): ?><td rowspan="8"><b>服务费</b></td><?php endif; ?>
            <td><?= $item['title'] ?></td><td><?= $item['days'] ?>天</td><td><?= $item['cnts'] ?>粉丝</td><td><?= $item['price'] ?>金币</td><td><?= $item['amount'] ?>金币</td>
        </tr>
        <?php $index++; $total_amount += floatval($item['amount']); ?>
        <?php endforeach; ?>
        <tr style="background-color:#eee"><td colspan="5" style="text-align:right;padding-right:6%">费用合计：<span><?= number_format(round($total_amount, 1), 1) ?></span>金币</td></tr>
        </tbody>
        <tfoot data-amount="<?= $total_amount ?>">
        <tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">--</td><td colspan="2">--</td><td>--</td></tr>
        <tr style="background-color:#eee"><td colspan="5" style="text-align:right;padding-right:6%">费用合计：<span>0</span>金币</td></tr>
        </tfoot>
    </table>
    <div class="service_sum">费用合计&nbsp;&nbsp;服务费：<span class="service_fd_zong">0</span>金币</div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/toast/toastr.min.js"></script>
<script src="/static/My97DatePicker/WdatePicker.js"></script>
<script src="/static/js/exif.js"></script>
<script src="/static/js/lrz.js"></script>
<script src="/static/js/task_img_upload.js"></script>
<script type="text/javascript">
    // 隐藏显示函数
    $(function(){
        var _trade_id = '<?= $trade_info->id; ?>';
        var _task_num = parseFloat('<?php echo $task_num; ?>');
        // 加赏佣金输入操作
        $(".jiashang_text").on("change", function () {
            var $this = $(this), _jiashang = parseFloat($this.val()), _parent_div = $this.parent();
            var _totle = Math.round(parseFloat(_jiashang * _task_num) * 10) / 10;
            _parent_div.find('input[type=checkbox]').val(_totle).prop('checked', true);
            _parent_div.find('em').text(_jiashang);
            _parent_div.find("span").text(_totle);
            service();
        });
        // 提升活动速度选择
        $(".fase_task_box div label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
            }
            service();
        });
        $('.j-custom-type').find('input[type="checkbox"]').on('click', function () {
            service();
        });
        // 地域限制（最多只能选择3个地区限制）
        $('.city_box .checkbox>input').change(function () {
            if ($('.city_box .checkbox>input:checked').length > 3){
                $(this).removeAttr("checked");
                toastr.warning("最多只能选择3个地区限制");
            }
        });
        // 访客入店时间分布
        $('.j-dist-box').find('input[type="checkbox"]').on('click', function () {
            var $this = $(this), _type = $this.data('type');
            if ($this.is(':checked')) {
                $this.parent().siblings().find('input[type="checkbox"]').prop('checked', false);
                if ('custom' == _type){
                    $this.parent().parent().find('.time_dist').show();
                } else {
                    $this.parent().parent().find('.time_dist').hide();
                }
            } else {
                $this.parent().parent().find('.time_dist').hide();
            }
            service();
        });
        $('.j-dist-box').find('input[type="text"]').on('change', function () {
            var $this = $(this);
            var reg = /^(0|[1-9][0-9]*)$/;
            if (!reg.test($this.val())) {
                $this.css('border-color', 'red');
                toastr.warning('请填写对应的整数百分比值');
                return false;
            } else {
                $this.css('border-color', '#bddffd');
            }
            var _rate_amount = 0;
            $this.parent().parent().find('input[type="text"]').each(function () {
                if ('' != $(this).val()) {
                    _rate_amount += parseInt($(this).val());
                }
            });
            if (_rate_amount > 100) {
                toastr.warning('设置的每个时间段百分占比合计大于100%了，请确认');
                return false;
            }
        });

        // 点击下一步
        $("body").on("click", ".next_step", function () {
            var _dist_box = $('.j-dist-box').find('input[type="checkbox"]:checked');
            var _dist_type = '', _dist_time_list = [];
            if (_dist_box.length > 0) {
                _dist_type = _dist_box.data('type');
                if ('custom' == _dist_box.data('type')){
                    var _total_rate = 0;
                    $('.time_dist').find('input[type="text"]').each(function (idx, obj) {
                        _total_rate += parseInt($(obj).val());
                        _dist_time_list.push(parseInt($(obj).val()));
                    });
                    if (_total_rate != 100){
                        toastr.warning('设置的每个时间段百分占比合计不等于100%了，请确认');
                        return false;
                    }
                }
            }
            // 性别限制选择
            if ($('input[name="choiceSex"]').is(":checked") && $('.choiceSex').find('input[type="radio"]:checked').length <= 0){
                toastr.warning("请勾选对应的性别限制");
                return false;
            }
            // 限制地区选择
            if ($('input[name="area"]').is(":checked") && $('.city_box .checkbox>input:checked').length > 3) {
                toastr.warning("地域限制最多选择三个地区");
                return false;
            }
            var add_reward_point = $('input[name="jiashang_text"]').val();
            if ($('input[name="jiashang"]').is(":checked")){
                if (isNaN(parseFloat(add_reward_point)) || parseFloat(add_reward_point) <= 0){
                    toastr.warning("每单加赏佣金的应大于0");
                    return false;
                }
            }
            // 数据提交
            var add_reward = $('input[name="jiashang"]:checked').val();
            var first_check = $('input[name="first"]:checked').val();
            var area_limit = $('input[name="area"]').is(":checked") ? 1 : 0, area_limit_city = [];
            if (area_limit == 1) {
                $('.city_box .checkbox>input:checked').each(function () {
                    area_limit_city.push($(this).val());
                });
            }
            var sex_limit = $('input[name="choiceSex"]').is(":checked") ? 1 : 0, sex_limit_val = 0;
            if (sex_limit == 1) {
                sex_limit_val = $('.choiceSex').find('input[type="radio"]:checked').val();
            }
            // 优先执行
            var _first_exec = $('input[name="first_exec"]').is(':checked');
            var _disposable = $('input[name="disposable"]').is(':checked');
            // Data Submit
            $.ajax({
                type: "POST",
                url: "/trade/traffic_step4_submit/" + _trade_id,
                data: {
                    dist_type: _dist_type,
                    dist_time_list: _dist_time_list,
                    area_limit: area_limit,
                    area_limit_city: area_limit_city,
                    sex_limit: sex_limit,
                    sex_limit_val: sex_limit_val,
                    first_check: first_check,
                    add_reward: add_reward,
                    add_reward_point: add_reward_point,
                    first_exec: _first_exec ? 1 : 0,
                    disposable: _disposable ? 1 : 0
                },
                datatype: "json",
                success: function (data) {
                    var _data = eval("(" + data + ")");
                    if (_data.error == 0) {
                        location.href = "/trade/step/" + _trade_id;
                    } else {
                        toastr.error(_data.message);
                        return false;
                    }
                }
            });
        });

    });

    service();
    //统计增值服务方法
    function service() {
        var _service_html = '', _increment_amount = 0;
        // 访客入店时间分布
        var _dist_shop = $('.j-dist-box').find('input[type="checkbox"]:checked');
        if (_dist_shop.length > 0){
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">访客入店时间</td><td colspan="2">'+ _dist_shop.parent().find('em').text() +'</td><td>'+ _dist_shop.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">访客入店时间</td><td colspan="2">'+ _dist_shop.parent().find('em').text() +'</td><td>'+ _dist_shop.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_dist_shop.val());
        }
        // 性别选择
        var _sex_choice = $('input[name="choiceSex"]');
        if (_sex_choice.is(':checked')){
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">指定访客类型</td><td colspan="2">性别设置</td><td>'+ _sex_choice.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">指定访客类型</td><td colspan="2">性别设置</td><td>'+ _sex_choice.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_sex_choice.val());
        }
        // 地域限制
        var _area_choice = $('input[name="area"]');
        if (_area_choice.is(':checked')) {
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">指定访客类型</td><td colspan="2">地域限制</td><td>'+ _area_choice.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">指定访客类型</td><td colspan="2">地域限制</td><td>'+ _area_choice.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_area_choice.val());
        }
        // 订单优先审核
        var _first_check = $('input[name="first"]');
        if (_first_check.is(':checked')) {
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">加快活动进度</td><td colspan="2">优先审核</td><td>'+ _first_check.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">加快活动进度</td><td colspan="2">优先审核</td><td>'+ _first_check.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_first_check.val());
        }
        // 每单加赏佣金
        var _jiashang = $('input[name="jiashang"]');
        if (_jiashang.is(':checked')) {
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">加快活动进度</td><td colspan="2">加赏活动佣金</td><td>'+ _jiashang.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">加快活动进度</td><td colspan="2">加赏活动佣金</td><td>'+ _jiashang.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_jiashang.val());
        }
        //
        var _first_exec = $('.j-first-exec').find('input[type="checkbox"]:checked');
        if (_first_exec.length > 0){
            if (_service_html == ''){
                _service_html = '<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">加快活动进度</td><td colspan="2">'+ _first_exec.parent().find('em').text() +'</td><td>'+ _first_exec.val() +' 金币</td></tr>';
            } else {
                _service_html += '<tr><td colspan="2">加快活动进度</td><td colspan="2">'+ _first_exec.parent().find('em').text() +'</td><td>'+ _first_exec.val() +' 金币</td></tr>';
            }
            _increment_amount += parseFloat(_first_exec.val());
        }
        // 追加子元素
        var _table_foot = $('.service_total').find('table>tfoot');
        var _base_amount = parseFloat(_table_foot.data('amount'));
        if (_service_html.length > 0) {
            var _tr_list = _table_foot.find('tr');
            for (var i = 0; i < _tr_list.length - 1; i++) {
                _tr_list[i].remove();
            }
            _table_foot.prepend(_service_html);
            _table_foot.find('tr:last').find('td span').text(Math.round((_increment_amount) * 10) / 10);
        } else {
            _table_foot.html('<tr><td rowspan="100"><b>增值服务</b></td><td colspan="2">--</td><td colspan="2">--</td><td>--</td></tr><tr style="background-color:#eee"><td colspan="5" style="text-align:right;padding-right:6%">费用合计：<span>0</span>金币</td></tr>');
        }
        // 费用合计
        $('.service_sum').find('.service_fd_zong').text(Math.round((_increment_amount + _base_amount) * 10) / 10)
    }

    function toggle(obj) {
        if ($(obj).is(':checked')) {
            $(obj).parent('label').next('div').show();
        } else {
            $(obj).parent('label').next('div').hide();
        }
    }
</script>
</body>
</html>