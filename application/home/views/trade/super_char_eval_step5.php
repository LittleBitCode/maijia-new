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
<?php $task_num = $trade_info->total_num; ?>
<div class="com_title">商家报名活动</div>
<!-- 发活动顶部活动步骤进度start -->
<div class="trade_top">
    <div class="Process">
        <ul class="clearfix">
            <li style="width:17%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:17%" class="cur"><em class="Processyes">2</em><span>添加预先浏览的商品</span></li>
            <li style="width:16%" class="cur"><em class="Processyes">3</em><span>填写下单商品信息</span></li>
            <li style="width:16%" class="cur"><em class="Processyes">4</em><span>选择活动数量</span></li>
            <li style="width:16%"><em class="Processyes">5</em><span>选增值服务</span></li>
            <li style="width:17%"><em>6</em><span>支付</span></li>
            <li style="width:17%" class="Processlast"><em>7</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <!-- 发活动顶部活动步骤进度start -->
    <div class="step4_box">
        <h1>5.选择增值服务<p>已选择：<span><?= $trade_select['plat_name']. '&nbsp;|&nbsp;'. $trade_select['shop_name']. '&nbsp;|&nbsp;'. $trade_select['type_name']. '&nbsp;|&nbsp;'. $trade_select['total_num']; ?>单</p></h1>
        <div class="service_box">
            <h3 class="tit_img"><img src="/static/imgs/icon/ic1.png" />快速返款给买手</h3>
            <div class="step4_white">
                <div class="fase_box">
                    <label>
                        <p> <input type="checkbox" value="13" data-discount="<?= isset($discount['plat_refund']) ? $discount['plat_refund'] : 100; ?>" val="<?= sprintf("%.2f",$trade_info->price*$trade_info->buy_num*$trade_info->total_num*$plat_refund_percent); ?>" name="fase" <?php if ($has_plat_refund): ?>checked<?php endif; ?> <?php if ($plat_refund_disabled): ?>disabled<?php endif; ?> /> 押金直接返款：</p>
                        <span>押金直接返款 商家押商品本金到平台，只需在个人中心“需操作退款活动”中确认返款金额，一键返款给用户(48小时内)，商家无需耗费时间、人力处理退款</span>
                    </label>
                    <p class="padd_lf">每单收取活动金额的<?= $plat_refund_percent*100; ?>%做为退款服务费，费用：<?php echo $trade_info->total_num; ?>单&nbsp;x&nbsp;<?php echo $trade_info->price*$trade_info->buy_num; ?>元&nbsp;x&nbsp;<?= $plat_refund_percent*100; ?>%&nbsp;=&nbsp;<span><?= sprintf("%.2f",$trade_info->price*$trade_info->buy_num*$trade_info->total_num*$plat_refund_percent); ?></span>金币</p>
                    <?php if (!$plat_refund_disabled): ?>
                        <label><input type="checkbox" value="14" data-discount="<?= isset($discount['bus_refund']) ? $discount['bus_refund'] : 100; ?>" val="<?php echo sprintf("%.2f",$trade_info->price*$trade_info->buy_num*$trade_info->total_num*BUS_REFUND_PERCENT); ?>" name="fase2" <?php if ($has_bus_refund): ?>checked<?php endif; ?> /><b>商家返款：</b><span>选择此项服务，买手完成后，淘宝/天猫/阿里巴巴活动需要您使用财付通返款，其他平台的活动需要您通过支付宝返款。</span></label>
                        <p>每单收取活动金额的<?php echo BUS_REFUND_PERCENT*100; ?>%做为退款服务费，费用：<?php echo $trade_info->total_num; ?>单&nbsp;x&nbsp;<?php echo $trade_info->price*$trade_info->buy_num; ?>元&nbsp;x&nbsp;<?php echo BUS_REFUND_PERCENT*100; ?>%&nbsp;=&nbsp;<span><?php echo sprintf("%.2f",$trade_info->price*$trade_info->buy_num*$trade_info->total_num*BUS_REFUND_PERCENT); ?></span>金币</p>

                        <div>注：因财付通，支付宝每日均有转账额度限制，强烈推荐使用此服务，选择平台返款，可以杜绝买家在店铺恶意退款的行为发生。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果没有勾选此服务，买手完成活动后，商家需要自己单独到财付通备用一份资金来给买手转账返款本金，请先确保财付通转账额度没有受限。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;活动金额小于等于800元的活动，此单服务为必选【<a href="javascript:;" target="_blank">点击查看详情</a>】</div>
                    <?php endif; ?>
                </div>
            </div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic2.png" />加快活动进度</h3>
            <div class="fase_task_box step4_white">
                <h5>1、提升完成活动速度：<span>增加金币数越多，推荐活动排名越靠前，便于买手更快速完成活动</span></h5>
                <div>
                    <label><input type="checkbox" value="10" name="fase_task" <?php if ($add_speed_val == '10'): ?>checked<?php endif; ?> />+10金币</label>
                    <label><input type="checkbox" value="20" name="fase_task" <?php if ($add_speed_val == '20'): ?>checked<?php endif; ?> />+20金币</label>
                    <label><input type="checkbox" value="30" name="fase_task" <?php if ($add_speed_val == '30'): ?>checked<?php endif; ?> />+30金币</label>
                </div>
                <div class="jiashang_box">
                    <h5>2、加赏活动佣金：<span>增加金币数越多，买手完成活动的积极性越大，买手会优先做此类活动</span></h5>
                    <div>
                        <label><input type="checkbox" value="<?php echo $task_num*$add_reward_val; ?>" name="jiashang" <?php if ($has_add_reward): ?>checked<?php endif; ?> />每单加赏佣金</label>
                        <input type="number" value="<?php echo $add_reward_val; ?>" name="jiashang_text" class="jiashang_text" min="2" onKeyUp="javascript:this.value=this.value.replace(/[^\d]/g,'');" onblur="if(this.value<2){this.value=2;}" onafterpaste="this.value=this.value.replace(/\D/g,'')" maxleng="3" />金币
                        <i>（最低为2金币）</i><b>&nbsp;&nbsp;&nbsp;共计：<?php echo $task_num; ?>单&nbsp;x&nbsp;<em>3</em>金币&nbsp;=&nbsp;<span><?php echo $task_num*3; ?></span>金币</b>
                    </div>
                </div>
                <div class="first_box">
                    <h5>3、优先审单：<span>选择此服务后，<?php echo PROJECT_NAME; ?>将会优先审核您发布的活动</span></h5>
                    <div>
                        <label><input type="checkbox" value="5" data-discount="<?= isset($discount['first_check']) ? $discount['first_check'] : 100; ?>" name="first" <?php if ($has_first_check): ?>checked<?php endif; ?> />订单优先审核（<span class="red">5</span>金币）</label>
                    </div>
                </div>
                <div class="q_people <?= ($trade_info->plat_id == '1' || $trade_info->plat_id == '2') ? '' : 'hidden'; ?>">
                    <h5>4、千人千面设置：</h5>
                    <div class="limitBox">
                        <label><input onclick="toggle(this)" type="checkbox" data-discount="<?= isset($discount['area_limit']) ? $discount['area_limit'] : 100; ?>" value="<?= AREA_LIMIT * $task_num ?>" name="area" <?= $area_limit ? 'checked':''; ?> />地域限制<span class="text-grey">（最多只能选择<span class="red">5</span>个地区限制，+<?= AREA_LIMIT ?>金币/单）</span></label>
                        <div class="city_box grey_bgg toggle" style="display: <?= $area_limit ? '' : 'none'; ?>">
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
                    <div class="limitBox">
                        <label><input onclick="toggle(this)" type="checkbox" data-discount="<?= isset($discount['sex_limit']) ? $discount['sex_limit'] : 100; ?>" value="<?= SEX_LIMIT * $task_num ?>" <?= $sex_limit ? 'checked' : ''; ?> name="choiceSex"><i></i>性别选择<span class="text-grey" style="font-size:14px;">（仅限选择性别用户可接该活动，+<?= SEX_LIMIT ?>金币/单）</span></label>
                        <div class="grey_bgg choiceSex" style="display: <?= $sex_limit ? '':'none'; ?>;">
                            <label class="radio"><input type="radio" name="sex" <?= ($sex_limit_val == '1') ? 'checked':''; ?> value="1"><i></i> 男</label>
                            <label class="radio"><input type="radio" name="sex" <?= ($sex_limit_val == '2') ? 'checked':''; ?> value="2"><i></i> 女</label>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div class="limitBox">
                        <label><input onclick="toggle(this)" type="checkbox" data-discount="<?= isset($discount['reputation_limit']) ? $discount['reputation_limit'] : 100; ?>" value="<?= REPUTATION_LIMIT * $task_num ?>" <?= $reputation_limit ? 'checked' : ''; ?> name="damon" >信誉限制</label>
                        <div class="grey_bgg choice_reputation">
                            <label class="radio"><input type="radio" name="reputation" <?= ($reputation_limit == '1') ? 'checked':''; ?> value="1"><i></i> 两心以上无上限<span class="text-grey" style="font-size:14px;">（0金币/单）</span></label>
                            <label class="radio"><input type="radio" name="reputation" <?= ($reputation_limit == '2') ? 'checked':''; ?> value="2"><i></i> 钻号以上<span class="text-grey" style="font-size:14px;">（+<?= REPUTATION_LIMIT ?>金币/单）</span></label>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div class="limitBox">
                        <label><input onclick="toggle(this)" type="checkbox" data-discount="<?= isset($discount['taoqi_limit']) ? $discount['taoqi_limit'] : 100; ?>" value="<?= TAOQI_LIMIT * $task_num ?>" <?= $taoqi_limit ? 'checked' : ''; ?> name="taoqi" >淘气值限制<span class="text-grey" style="font-size:14px;">（仅限选择用户可接该活动）</span></label>
                        <div class="grey_bgg choice_taoqi">
                            <label class="radio"><input type="radio" name="staoqi" <?= ($taoqi_limit == '1') ? 'checked':''; ?> value="1"><i></i> 500以上无上限<span class="text-grey" style="font-size:14px;">（0金币/单）</span></label>
                            <label class="radio"><input type="radio" name="staoqi" <?= ($taoqi_limit == '2') ? 'checked':''; ?> value="2"><i></i> 1000以上无上限<span class="text-grey" style="font-size:14px;">（+<?= TAOQI_LIMIT ?>金币/单）</span></label>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic7.png" />快递选项</h3>
            <div class="time_task_box step4_white">
                <div class="shipping_box">
                    <h5>1、选择快递</span></h5>
                    <div>
                        <?php foreach ($shipping_type_list as $key => $item): if($item['is_show'] == '0') continue; ?>
                            <label><input type="checkbox"  data-discount="<?= isset($discount['set_shipping']) ? $discount['set_shipping'] : 100; ?>" value="<?= $key ?>" name="shipping" data-nums="<?= $trade_select['total_num']; ?>" data-price="<?= $item['price'] ?>" data-totalprice="<?= $item['price']*$task_num ?>" <?= ($set_shipping==$key) ? 'checked':''; ?> /><?= $item['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                    <p><span class="red">温馨提示：</span>赠送小礼品的重量尽量和真实快递产品的重量保持一致，切勿自发空包（淘宝会核查快递中转站重量）</p>
                </div>
                <div class="weight_box">
                    <h5>2、自定义包裹重量：<span>平台会根据您设置的包裹重量进行发货，提高安全性</span></h5>
                    <div>
                        <label><input type="checkbox" value="0" name="weight" <?= ($set_shipping=='self') ? '' : 'checked'; ?> disabled />设置每个订单包裹重量</label>
                        <input type="input" value="<?php echo $set_weight_val; ?>" onKeyUp="javascript:this.value=this.value.replace(/[^\d.]/g,'');if(this.value.indexOf('.')>=0){if(this.value.split('.')[1].length>2){this.value=parseFloat(this.value).toFixed(2);};};" name="weight_text" <?= ($set_shipping=='self') ? 'disabled' : ''; ?> />kg（<span><?= $shipping_type_list[$set_shipping]['price'] ?></span>金币/单）<i>请根据您活动商品的实际重量来设定，最大不超过40kg，可设置小数点后两位</i>
                    </div>
                </div>
            </div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic3.png" />定时发布</h3>
            <div class="time_task_box step4_white">
                <label>
                    <h5><input style="margin-right: 5px;" type="checkbox" data-discount="<?= isset($discount['set_time']) ? $discount['set_time'] : 100; ?>" value="3" name="time_task" <?php if ($has_set_time): ?>checked<?php endif; ?> />定时发布：<span>选择此服务后，<?php echo PROJECT_NAME; ?>将会按照您设置的时间来报名活动</span></h5>
                </label>
                <div>
                    <label>报名活动时间</label>
                    <input type="text" name="set_time_val" onclick="WdatePicker({minDate:'%y-%M-#{%d} #{%H+2}',dateFmt:'yyyy-MM-dd HH:mm:00'})" value="<?php echo $set_time_val; ?>" style="padding-left: 4px;" />（3金币）<span>发布时间、与当前时间至少错开两个小时</span><br>
                </div>
                <p><span class="red">温馨提示：</span>客服审核时间为9:00 - 22:00，请在本时间段内报名活动，否则无法按照您设置的时间定时发布</p>
                <label>
                    <h5><input style="margin-right: 5px;" type="checkbox" data-discount="<?= isset($discount['set_over_time']) ? $discount['set_over_time'] : 100; ?>" value="2" name="time_over_task" <?php if ($has_set_over_time): ?>checked<?php endif; ?> />定时结束：<span>选择此服务后，<?php echo PROJECT_NAME; ?>将会按照您设置的时间来结束活动</span></h5>
                </label>
                <div style="margin-bottom:16px;">
                    <label>活动结束时间</label>
                    <input type="text" name="set_over_time_val" onclick="WdatePicker({minDate:'%y-%M-#{%d} #{%H+2}',dateFmt:'yyyy-MM-dd HH:mm:00'})" value="<?php echo $set_over_time_val; ?>" style="padding-left: 4px;" />（2金币）<span>结束时间、与活动发布时间至少错开一个小时</span><br>
                </div>
                <div class="releaseTime">
                    <label style="padding-left:0"><h5><input style="margin-right: 5px;" type="checkbox" name="releasetime" data-discount="<?= isset($discount['custom_time_price']) ? $discount['custom_time_price'] : 100; ?>" <?php if($custom_time_price): ?>checked<?php endif; ?> value="<?= CUSTOM_TIME_PRICE ?>">分时发布：<small style="font-size:14px;color:#909090">选择此服务平台将收取 <span style="color:#ff4800;"><?= CUSTOM_TIME_PRICE ?></span>个金币</small></h5></label>
                    <span style="margin-left:64px;color:#999999;">总计<span><?= $task_num ?></span>单，总活动单数与下面时间点的单数数量相加值一致</span>
                    <?php $hours_list = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]; ?>
                    <div><label>选择发布日期</label><input type="text" name="set_time_pre_val" onclick="WdatePicker({minDate:'%y-%M-#{%d} #{%H+2}',dateFmt:'yyyy-MM-dd', onpicked: fun_custom_time_pre_set})" value="<?= $set_time_pre_val ?>" style="padding-left:8px;margin-top:8px;" /></div>
                    <div class="releaseTimelists">
                        <?php $compare_hour = (strtotime($set_time_pre_val) > time()) ? -1 : date('H'); ?>
                        <?php foreach ($hours_list as $item): ?>
                            <div>
                                <label><span><?= $item ?>时</span></label>
                                <input type="number" min="1" max="<?= $task_num ?>" value="<?= ($compare_hour < $item && isset($custom_time_price[$item])) ? $custom_time_price[$item] : ''; ?>" <?php if($compare_hour >= $item): ?>disabled<?php endif; ?> data-idx="<?= $item ?>" />单
                            </div>
                        <?php endforeach; ?>
                        <div class="the_rest">已分配<span class="choiced">0</span>单，未分配<span class="rest">0</span>单</div>
                    </div>
                    <div class="clearfix"></div>
                    <div style="margin:16px 0;"><span style="margin-left:20px;">温馨提示：设置时间单数后请及时付款，否则会导致过期时间段的任务与当前时间单一起发布出去哦~</span></div>
                </div>
                <label><h5><input type="checkbox" name="time_interval" data-discount="<?= isset($discount['set_interval']) ? $discount['set_interval'] : 100; ?>" value="6" <?php if ($has_set_interval): ?>checked<?php endif; ?> <?php if ($set_interval_disabled): ?>disabled<?php endif; ?> style="margin-right: 5px;" />间隔发布：<i>选择此项服务后，<?php echo PROJECT_NAME; ?>会将您的活动分批发布，以避免订单过于集中：</i><span class="red f14">为了安全性建议选择此项服务</span></h5></label>
                <div style="<?php if ($set_interval_disabled): ?>color: #8D8D8D;<?php endif; ?>">
                    <label style="margin-right:8px;">每隔</label>
                    <select name="set_interval_val" style="width: 108px;" class="blue_input">
                        <?php foreach ($interval_list as $k=>$v): ?>
                            <option value="<?php echo $k; ?>" <?php if ($k == $set_interval_val): ?>selected<?php endif; ?>><?php echo $v['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    发布
                    <select name="interval_num" class="blue_input" style="width: 84px;">
                        <?php foreach ($interval_nums as $v): ?>
                            <option value="<?php echo $v; ?>" <?php if ($v == $interval_num_val): ?>selected<?php endif; ?>><?php echo $v; ?>单</option>
                        <?php endforeach; ?>
                    </select>
                    活动（<span>6</span>金币）
                </div>
            </div>
            <div class="clearfix"></div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic4.png">安全优化</h3>
            <div class="time_interval_box step4_white">
                <div class="shopping_cycle_box">
                    <h5>1、延长买家购物周期：<span>仅推荐重复购买率低的商品使用，如家居，家电，高单价的商品等；其他品类不推荐使用</span></h5>
                    <div>
                        <label><input type="checkbox" value="1" name="shopping_cycle" data-discount="<?= isset($discount['extend_cycle']) ? $discount['extend_cycle'] : 100; ?>" val="0" <?php if ($trade_info->extend_cycle == '0'): ?>checked<?php endif; ?> />1个月&nbsp;<small>（<span>0</span>金币/单）</small></label>
                        <label><input type="checkbox" value="2" name="shopping_cycle" data-discount="<?= isset($discount['extend_cycle']) ? $discount['extend_cycle'] : 100; ?>" val="<?php echo $task_num; ?>" <?php if ($trade_info->extend_cycle == '2'): ?>checked<?php endif; ?> />2个月&nbsp;<small>（<span>1</span>金币/单）</small></label>
                        <label><input type="checkbox" value="3" name="shopping_cycle" data-discount="<?= isset($discount['extend_cycle']) ? $discount['extend_cycle'] : 100; ?>" val="<?php echo $task_num*1.5; ?>" <?php if ($trade_info->extend_cycle == '3'): ?>checked<?php endif; ?> />3个月&nbsp;<small>（<span>1.5</span>金币/单）</small></label>
                    </div>
                    <p class="shopping_cycle_p1"><span class="red">温馨提示：</span>选择此项服务后，购买过活动商品的买家<span>2个月</span>内，将不能再接手包含本活动商品的活动；</p>
                    <p class="shopping_cycle_p2">合计收费：<span class="shopping_cycle_dj">1</span>金币&nbsp;X&nbsp;<span><?php echo $task_num; ?></span>单&nbsp;X&nbsp;<span>1</span>个商品链接&nbsp;=&nbsp;<span class="shopping_cycle_total">10</span>金币；</p>
                </div>
                <div class="shopping_end_box">
                    <h5>2、限制买号重复进店下单：<span>有效降低重购率</span></h5>
                    <div><label><input type="checkbox" value="<?= SHOPPING_END_BOX ?>" data-discount="<?= isset($discount['shopping_end']) ? $discount['shopping_end'] : 100; ?>" name="shopping_end" val="<?= $task_num * SHOPPING_END_BOX; ?>" />&nbsp;限制买号重复进店下单<small>（<span><?= SHOPPING_END_BOX ?></span>金币/单）</small></label></div>
                    <p class="shopping_end_p1"><span class="red">温馨提示：</span>选择此项服务后，凡是购买过该活动商品的买家，将不能再接手该商品的所在店铺的其他活动商品；</p>
                    <p class="shopping_end_p2">合计收费：<span class="shopping_end_dj"><?= SHOPPING_END_BOX ?></span>金币&nbsp;X&nbsp;<span><?php echo $task_num; ?></span>单&nbsp;=&nbsp;<span class="shopping_end_total"><?= SHOPPING_END_BOX ?></span>金币；</p>
                </div>
                <div class="newhand_box" style="display:<?= ($trade_info->plat_id == '1' || $trade_info->plat_id == '2') ? 'block' : 'none'; ?>;">
                    <h5>3、指定平台新注册买手接单：<span>增加安全性</span></h5>
                    <div>
                        <label><input type="checkbox" value="3" data-discount="<?= isset($discount['newhand']) ? $discount['newhand'] : 100; ?>" name="newhand" val="<?= $task_num * 3; ?>" <?php if($has_newhand == '3'): ?>checked<?php endif; ?>  data-txt="7天内" />&nbsp;7天内<small>（<span>3</span>金币/单）</small></label>
                        <label><input type="checkbox" value="2" data-discount="<?= isset($discount['newhand']) ? $discount['newhand'] : 100; ?>" name="newhand" val="<?= $task_num * 2; ?>" <?php if($has_newhand == '2'): ?>checked<?php endif; ?> data-txt="15天内" />&nbsp;15天内<small>（<span>2</span>金币/单）</small></label>
                        <label><input type="checkbox" value="1" data-discount="<?= isset($discount['newhand']) ? $discount['newhand'] : 100; ?>" name="newhand" val="<?= $task_num * 1; ?>" <?php if($has_newhand == '1'): ?>checked<?php endif; ?> data-txt="1个月内" />&nbsp;1个月内<small>（<span>1</span>金币/单）</small></label>
                    </div>
                    <p class="newhand_p1"><span class="red">温馨提示：</span>选择此项服务后，只能被对应在平台注册天数内的新买家接取；</p>
                    <p class="newhand_p2">合计收费：<span class="newhand_dj">3</span>金币&nbsp;X&nbsp;<span><?php echo $task_num; ?></span>单&nbsp;=&nbsp;<span class="newhand_total">3</span>金币；</p>
                </div>
            </div>
            <!-- 人气权重 -->
            <div class="service_box <?= (in_array($trade_info->trade_type, ['4', '5']) || in_array($trade_info->plat_id, ['4', '14'])) ? 'hide' : 'show'; ?>">
                <h3 class="tit_img"><img src="/static/imgs/icon/quanzhong.png">人气权重优化</h3>
                <div class="step4_white people_weight">
                    <div class="people_weight_top"><span style="color:red">额外</span>再给您安排相应的人群进行宝贝的浏览、收藏、加购等操作；<br>有利于快速提高人气权重，获取<span style="color:red">手淘首页流量！</span><br>根据我们的测算,建议每日收藏控制在销量的4倍左右,加购量控制在销量的2-3倍；访客进入评价页可反应宝贝关注度,可快速提高宝贝喜爱度权重，建议所有商家选择。</div>
                    <div class="people_weight_info" data-nums="<?= $trade_select['total_num'] ?>">
                        <?php $total_price = 0; ?>
                        <?php foreach ($traffic_arr as $key => $item): ?>
                            <?php $total_price += floatval($item['total']); ?>
                            <div>
                                <label><input type="checkbox" name="<?= $key; ?>" value="" <?= (intval($item['nums']) > 0) ? 'checked="checked"' : ''; ?> /></label>
                                <span><?= $item['title']; ?><input type="text" onkeyup="javascript:this.value=this.value.replace(/[^\d]/g,'');" value="<?= (intval($item['nums']) > 0) ? $item['nums'] : ''; ?>" data-price="<?= $item['price']; ?>" />访客/单</span>
                                <span style="float:right;">需：<em class="span-num"><?= (intval($item['nums']) > 0) ? $item['nums'] : '--'; ?></em>访单/单 x <?= $item['price']; ?>金币/访客 x <?= $trade_select['total_num']; ?>单 = <em class="color_red"><?= number_format($item['total'], 2); ?></em>金币</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="total"><span><strong>共计：</strong><em class="color_red"><?= number_format($total_price, 2); ?></em>金币</span></div>
                </div>
                <label class="hidden"><input type="checkbox" value="<?= $total_price; ?>" data-discount="100" name="traffic" /></label>
            </div>
            <h3 class="tit_img"><img src="/static/imgs/icon/ic5.png" />好评优化<span>买手会根据您的要求对商品进行评论</span></h3>
            <div class="praise_box step4_white">
                <label><input type="checkbox" name="default_eval" class="praise" <?php if ($has_default_eval): ?>checked<?php endif; ?> />默认好评：选择此服务后，接手活动买手将对商品5分默认好评（<span>0金币/单</span>）</label>
                <label><input type="checkbox" name="free_eval" class="praise" <?php if ($has_free_eval): ?>checked<?php endif; ?> />自由好评：选择此服务后，接手活动买手将按照商品类型自己评价商品（<span>0金币/单</span>）</label>
                <label><input type="checkbox" name="kwd_eval" data-discount="<?= isset($discount['kwd_eval']) ? $discount['kwd_eval'] : 100; ?>" class="praise" <?php if ($has_kwd_eval): ?>checked<?php endif; ?> value="<?php echo $task_num*1; ?>" />优质好评：选择此项服务后，将有助于提升评价质量并优化您商品评价映像关键词（<span>1金币/单</span>）</label>
                <div class="set_praise">
                    <div class="youzhi_praise">
                        <p><span class="red">温馨提示：</span>请根据您报名活动的商品设定<span>几个关键字</span>作为买手的<span>评价范围</span>独自发挥撰写评价，例如"<span>手感很舒服，款式很漂亮，包装很讲究，物流很快，性价比高</span>"等... 注意：请不要填写完整的评价内容，避免所有买手评价商品的内容一模一样</p>
                        <p class="red">每个关键字最多输入10个字</p>
                        <div class="yz_praise_box row">
                            <div class="col-xs-3">
                                <div class="yz_praise_list"><span>关键字1：</span><input type="text" maxlength="10" name="keyword[]" value="<?php echo $kwds[0]; ?>" class="form-control" /></div>
                            </div>
                            <div class="col-xs-3">
                                <div class="yz_praise_list"><span>关键字2：</span><input type="text" maxlength="10" name="keyword[]" value="<?php echo $kwds[1]; ?>" class="form-control" /></div>
                            </div>
                            <div class="col-xs-3">
                                <div class="yz_praise_list"><span>关键字3：</span><input type="text" maxlength="10" name="keyword[]" value="<?php echo $kwds[2]; ?>" class="form-control" /></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 小于等于3单展示自定义好评 -->
                <label style="<?php if ($setting_eval_disabled): ?>color: #8D8D8D;<?php endif; ?>"><input type="checkbox" name="setting_eval" data-discount="<?= isset($discount['setting_eval']) ? $discount['setting_eval'] : 100; ?>" class="praise" <?php if ($setting_eval_disabled): ?>disabled<?php endif; ?> <?php if ($has_setting_eval): ?>checked<?php endif; ?> value="<?php echo $task_num*2; ?>" />自定义好评：选择此项服务后，将有助于提升评价质量（<span>2金币/单</span>）（<span>自定义好评订单最多只能发布十单</span>）</label>
                <div class="set_praise">
                    <!-- 小于等于3单展示自定义好评 -->
                    <div class="zdy_praise">
                        <p><span class="red">温馨提示：</span>请根据您发布商品的实际情况提供评价内容<span>注意：发布多单活动时务必保证评价内容不同，避免重复</span></p>
                        <p class="red">每个评论最多输入200个字</p>
                        <div class="zdy_praise_box row">
                            <?php foreach ($eval_contents as $k=>$v): ?>
                                <div class="zdy_praise_list"><span style="display:inline-block;width:90px;"><span class="red">* </span>评价：<em><?php echo $k+1; ?></em></span><input type="text" name="comment[]" value="<?php echo $v; ?>" /><?= ($k==0) ? '':''; ?></div>
                            <?php endforeach; ?>
                        </div>
                        <!-- <div class="add_zdy_praise" <?php //if (count($eval_contents) >= 10): ?>style="display:none;"<?php //endif; ?>><a href="javascript:;">+点击添加一组评论</a></div> -->
                    </div>
                </div>
                <!-- 图文好文服务 -->
                <label style="<?php if ($trade_info->total_num > 5): ?>color: #8D8D8D;<?php endif; ?>"><input type="checkbox" name="setting_picture" data-discount="<?= isset($discount['setting_picture']) ? $discount['setting_picture'] : 100; ?>" class="praise" <?php if ($trade_info->total_num > 5 || $trade_info->trade_type == '140'): ?>disabled<?php endif; ?> <?php if ($has_setting_picture): ?>checked<?php endif; ?> value="<?php echo $task_num*4; ?>" />图文好评：选择此项服务后，将有助于提升评价质量（<span>4金币/单</span>）（<span>图文好评订单最多只能发布五单</span>）</label>
                <div class="set_praise">
                    <div class="txt_image">
                        <p>
                            <span class="red">温馨提示：</span>
                            <span style="display:block;margin-left:32px">1、每组照片拍摄的角度、背景不能一样</span>
                            <span style="display:block;margin-left:32px">2、请将你的商品根据你要的图文评价数量，拍摄不同的组数，每组可传1-5张商品图片，每张图片不可大于2Mb</span>
                            <span style="display:block;margin-left:32px">3、每个评论最多输入200个字</span>
                        </p>
                        <div class="zdy_praise_box row js-txt-image" style="margin-top:0">
                            <?php foreach ($txt_image_list as $k => $item): ?>
                                <div class="zdy_praise_list">
                                    <div class="pic_list_box">
                                        <div class="pic_list">
                                            <div class="pic_list_info">
                                                <b>第<?= $k + 1 ?>单的照片</b>设置图片商品的规格：
                                                <input type="text" name="color[]" placeholder="如：颜色" value="<?= $item['color'] ?>" <?= ($item['color'] || $item['size']) ? 'readonly' : ''; ?> class="form-control" style="width:108px;" />
                                                <input type="text" name="size[]" placeholder="如：尺码" value="<?= $item['size'] ?>" <?= ($item['color'] || $item['size']) ? 'readonly' : ''; ?> class="form-control" style="width:108px;" />
                                                <span>主要针对商品规格不同颜色,花色,款式进行设置,和好评图片保持一致</span>
                                            </div>
                                            <?php $pic_idx = 0; ?>
                                            <ul class="up_load_list">
                                                <?php if($item['img1']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img1']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img1']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img2']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img2']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img2']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img3']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img3']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img3']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img4']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img4']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img4']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img5']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img5']. '?imageView/1/w/128/h/128'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img5']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($pic_idx < 5): ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: none;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val hidden" onchange="javascript:setImagePreview(this);" uploaded="1" base64="" path="" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                            <div style="margin-bottom:12px;"><span style="display:inline-block;width:64px;"><span class="red">* </span>评价：</span><input type="text" name="comment[]" value="<?= $item['content'] ?>" size="200" /></div>
                                            <p style="padding-left: 10px;"></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- 视频评价 -->
                <label style="<?php if ($trade_info->total_num > 5): ?>color: #8D8D8D;<?php endif; ?>"><input type="checkbox" name="setting_video" data-discount="<?= isset($discount['setting_video']) ? $discount['setting_video'] : 100; ?>" class="praise" <?php if ($trade_info->total_num > 5 || $trade_info->trade_type == '140'): ?>disabled<?php endif; ?> <?php if ($has_setting_video): ?>checked<?php endif; ?> value="<?php echo $task_num*6; ?>" />视频评价：选择此项服务后，将有助于提升评价质量（<span>6金币/单</span>）（<span>视频评价订单最多只能发布五单</span>）</label>
                <div class="set_praise">
                    <div class="txt_video">
                        <p>
                            <span class="red">温馨提示：</span>
                            <span style="display:block;margin-left:32px">1、每组照片、视频拍摄的角度、背景不能一样</span>
                            <span style="display:block;margin-left:32px">2、请将你的商品根据你要的图文评价数量，拍摄不同的组数，每组可传1-5张商品图片（大小小于2Mb），及1段视频（时长小于3分钟，大小小于140Mb）。</span>
                            <span style="display:block;margin-left:32px">3、每个评论最多输入200个字</span>
                        </p>
                        <div class="zdy_praise_box row js-txt-video" style="margin-top:0">
                            <?php foreach ($video_image_list as $k => $item): ?>
                                <div class="zdy_praise_list">
                                    <div class="pic_list_box">
                                        <div class="pic_list">
                                            <div class="pic_list_info">
                                                <b>第<?= $k + 1 ?>单的照片</b>设置图片商品的规格：
                                                <input type="text" name="color[]" placeholder="如：颜色" value="<?= $item['color'] ?>" <?= ($item['color'] || $item['size']) ? 'readonly' : ''; ?> class="form-control" style="width:108px;" />
                                                <input type="text" name="size[]" placeholder="如：尺码" value="<?= $item['size'] ?>" <?= ($item['color'] || $item['size']) ? 'readonly' : ''; ?> class="form-control" style="width:108px;" />
                                                <span>主要针对商品规格不同颜色,花色,款式进行设置,和好评图片保持一致</span>
                                            </div>
                                            <!-- 上传视频 -->
                                            <div class="uploaded_img_preview j-video-box" style="display:inline-block;margin-left:32px;">
                                                <i style="display: none;" class="remove_upload_img"></i>
                                                <img class="uploaded_goods_img" src="<?= $item['video'] ? $item['video']. '?vframe/jpg/offset/0/w/128/h/128' : '/static/imgs/icon/set_video.png'; ?>" style="width:128px;height:128px;" />
                                                <input type="file" name="upload_video" accept="video/*" class="uploaded_goods_img_val hidden" onchange="javascript:setVideoPreview(this);" uploaded="1" base64="" path="<?= $item['video'] ?>" />
                                                <video style="display:none;" controls="controls" id="hid_video" oncanplaythrough="myFunction(this)"></video>
                                            </div>
                                            <?php $pic_idx = 0; ?>
                                            <ul class="up_load_list">
                                                <?php if($item['img1']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img1']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img1']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img2']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img2']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img2']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img3']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img3']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img3']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img4']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img4']. '?imageView/1/w/108/h/108'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img4']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($item['img5']): $pic_idx++; ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: block;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_imgs" src="<?= $item['img5']. '?imageView/1/w/128/h/128'; ?>" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val" onChange="javascript:setImagePreview(this);" uploaded="1" base64="" hidden="hidden" path="<?= $item['img5']; ?>" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if($pic_idx < 5): ?>
                                                    <li>
                                                        <div class="uploaded_img_preview">
                                                            <i style="display: none;" class="remove_upload_img"></i>
                                                            <img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" style="width:128px;height:128px;" />
                                                            <input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val hidden" onchange="javascript:setImagePreview(this);" uploaded="0" base64="" path="" />
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                            <div style="margin-bottom:12px;"><span style="display:inline-block;width:64px;"><span class="red">* </span>评价：</span><input type="text" name="comment[]" value="<?= $item['content'] ?>" size="200" /></div>
                                            <p style="padding-left: 10px;"></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="next_box">
    <a href="/trade/prev/<?= $trade_info->id; ?>" class="previous_step">上一步</a>
    <a href="javascript:;" class="next_step">下一步</a>
