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
            <li style="width:20%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:20%" class="cur"><em class="Processyes">2</em><span>填写商品信息</span></li>
            <li style="width:20%" class="cur"><em class="Processyes">3</em><span>选择活动数量</span></li>
            <li style="width:20%"><em class="Processyes">4</em><span>选增值服务</span></li>
            <li style="width:20%"><em>5</em><span>支付</span></li>
            <li style="width:20%" class="Processlast"><em>6</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <!-- 发活动顶部活动步骤进度start -->
    <div class="step4_box">
        <h1>4.选择增值服务<p>已选择：<span><?= $trade_select['plat_name']. '&nbsp;|&nbsp;'. $trade_select['shop_name']. '&nbsp;|&nbsp;'. $trade_select['type_name']. '&nbsp;|&nbsp;'. $trade_select['total_num']; ?>单</p></h1>
        <div class="service_box">
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
                        <label><input type="checkbox" data-discount="<?= isset($discount['reputation_limit']) ? $discount['reputation_limit'] : 100; ?>" value="<?= REPUTATION_LIMIT * $task_num ?>" <?= $reputation_limit ? 'checked' : ''; ?> name="damon" >仅限钻级别的买号可接此活动<span class="text-grey" style="font-size:14px;">（+<?= REPUTATION_LIMIT ?>金币/单）</span></label>
                    </div>
                    <div class="limitBox">
                        <label><input type="checkbox" data-discount="<?= isset($discount['taoqi_limit']) ? $discount['taoqi_limit'] : 100; ?>" value="<?= TAOQI_LIMIT * $task_num ?>" <?= $taoqi_limit ? 'checked' : ''; ?> name="taoqi" >仅限淘气值1000以上买号可接此活动<span class="text-grey" style="font-size:14px;">（+<?= TAOQI_LIMIT ?>金币/单）</span></label>
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
                    <div style="margin-left:30px;color:#999999;">默认发布日期为: <span style="color:red;">2019-12-12</span> </div>
                    <div style="display: none">
                        <label>选择发布日期</label>
                        <input type="text" name="set_time_pre_val" onclick="WdatePicker({minDate:'%y-%M-#{%d} #{%H+2}',dateFmt:'yyyy-MM-dd', onpicked: fun_custom_time_pre_set})" value="2019-12-12" style="padding-left:8px;margin-top:8px;" />
                    </div>
                    <div class="releaseTimelists">
                        <?php $compare_hour = (strtotime($set_time_pre_val) > time()) ? -1 : date('H'); ?>
                        <?php foreach ($hours_list as $item): ?>
                            <div>
                                <label><span><?= $item ?>时</span></label>
                                <input type="number" min="1" max="<?= $task_num ?>" value="<?= ($compare_hour < $item && isset($custom_time_price[$item])) ? $custom_time_price[$item] : ''; ?>" data-idx="<?= $item ?>" />单
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
        <tr><th width="15%">分类</th><th width="35%">费用明细</th><th width="15%">小计</th><th width="15%">优惠折扣</th><th width="20%">合计</th></tr>
        <tr>
            <td><b>服务费</b></td>
            <td>
                <div>
                    <p>套餐服务费：<?php echo $trade_info->total_fee*1; ?>金币/单</p>
                    <?php if ($trade_info->is_phone): ?>
                    <p>手机端加成：<?php echo ORDER_DIS_PRICE; ?>金币/单</p>
                    <?php endif; ?>
                </div>
            </td>
            <td><p><span class="red"><?php echo $point_subtotal*1; ?></span>金币</p></td>
            <td><p>--</p></td>
            <td><p><?php echo $point_subtotal*1; ?>&nbsp;x&nbsp;<?php echo $trade_info->total_num; ?>&nbsp;=&nbsp;<span class="fee_fd"><?php echo bcmul($point_subtotal, $trade_info->total_num, 2)*1; ?></span>金币</p></td>
        </tr>
        <tr>
            <td><b>增值服务</b></td>
            <td><div class="service_total_detail"></div></td>
            <td><p><span class="service_sub_total_fd">--</span>金币</p></td>
            <td><p class="j-discount-info">--</p></td>
            <td><p><span class="service_total_fd">--</span>金币</p></td>
        </tr>
    </table>
    <div class="service_sum">费用合计&nbsp;&nbsp;&nbsp;&nbsp;服务费：<span class="service_fd_zong"></span>金币</div>
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
        var _task_num = parseFloat('<?php echo $task_num; ?>');
        //加赏佣金输入操作
        $(".jiashang_text").on("keyup blur change", function () {
            var $this = $(this), _jiashang = parseFloat($this.val()), _parent_div = $this.parent();
            var _totle = parseFloat(_jiashang * _task_num);
            _parent_div.find('input[type=checkbox]').val(_totle).prop('checked', true);
            _parent_div.find('em').text(_jiashang);
            _parent_div.find("span").text(_totle);
            service();
        });

        //提升活动速度选择
        $(".fase_task_box div label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
            }
        });

        // 延长买家购物周期
        $(".shopping_cycle_box>div>label").click(function () {
            if ($(this).children("input").is(":checked")) {
                $(this).siblings("label").children("input").attr("checked", false);
                var task_url = '<?php echo $task_url; ?>';
                var shopping_cycle = $(this).children("input").val();
                if (shopping_cycle == 2) {
                    $(".shopping_cycle_p1 span").html(shopping_cycle + '个月');
                    $(".shopping_cycle_p2").show();
                    $(".shopping_cycle_p2").children(".shopping_cycle_dj").html(1);
                    $(".shopping_cycle_p2").children(".shopping_cycle_total").html(parseFloat(1) * parseFloat(_task_num) * parseFloat(task_url));
                } else if (shopping_cycle == 3) {
                    $(".shopping_cycle_p1 span").html(shopping_cycle + '个月');
                    $(".shopping_cycle_p2").show();
                    $(".shopping_cycle_p2").children(".shopping_cycle_dj").html(1.5);
                    $(".shopping_cycle_p2").children(".shopping_cycle_total").html(parseFloat(1.5) * parseFloat(_task_num) * parseFloat(task_url));
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

        // 地域限制（最多只能选择3个地区限制）
        $('.city_box .checkbox>input').change(function () {
            if ($('.city_box .checkbox>input:checked').length > 3){
                $(this).removeAttr("checked");
                toastr.warning("最多只能选择5个地区限制");
            }
        });

        /************   手机端活动执行此模块start  ***********/
        // 手机端人气权重优化：
        $('input[name="liulan_text"]').on("blur change", function () {
            if (parseFloat($(this).val()) < 4 || $(this).val() == '') {
                $(this).val(4);
            }
            $(this).siblings("p").children("i").html($(this).val());
            $(this).siblings("p").children("em").html(parseFloat($(this).val() * 0.5 * _task_num).toFixed(2));
            $(this).siblings("label").children("input").val(parseFloat($(this).val() * 0.5 * _task_num).toFixed(2));
            service_phone();
        });
        $('input[name="sc_goods_text"]').on("blur change", function () {
            var liulan_num = $('input[name="liulan_text"]').val();
            if (parseFloat($(this).val()) < 2 || $(this).val() == '') {
                $(this).val(2);
            }
            if (parseFloat($(this).val()) > liulan_num) {
                $(this).val(liulan_num);
            }
            $(this).siblings("p").children("i").html($(this).val());
            $(this).siblings("p").children("em").html(parseFloat($(this).val() * 0.7 * _task_num).toFixed(2));
            $(this).siblings("label").children("input").val(parseFloat($(this).val() * 0.7 * _task_num).toFixed(2));
            service_phone();
        });
        $('input[name="gwc_text"]').on("blur change", function () {
            var liulan_num = $('input[name="liulan_text"]').val();
            if (parseFloat($(this).val()) < 0 || $(this).val() == '') {
                $(this).val(0);
            }
            if (parseFloat($(this).val()) > liulan_num) {
                $(this).val(liulan_num);
            }
            $(this).siblings("p").children("i").html($(this).val());
            $(this).siblings("p").children("em").html(parseFloat($(this).val() * 1 * _task_num).toFixed(2));
            $(this).siblings("label").children("input").val(parseFloat($(this).val() * 1 * _task_num).toFixed(2));
            service_phone();
        });
        $(".service_phone_list label").click(function () {
            service_phone();
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

        service_phone();

        function service_phone() {
            var service_val = 0;
            $(".service_phone_list").each(function () {
                if ($(this).children("label").children("input").is(":checked")) {
                    service_val = service_val * 10000 + parseFloat($(this).children("label").children("input").val()) * 10000;
                    service_val = service_val / 10000;
                }
            });
            $(".service_phone_total span").html(service_val);
            $('input[name="service_phone"]').val(service_val);

            service();
        }
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

            // 数据提交
            var add_speed = $('input[name="fase_task"]:checked').val();
            var add_reward = $('input[name="jiashang"]:checked').val();
            var add_reward_point = $('input[name="jiashang_text"]').val();
            var first_check = $('input[name="first"]:checked').val();
            var set_time = $('input[name="time_task"]:checked').val();
            var set_over_time = $('input[name="time_over_task"]:checked').val();
            var set_interval = $('input[name="time_interval"]:checked').val();
            var set_interval_val = $('select[name="set_interval_val"]').val();
            var interval_num = $('select[name="interval_num"]').val();
            var extend_cycle = $('input[name="shopping_cycle"]:checked').val();
            var shopping_end = $('input[name="shopping_end"]:checked').val();
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
            var reputation_limit = $('.q_people').find('input[name="damon"]').is(":checked") ? 1 : 0;
            var taoqi_limit = $('.q_people').find('input[name="taoqi"]').is(":checked") ? 1 : 0;
            // Data Submit
            $.ajax({
                type: "POST",
                url: "/trade/refund_step4_submit/<?= $trade_info->id; ?>",
                data: {
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
                    set_time_pre_val: _set_time_pre_val,
                    extend_cycle: extend_cycle,
                    shopping_end: shopping_end,
                    area_limit: area_limit,
                    area_limit_city: area_limit_city,
                    sex_limit: sex_limit,
                    sex_limit_val: sex_limit_val,
                    reputation_limit: reputation_limit,
                    taoqi_limit: taoqi_limit,
                    custom_time_price: custom_time_price,
                },
                datatype: "json",
                success: function (data) {
                    var _data = eval("(" + data + ")");
                    if (_data.error == 0) {
                        location.href = "/trade/step/<?php echo $trade_info->id; ?>";
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
    });

    service();
    var _task_num = parseFloat('<?php echo $task_num; ?>');
    //统计增值服务方法
    function service() {
        var service_html = '', discount_html = '';
        var service_fd = 0, discount_fd = 0;
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
        // 千人千面设置－仅限钻级别的买号可接此活动
        if ($('input[name="damon"]').is(":checked")) {
            var _object = $('input[name="damon"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>钻级别限制：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>钻级别限制：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
            }
        }
        // 千人千面设置－仅限淘气值1000以上买号可接此活动
        if ($('input[name="taoqi"]').is(":checked")) {
            var _object = $('input[name="taoqi"]');
            var _sub_fee = parseFloat(_object.val()) * 10000;
            service_html += '<p>淘气值限制：' + _object.val() + '金币</p>';
            service_fd += _sub_fee;
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>淘气值限制：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(_sub_fee * (100 - discount_single) / 10000) * 100;
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

        //8.延长买家购物周期
        if ($('input[name="shopping_cycle"]').is(":checked")) {
            var _object = $('input[name="shopping_cycle"]:checked');
            var _points = _object.attr('val');
            service_html += '<p>延长买家购物周期：' + _points + '金币</p>';
            service_fd += (parseFloat(_points) * 10000);
            var discount_single = _object.attr('data-discount');
            if (discount_single != '100') {
                discount_html += '<p>延长买家购物周期：' + parseFloat(discount_single/10) + '折</p>';
                discount_fd += parseInt(parseFloat(_points) * (100 - discount_single) / 10000) * 100;
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