</div>
<div class="service_total">
    <h3>费用合计</h3>
    <table class="table table-bordered" style="margin-bottom:0">
        <tr><th width="15%">分类</th><th width="35%">费用明细</th><th width="15%">小计</th><th width="15%">优惠折扣</th><th width="20%">合计</th></tr>
        <tr>
            <td><b>押金</b></td>
            <td>
                <div>
                    <p>商品1：<?php echo $trade_info->price*1; ?>元 x <?php echo $trade_info->buy_num; ?></p>
                    <p>活动保证金：<?php echo $payment*1; ?>元/单</p>
                    <?php if ($trade_info->post_fee > 0): ?>
                        <p>运费保证金：<?php echo $trade_info->post_fee; ?>元/单</p>
                    <?php endif; ?>
                </div>
            </td>
            <td><p><span class="red"><?php echo $deposit_subtotal*1; ?></span>元</p></td>
            <td><p>--</p></td>
            <td><?php echo $deposit_subtotal*1; ?> x <?php echo $trade_info->total_num; ?> = <span><?php echo bcmul($deposit_subtotal, $trade_info->total_num, 2)*1; ?></span>元</td>
        </tr>
        <tr>
            <td><b>服务费</b></td>
            <td>
                <div>
                    <p>套餐服务费：<?php echo $trade_info->total_fee*1; ?>金币/单</p>
                    <?php if ($trade_info->is_phone): ?>
                        <p>手机端加成：<?php echo ORDER_DIS_PRICE; ?>金币/单</p>
                    <?php endif; ?>
                    <?php if (count($app_scans) > 0): ?>
                        <p>超级浏览任务：<?php echo SUPER_SCAN_PRICE; ?>金币/个</p>
                    <?php endif; ?>
                </div>
            </td>
            <td><p><span class="red"><?php echo $point_subtotal*1; ?></span>金币</p></td>
            <td><p>--</p></td>
            <td><p><?php echo $point_subtotal*1; ?>&nbsp;x&nbsp;<?php echo $trade_info->total_num; ?> + <?php echo SUPER_SCAN_PRICE ?> x <?php echo count($app_scans); ?> x <?php echo $trade_info->total_num ; ?>&nbsp;=&nbsp;<span class="fee_fd"><?php echo bcmul($point_subtotal, $trade_info->total_num, 2)*1 + bcmul((count($app_scans)*SUPER_SCAN_PRICE), $trade_info->total_num, 2); ?></span>金币</p></td>
        </tr>
        <tr>
            <td><b>增值服务</b></td>
            <td><div class="service_total_detail"></div></td>
            <td><p><span class="service_sub_total_fd">--</span>金币</p></td>
            <td><p class="j-discount-info">--</p></td>
            <td><p><span class="service_total_fd">--</span>金币</p></td>
        </tr>
    </table>
    <div class="service_sum">费用合计&nbsp;&nbsp;押金：<span><?php echo bcmul($deposit_subtotal, $trade_info->total_num, 2)*1; ?></span>元&nbsp;&nbsp;服务费：<span class="service_fd_zong"></span>金币</div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/toast/toastr.min.js"></script>
<script src="/static/My97DatePicker/WdatePicker.js"></script>
<script src="/static/js/task_step_4.js?v=1"></script>
<script src="/static/js/task_img_upload.js?v=2"></script>
<script type="text/javascript">
    // 隐藏显示函数
    $(function(){
        var _task_num = parseFloat('<?= $task_num; ?>');
        var _trade_id = parseInt('<?= $trade_info->id; ?>');
        //加赏佣金输入操作
        $(".jiashang_text").on("keyup blur change", function () {
            var $this = $(this), _jiashang = parseFloat($this.val()), _parent_div = $this.parent();
            var _totle = parseFloat(_jiashang * _task_num);
            _parent_div.find('input[type=checkbox]').val(_totle).prop('checked', true);
            _parent_div.find('em').text(_jiashang);
            _parent_div.find("span").text(_totle);
            service();
        });

        // 快速返款给买手选择
        $(".fase_box label").click(function (e) {
            if ($(this).find('input[name="fase"]:checked').length <= 0) {
                $(this).find('input[name="fase"]').prop('checked', true);
            } else {
                e.preventDefault();
            }
        });

        //提升活动速度选择
        $(".fase_task_box div label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
            }
        });

        // 快递配送选择
        $(".shipping_box div label").click(function () {
            var $this = $(this);
            $this.siblings("label").children("input").attr("checked", false);
            $this.children("input").prop("checked", true);
            // 关联自定义包裹重量
            var _type = $this.children("input").val(), _weight_box = $('.weight_box');
            var _shipping = $('.shipping_box').find('input[name="shipping"]:checked'), _price = parseFloat(_shipping.data('price'));
            if (_type=='self') {
                _weight_box.find('input[name="weight"]').prop('checked', false);
                _weight_box.find('input[name="weight_text"]').prop('disabled', 'disabled').val(0.00).parent().find('span').text(_price);
            }else{
                _weight_box.find('input[name="weight"]').prop('checked', true);
                _weight_box.find('input[name="weight_text"]').prop('disabled', false).val(2.00).parent().find('span').text(_price);
            }
        });

        // 延长买家购物周期
        $(".shopping_cycle_box>div>label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
                var shopping_cycle = $(this).children("input").val();
                $(".shopping_cycle_p1 span").html(shopping_cycle + '个月');
                if (shopping_cycle == 2) {
                    $(".shopping_cycle_p2").show();
                    $(".shopping_cycle_p2").children(".shopping_cycle_dj").html(1);
                    $(".shopping_cycle_p2").children(".shopping_cycle_total").html(parseFloat(1) * parseFloat(_task_num));
                } else if (shopping_cycle == 3) {
                    $(".shopping_cycle_p2").show();
                    $(".shopping_cycle_p2").children(".shopping_cycle_dj").html(1.5);
                    $(".shopping_cycle_p2").children(".shopping_cycle_total").html(parseFloat(1.5) * parseFloat(_task_num));
                }
            } else {
                $(".shopping_cycle_p2").hide();
            }
        });
        // 限制买号重复进店下单
        $(".shopping_end_box>div>label").click(function () {
            if ($(this).children("input").is(":checked")) {
                var shopping_end = $(this).children("input").val();
                $(".shopping_end_p2").show();
                $(".shopping_end_p2").children(".shopping_end_dj").html(shopping_end);
                $(".shopping_end_p2").children(".shopping_end_total").html(parseFloat(shopping_end) * parseFloat(_task_num));
            } else {
                $(".shopping_end_p2").hide();
            }
        });
        // 指定平台新注册买手接单
        $(".newhand_box>div>label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
                var _new_hand_points = $(this).children("input").val();
                $(".newhand_p2").show();
                $(".newhand_p2").children(".newhand_dj").html(_new_hand_points);
                $(".newhand_p2").children(".newhand_total").html(parseFloat(_new_hand_points) * parseFloat(_task_num));
            } else {
                $(".newhand_p2").hide();
            }
        });
        // 好评优化选择
        $(".praise_box label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
                var _name = $(this).children('input').attr('name');
                if (_name == 'kwd_eval'){
                    $(".youzhi_praise").slideDown();
                    $(".zdy_praise").slideUp();
                    $('.txt_image').slideUp();
                    $('.txt_video').slideUp();
                } else if(_name == 'setting_eval') {
                    $(".youzhi_praise").slideUp();
                    $(".zdy_praise").slideDown();
                    $('.txt_image').slideUp();
                    $('.txt_video').slideUp();
                } else if(_name == 'setting_picture') {
                    $('.txt_image').slideDown();
                    $(".youzhi_praise").slideUp();
                    $(".zdy_praise").slideUp();
                    $('.txt_video').slideUp();
                } else if(_name == 'setting_video') {
                    $('.txt_video').slideDown();
                    $(".youzhi_praise").slideUp();
                    $(".zdy_praise").slideUp();
                    $('.txt_image').slideUp();
                } else {
                    $(".youzhi_praise").slideUp();
                    $(".zdy_praise").slideUp();
                    $('.txt_image').slideUp();
                    $('.txt_video').slideUp();
                }
            } else {
                $(".youzhi_praise").slideUp();
                $(".zdy_praise").slideUp();
                $('.txt_image').slideUp();
                $('.txt_video').slideUp();
            }
        });
        var _chk = $(".praise_box label").find('input[type="checkbox"]:checked');
        if (_chk.length == 1){
            var _obj = _chk[0];
            $(_obj).parents(".praise_box label").trigger('click');
            $(_obj).prop('checked', true);
        }

        // 输入评论内容
        $(".zdy_praise_list input").on("change", function () {
            if ($(this).val().length == 0 || $(this).val().length > 200) {
                toastr.warning("评论内容不能为空且不能超过200个字");
                return false;
            }
        });

        // 地域限制（最多只能选择3个地区限制）
        $('.city_box .checkbox>input').change(function () {
            if ($('.city_box .checkbox>input:checked').length > 5){
                $(this).removeAttr("checked");
                toastr.warning("最多只能选择5个地区限制");
            }
        });

        /************   手机端活动执行此模块start  ***********/
        // 自定义时间选择
        $(".releaseTimelists div>input").on("change", function () {
            var _total_sum = 0;
            $(".releaseTimelists div>input").each(function (i, obj) {
                var _nums = $(obj).val();
                if (_nums != '' && parseInt(_nums) > 0){
                    _total_sum += parseInt($(obj).val());
                }
            });
            if (_total_sum > _task_num) {
                toastr.warning("时间点单数相加超过总单数，请确认");
                $(this).focus();
            } else {
                $('.the_rest .choiced').text(_total_sum);
                $('.the_rest .rest').text(_task_num - _total_sum);
            }
            if($('input[name="releasetime"]').is(":checked") == false){
                $('input[name="releasetime"]').trigger('click');
            }
        });
        // 分时发布、与定时发布、与间隔发布相排斥
        $('input[name="releasetime"]').click(function (e) {
            var _time_interval_obj = $('input[name="time_interval"]');
            var _time_task_obj = $('input[name="time_task"]');
            if ($(this).is(":checked")) {
                if (_time_interval_obj.is(":checked")){
                    _time_interval_obj.trigger('click');
                }
                if (_time_task_obj.is(":checked")){
                    _time_task_obj.trigger('click');
                }
                _time_interval_obj.attr('disabled', true);
                _time_task_obj.attr('disabled', true);
            } else{
                _time_interval_obj.removeAttr('disabled');
                _time_task_obj.removeAttr('disabled');
            }
        });
        // 间隔发布、与分时发布相排斥
        $('input[name="time_interval"]').click(function (e) {
            var _releasetime_obj = $('input[name="releasetime"]');
            if($(this).is(":checked")) {
                if (_releasetime_obj.is(":checked")){
                    _releasetime_obj.trigger('click');
                }
                _releasetime_obj.attr('disabled', true);
            } else{
                _releasetime_obj.removeAttr('disabled');
            }
        });
        // 定时发布、与分时发布相斥
        $('input[name="time_task"]').click(function (e) {
            var _releasetime_obj = $('input[name="releasetime"]');
            if($(this).is(":checked")) {
                if (_releasetime_obj.is(":checked")){
                    _releasetime_obj.trigger('click');
                }
                _releasetime_obj.attr('disabled', true);
            } else{
                _releasetime_obj.removeAttr('disabled');
            }
        });
        // 定时发布 取消时，关联分时发布
        // $('input[name="time_task"]').click(function (e) {
        //     if ($(this).is(":checked") == false && $('input[name="releasetime"]').is(":checked")){
        //         // 查询勾选的时间，有没有超时的
        //         var _date_time_obj = new Date();
        //         var _hour = _date_time_obj.getHours();
        //         $(".releaseTimelists div>input").each(function (i, obj) {
        //             var $this = $(obj), _idx = $this.data('idx');
        //             var _remain_nums = 0 ;
        //             $this.removeAttr('disabled');
        //             if (_idx <= _hour){
        //                 var _nums = parseInt($this.val());
        //                 if (!isNaN(_nums)){
        //                     _remain_nums += _nums;
        //                 }
        //                 $this.val('');
        //                 $this.attr('disabled', '');
        //             }
        //             if (_remain_nums > 0){
        //                 $('.the_rest .choiced').text(parseInt($('.the_rest .choiced').text()) - _remain_nums);
        //                 $('.the_rest .rest').text(parseInt($('.the_rest .rest').text()) + _remain_nums);
        //             }
        //         });
        //     }
        // });
        /************   手机端活动执行此模块end  ***********/

        // 点击下一步
        $("body").on("click", ".next_step", function () {
            // 限制地区选择
            if ($('.q_people').find('input[name="area"]').is(":checked") && $('.city_box .checkbox>input:checked').length > 5) {
                toastr.warning("地域限制最多选择5个地区");
                return false;
            }
            // 性别限制选择
            if ($('.q_people').find('input[name="choiceSex"]').is(":checked") && $('.choiceSex').find('input[type="radio"]:checked').length <= 0){
                toastr.warning("请勾选对应的性别限制");
                return false;
            }
            // 快递类型选择
            var shipping_len = $('.shipping_box').find('input[name="shipping"]:checked');
            if (shipping_len.length <= 0) {
                toastr.warning("请选择预备配送的快递类型");
                return false;
            }

            // 1.包裹重量验证
            var weight = $('input[name="weight_text"]').val();
            if (shipping_len.val() != 'self' && !/^[0-9]+(.[0-9]{1,3})?$/.test(weight)) {
                toastr.warning("请输入合法的包裹重量");
                return false;
            } else if (weight > 40) {
                toastr.warning("包裹重量最大不超过40kg");
                return false;
            }

            // 判断是否选中定时发布
            var set_time_val = $('input[name="set_time_val"]').val();
            if ($('input[name="time_task"]').is(':checked') && set_time_val == "") {
                toastr.warning("请设置定时发布的报名活动时间");
                return false;
            }
            // 判断是否选中定时结束
            var set_over_time_val = $('input[name="set_over_time_val"]').val();
            if ($('input[name="time_over_task"]').is(':checked') && set_over_time_val == "") {
                toastr.warning("请设置定时发布的活动的结束时间");
                return false;
            }
            // 分时发布
            var custom_time_price = [], _set_time_pre_val = '';
            if ($('input[name="releasetime"]').is(':checked')){
                var _total_sum = 0;
                $(".releaseTimelists div>input").each(function (i, obj) {
                    var _nums = $(obj).val(), _idx = $(obj).data('idx');
                    if (_nums != '' && parseInt(_nums) > 0){
                        _total_sum += parseInt(_nums);
                        custom_time_price.push({'hour': _idx, 'nums': parseInt(_nums)});
                    }
                });
                if (_total_sum != _task_num) {
                    toastr.warning("总活动单数与时间点单数累加值应一致，请确认");
                    return false;
                }
                _set_time_pre_val = $('input[name="set_time_pre_val"]').val();
            }
            // 好评优化
            if ($(".praise_box").find('input[name="kwd_eval"]').is(":checked")) {
                $(".yz_praise_list").each(function () {
                    if ($(this).children("input").val() == '' || $(this).children("input").val().length >= 50) {
                        toastr.warning("关键字不能为空且不能超过50个字");
                        return false;
                    }
                });
            }
            // 自定义好评
            if ($(".praise_box").find('input[name="setting_eval"]').is(":checked")) {
                $(".youzhi_praise .zdy_praise_list").each(function () {
                    if ($(this).children("input").val() == '' || $(this).children("input").val().length >= 200) {
                        toastr.warning("不能为空且不能超过200个字");
                        return false;
                    }
                });
            }
            // 图文好评
            var _setting_pic_color = false, _setting_pic_size = false, _setting_pic_list = false, _setting_pic_content = false;
            if ($(".praise_box").find('input[name="setting_picture"]').is(":checked")) {
                var pic_err = false;
                var _txt_image_box = $('.txt_image');
                _txt_image_box.find(".zdy_praise_list").each(function () {
                    $(this).find(".pic_list").each(function (i, obj) {
                        if ($(obj).find('.uploaded_goods_imgs').length < 1) {
                            pic_err = true;
                        }
                    });
                });
                if (pic_err) {
                    toastr.warning("每组图片至少上传1张");
                    return false;
                }
                _setting_pic_color = rtn_array(_txt_image_box.find('input[name="color[]"]'));
                _setting_pic_size = rtn_array(_txt_image_box.find('input[name="size[]"]'));
                _setting_pic_list = rtn_imgs(_txt_image_box);
                _setting_pic_content = rtn_array(_txt_image_box.find('input[name="comment[]"]'));
                if (_setting_pic_list == 0) {
                    toastr.warning("图片上传出了点问题，请刷新后重新上传");
                    return false;
                }
            }
            // 视频评价
            var _setting_video_color = false, _setting_video_size = false, _setting_video_list = [], _setting_video_pic_list = false, _setting_video_content = false;
            if ($(".praise_box").find('input[name="setting_video"]').is(":checked")) {
                var _txt_video_box = $('.txt_video'), video_err = 0;
                _txt_video_box.find('input[name="upload_video"]').each(function () {
                    if (this.getAttribute('uploaded') != '1' || this.getAttribute('path') == '') {
                        video_err = 1;
                    } else {
                        _setting_video_list.push(this.getAttribute('path'));
                    }
                });
                if (video_err) {
                    toastr.warning("每组中需上传一段评价视频");
                    return false;
                }
                _setting_video_color = rtn_array(_txt_video_box.find('input[name="color[]"]'));
                _setting_video_size = rtn_array(_txt_video_box.find('input[name="size[]"]'));
                _setting_video_pic_list = rtn_imgs(_txt_video_box);
                _setting_video_content = rtn_array(_txt_video_box.find('input[name="comment[]"]'));
                if (_setting_video_list == 0) {
                    toastr.warning("视频上传出了点问题，请刷新后重新上传");
                    return false;
                }
            }

            // 数据提交
            var plat_refund = $('input[name="fase"]:checked').val();
            var add_speed = $('input[name="fase_task"]:checked').val();
            var add_reward = $('input[name="jiashang"]:checked').val();
            var add_reward_point = $('input[name="jiashang_text"]').val();
            var first_check = $('input[name="first"]:checked').val();
            var set_time = $('input[name="time_task"]:checked').val();
            var set_over_time = $('input[name="time_over_task"]:checked').val();
            var set_interval = $('input[name="time_interval"]:checked').val();
            var set_interval_val = $('select[name="set_interval_val"]').val();
            var interval_num = $('select[name="interval_num"]').val();
            var set_weight_val = $('input[name="weight_text"]').val();
            var extend_cycle = $('input[name="shopping_cycle"]:checked').val();
            var default_eval = $('input[name="default_eval"]:checked').val();
            var free_eval = $('input[name="free_eval"]:checked').val();
            var shopping_end = $('input[name="shopping_end"]:checked').val();
            var newhand = $('input[name="newhand"]:checked').val();
            var kwd_eval = $('input[name="kwd_eval"]:checked').val();
            var kwds = rtn_array($('input[name="keyword[]"]'));
            var setting_eval = $('input[name="setting_eval"]:checked').val();
            var eval_contents = rtn_array($('.zdy_praise').find('input[name="comment[]"]'));
            var setting_picture = $('input[name="setting_picture"]:checked').val();
            var setting_video = $('input[name="setting_video"]:checked').val();
            var shipping = $('.shipping_box').find('input[name="shipping"]:checked').val();
            var area_limit = $('.q_people').find('input[name="area"]').is(":checked") ? 1 : 0, area_limit_city = [];
            if (area_limit == 1) {
                $('.city_box .checkbox>input:checked').each(function () {
                    area_limit_city.push($(this).val());
                });
            }
            var sex_limit = $('.q_people').find('input[name="choiceSex"]').is(":checked") ? 1 : 0, sex_limit_val = 0;
            if (sex_limit == 1) {
                sex_limit_val = $('.choiceSex').find('input[type="radio"]:checked').val();
            }
            var reputation_limit = 0, _reputation_obj = $('.q_people').find('input[name="damon"]');
            if (_reputation_obj.is(":checked")) {
                if (_reputation_obj.parent().parent().find('input[type="radio"]:checked').val() == '2'){
                    reputation_limit = 1 ;
                }
            }
            var taoqi_limit = 0, _taoqi_obj = $('.q_people').find('input[name="taoqi"]');
            if (_taoqi_obj.is(":checked")) {
                if (_taoqi_obj.parent().parent().find('input[type="radio"]:checked').val() == '2'){
                    taoqi_limit = 1 ;
                }
            }
            var _traffic_list = [];
            var _traffic = parseFloat($('input[name="traffic"]').val());
            var _normal_nums = 0, _other_nums = 0;
            if (!isNaN(_traffic) && _traffic > 0) {
                $('.people_weight_info').find('input[type="checkbox"]:checked').each(function () {
                    var $this = $(this), _name = $this.attr('name'), _num = $this.parent().parent().find('input[type="text"]').val();
                    _traffic_list.push({name: _name, num: _num});
                    if ('normal_price' == _name) {
                        _normal_nums = _num;
                    } else {
                        if (_other_nums < _num) {
                            _other_nums=parseInt(_num);
                        }
                    }
                });
            }
            if (_other_nums > 0 && _other_nums > _normal_nums) {
                toastr.warning("浏览商品至少需要" + _other_nums + '访客/单');
                return false;
            }
            // Data Submit
            $.ajax({
                type: "POST",
                url: "/trade/super_char_eval_step5_submit/" + _trade_id,
                data: {
                    plat_refund: plat_refund,
                    add_speed: add_speed,
                    add_reward: add_reward,
                    add_reward_point: add_reward_point,
                    first_check: first_check,
                    set_time: set_time,
                    set_time_val: set_time_val,
                    set_over_time: set_over_time,
                    set_over_time_val: set_over_time_val,
                    set_interval: set_interval,
                    set_interval_val: set_interval_val,
                    interval_num: interval_num,
                    set_weight_val: set_weight_val,
                    shipping: shipping,
                    extend_cycle: extend_cycle,
                    shopping_end: shopping_end,
                    newhand: newhand,
                    default_eval: default_eval,
                    free_eval: free_eval,
                    kwd_eval: kwd_eval,
                    kwds: kwds,
                    setting_eval: setting_eval,
                    eval_contents: eval_contents,
                    area_limit: area_limit,
                    area_limit_city: area_limit_city,
                    sex_limit: sex_limit,
                    sex_limit_val: sex_limit_val,
                    reputation_limit: reputation_limit,
                    taoqi_limit: taoqi_limit,
                    custom_time_price: custom_time_price,
                    set_time_pre_val: _set_time_pre_val,
                    setting_picture: setting_picture,
                    setting_pic_color: _setting_pic_color,
                    setting_pic_size: _setting_pic_size,
                    setting_pic_list: _setting_pic_list,
                    setting_pic_content: _setting_pic_content,
                    setting_video: setting_video,
                    setting_video_list: _setting_video_list,
                    setting_video_color: _setting_video_color,
                    setting_video_size: _setting_video_size,
                    setting_video_pic_list: _setting_video_pic_list,
                    setting_video_content: _setting_video_content,
                    traffic_list: _traffic_list,
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

        //所有的label点击执行计算统计金币方法
        $("body").on("click", "label", function () {
            service();
        });

        // 图片上传
        $("body").on("click", ".uploaded_goods_img", function () {
            $(this).siblings("input").click();
        }).on("click", ".remove_upload_img", function () {
            var _parents_ul = $(this).parents(".up_load_list");
            $(this).parents("li").remove();
            if (_parents_ul.hasClass('img_5')){
                _parents_ul.removeClass('img_5');
                _parents_ul.find('li').show();
            } else {
                if (_parents_ul.find('input.uploaded_goods_img_val.hidden').length == 0) {
                    var up_load = '<li>' +
                        '<div class="uploaded_img_preview">' +
                        '<i style="display: none;" class="remove_upload_img"></i>' +
                        '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" style="width:128px;height:128px;" />' +
                        '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val hidden" onChange="javascript:setImagePreview(this);" uploaded="" base64="" path="" />' +
                        '</div></li>';
                    _parents_ul.append(up_load);
                }
            }
        });
    });

    service();
    //统计增值服务方法
    function service() {
        var service_html = '', discount_html = '';
        var service_fd = 0, discount_fd = 0;
        //1.押金直接返款
        if ($('input[name="fase"]').is(":checked")) {
            var _object = $('input[name="fase"]');
            var _sub_fee = parseFloat(_object.attr('val')) * 10000;
            service_html += '<p>押金直接返款：' + _object.attr('val') + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>押金直接返款：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        if ($('input[name="fase2"]').is(":checked")) {
            var _object = $('input[name="fase2"]');
            var _sub_fee = parseFloat(_object.attr('val')) * 10000;
            service_html += '<p>商家返款：' + _object.attr('val') + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>商家返款：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        //2.提升完成活动速度
        if ($('input[name="fase_task"]').is(":checked")) {
            service_html += '<p>提升完成活动速度：' + $('input[name="fase_task"]:checked').val() + '金币</p>';
            service_fd += (parseFloat($('input[name="fase_task"]:checked').val()) * 10000);
        }
        //3.加赏活动佣金
        if ($('input[name="jiashang"]').is(":checked")) {
            service_html += '<p>加赏活动佣金：' + $('input[name="jiashang"]').val() + '金币</p>';
            service_fd += (parseFloat($('input[name="jiashang"]').val()) * 10000);
        }
        //4.优先审单
        if ($('input[name="first"]').is(":checked")) {
            var _object = $('input[name="first"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>订单优先审核：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>订单优先审核：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 千人千面设置－地域限制
        if ($('input[name="area"]').is(":checked")) {
            var _object = $('input[name="area"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>地域限制：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>地域限制：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 千人千面设置－性别限制
        if ($('input[name="choiceSex"]').is(":checked")) {
            var _object = $('input[name="choiceSex"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>性别限制：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>性别限制：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 千人千面设置－信誉限制
        if ($('input[name="damon"]').is(":checked")) {
            var _object = $('input[name="damon"]');
            if (_object.parent().parent().find('input[type="radio"]:checked').val() == '2') {
                var _sub_fee = parseFloat(_object.val()) * 10000;
                service_html += '<p>钻级别限制：' + _object.val() + '金币</p>';
                service_fd += _sub_fee;
                var discount_single = _object.attr('data-discount');
                if (discount_single != '100') {
                    discount_html += '<p>钻级别限制：' + parseFloat(discount_single / 10) + '折</p>';
                    discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
                }
            }
        }
        // 千人千面设置－淘气值限制
        if ($('input[name="taoqi"]').is(":checked")) {
            var _object = $('input[name="taoqi"]');
            if (_object.parent().parent().find('input[type="radio"]:checked').val() == '2') {
                var _sub_fee = parseFloat(_object.val()) * 10000;
                service_html += '<p>淘气值限制：' + _object.val() + '金币</p>';
                service_fd += _sub_fee;
                var discount_single = _object.attr('data-discount');
                if (discount_single != '100') {
                    discount_html += '<p>淘气值限制：' + parseFloat(discount_single / 10) + '折</p>';
                    discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
                }
            }
        }
        //5.定时发布
        if ($('input[name="time_task"]').is(":checked")) {
            var _object = $('input[name="time_task"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>定时发布：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>定时发布：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 定时结束
        if ($('input[name="time_over_task"]').is(":checked")) {
            var _object = $('input[name="time_over_task"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>定时结束：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>定时结束：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 分时发布
        if ($('input[name="releasetime"]').is(":checked")) {
            var _object = $('input[name="releasetime"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>分时发布：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>分时发布：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        //6.间隔发布
        if ($('input[name="time_interval"]').is(":checked")) {
            var _object = $('input[name="time_interval"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>间隔发布：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>间隔发布：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        //6.间隔发布
        if ($('input[name="service_phone"]').is(":checked")) {
            service_html += '<p>人气权重优化：' + $('input[name="service_phone"]').val() + '金币</p>';
            service_fd += (parseFloat($('input[name="service_phone"]').val()) * 10000);
        }

        // 快递选择
        var _shipping = $('.shipping_box').find('input[name="shipping"]:checked');
        if (_shipping.length > 0) {
            var _sub_fee = parseFloat(_shipping.attr('data-totalprice')) * 10000;
            var _type = _shipping.val(), _price = parseFloat(_shipping.data('price')), _task_num = parseInt(_shipping.data('nums'));
            if (_type == 'self') {
                service_html += '<p>自发快递：' + (_price * _task_num).toFixed(2) + '金币</p>';
            } else {
                service_html += '<p>'+ _type.toUpperCase() +'快递配送：' + (_price * _task_num).toFixed(2) + '金币</p>';
            }
            service_fd += (_price * _task_num * 10000);
            var discount_single = _shipping.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>快递配送：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }

        //7.自定义包裹重量
        if ($('input[name="weight"]').is(":checked")) {
            service_html += '<p>自定义包裹重量：' + $('input[name="weight"]').val() + '金币</p>';
            service_fd += (parseFloat($('input[name="weight"]').val()) * 10000);
        }

        //8.延长买家购物周期
        if ($('input[name="shopping_cycle"]').is(":checked")) {
            var _object = $('input[name="shopping_cycle"]:checked');
            if (_object.val() != '1') {
                var _points = _object.attr('val');
                service_html += '<p>延长买家购物周期：' + _points + '金币</p>';
                service_fd += (parseFloat(_points) * 10000);
                var discount_single = _object.attr('data-discount');
                if (discount_single != '100') {
                    discount_html += '<p>延长买家购物周期：' + parseFloat(discount_single / 10) + '折</p>';
                    discount_fd += parseInt(parseFloat(_points) * (100 - discount_single) / 10000) * 100;
                }
            }
        }
        // 8-2.限制买号重复进店下单
        if ($('input[name="shopping_end"]').is(":checked")) {
            var _object = $('input[name="shopping_end"]:checked');
            var _points = _object.attr('val');
            service_html += '<p>限制买号重复进店下单：' + _points + '金币</p>';
            service_fd += (parseFloat(_points) * 10000);
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>限制买号重复进店下单：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(parseFloat(_points) * (100 - discount_single) / 10000) * 100;
            }
        }
        // 8-3.指定平台新注册买手接单
        if ($('input[name="newhand"]').is(":checked")) {
            var _object = $('input[name="newhand"]:checked');
            var _points = _object.attr('val'), _txt = _object.attr('data-txt');
            service_html += '<p>指定平台'+ _txt +'新注册下单：' + _points + '金币</p>';
            service_fd += (parseFloat(_points) * 10000);
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>指定平台'+ _txt +'新注册下单：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(parseFloat(_points) * (100 - discount_single) / 10000) * 100;
            }
        }
        // 人气权重优化
        var _traffic_points = parseFloat($('input[name="traffic"]').val());
        if (_traffic_points > 0){
            service_html += '<p>人气权重优化：' + _traffic_points + '金币</p>';
            service_fd += (parseFloat(_traffic_points) * 10000);
        }

        //9.好评优化
        // 优质好评
        if ($('input[name="kwd_eval"]').is(":checked")) {
            var _object = $('input[name="kwd_eval"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>好评优化：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>好评优化：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 自定义好评
        if ($('input[name="setting_eval"]').is(":checked")) {
            var _object = $('input[name="setting_eval"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>好评优化：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>好评优化：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 图文好评
        if ($('input[name="setting_picture"]').is(":checked")) {
            var _object = $('input[name="setting_picture"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>图文好评：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>图文好评：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 视频好评
        if ($('input[name="setting_video"]').is(":checked")) {
            var _object = $('input[name="setting_video"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>视频评价：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>视频评价：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        $(".service_total_detail").html(service_html);
        $(".j-discount-info").html(discount_html);
        $(".service_sub_total_fd").text(service_fd / 10000);
        $(".service_total_fd").text((service_fd - discount_fd) / 10000);
        // 总金币统计
        $(".service_fd_zong").html(parseFloat(service_fd - discount_fd + parseFloat($(".fee_fd").html() * 10000)) / 10000);
    }

    // 对象转数组
    function rtn_array(obj) {
        var tmpArr = new Array();
        obj.each(function () {
            tmpArr.push($(this).val());
        });
        return tmpArr;
    }

    function toggle(obj) {
        if ($(obj).is(':checked')) {
            var _name = obj.getAttribute('name');
            if (_name == 'choiceSex' || _name == 'damon' || _name == 'taoqi'){
                if ($(obj).parent().parent().find('input[type="radio"]:checked').length <= 0){
                    $(obj).parent().parent().find('input[type="radio"]').attr('checked', true);
                }
            }
            $(obj).parent('label').next('div').show();
        } else {
            $(obj).parent('label').next('div').hide();
        }
    }
    // 分时发布修改预定时间触发事件
    function fun_custom_time_pre_set() {
        var _date_time = $('input[name="set_time_pre_val"]').val();
        if (_date_time != ''){
            if($('input[name="time_task"]').is(":checked") == true){
                $('input[name="time_task"]').trigger('click');
            }
            if($('input[name="releasetime"]').is(":checked") == false){
                $('input[name="releasetime"]').trigger('click');
            }
            var _date_time_obj = new Date(_date_time);
            var _current_time_obj = new Date();
            var _hour = -1 ;
            if (_date_time_obj.getTime() < _current_time_obj.getTime()) {
                _hour = _current_time_obj.getHours();
            }
            $(".releaseTimelists div>input").each(function (i, obj) {
                var $this = $(obj), _idx = $this.data('idx');
                $this.removeAttr('disabled');
                if (_idx <= _hour){
                    $this.val('');
                    $this.attr('disabled', '');
                }
            });
        }
    }
</script>
</body>
</html>