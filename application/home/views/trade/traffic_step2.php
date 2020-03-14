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
    <style type="text/css">
        .white_box_ts {background-color: #ffffff;border-radius: 5px;padding:10px;margin: 15px 0;font-size: 14px;}
        .white_box_ts h3 {margin-top: 15px;font-size: 16px;font-weight: 700;}
        .white_box_ts h3 span {font-size: 14px;margin-left: 20px;}
        .message_text{margin-left: 0px;}
    </style>
</head>
<body>
<?php $this->load->view("/common/top", ['site' => 'trade']); ?>
<div class="com_title">商家报名活动</div>
<!-- 发活动顶部活动步骤进度start -->
<div class="trade_top contain">
    <div class="Process">
        <ul class="clearfix">
            <li style="width:25%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:25%"><em class="Processyes">2</em><span>填写商品信息</span></li>
            <li style="width:25%"><em>3</em><span>选增值服务</span></li>
            <li style="width:25%"><em>4</em><span>支付</span></li>
            <li style="width:25%" class="Processlast"><em>5</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <div class="step2_box">
        <h3 style="margin-top:0">2.填写商品信息</h3>
        <!-- 填写商品信息start -->
        <div class="one_box">
            <div class="goods_info_box white_box">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="goods_url">
                            <p><label><span class="color_red">* </span>商品链接：</label></p>
                            <input type="text" name="goods_url" value="<?php echo $trade_item->goods_url; ?>" autocomplete="off" disableautocomplete/><span class="color_red">（必填）</span>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="goods_title">
                            <p><label><span class="color_red">* </span>商品标题：</label></p>
                            <input type="text" name="goods_title" value="<?php echo $trade_item->goods_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">（必填）</span><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="guige">
                            <p><label>商品规格：</label></p>
                            <input type="text" placeholder="如：颜色" name="guige_color" value="<?php echo $trade_item->color; ?>" autocomplete="off" disableautocomplete />
                            <input type="text" placeholder="如：尺码" name="guige_size" value="<?php echo $trade_item->size; ?>" autocomplete="off" disableautocomplete />
                        </div>
                    </div>
                </div>
                <div class="row price_search">
                    <div class="col-xs-12">
                        <p><label>搜索页面展示价格：</label></p>
                        <input type="text" onblur="javascript:this.value=this.value.replace(/[^\d\¥￥－\-.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d\¥￥－\-.]/g,'')" value="<?= $trade_item->show_price; ?>" />
                        <em>如该商品有满减、促销、多规格等情况，请填写此金额</em>
                    </div>
                </div>
                <div class="prompt">如商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</div>
            </div>
            <div class="search_type_box white_box">
                <h1>如何找到您的商品：</h1>
                <!-- 手机搜索start -->
                <div class="phone_search_box">
                    <label><input type="checkbox" name="phone_check" <?php if ($trade_info->is_phone): ?>checked<?php endif; ?> />手机<?= $plat_name ?>活动<span>（用户"手机<?= $plat_name ?>APP"搜索）</span></label>
                    <div class="phone_taobao_con">
                        <div class="phone_taobao_pic row">
                            <div class="col-xs-5">
                                <div class="phone_goods_pic_con pull-left">
                                    <?php if ($app_search[0]->search_img): ?>
                                        <img src="<?= $app_search[0]->search_img; ?>" height="130" width="130" id="goods_pic2" title="点击更换商品主图" />
                                    <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic2" title="点击上传商品主图" />
                                    <?php endif; ?>
                                    <input type="file" name="goods_pic" id="2" onChange="javascript:setImagePreview(this);" uploaded="<?= $app_search[0]->search_img; ?>" base64="<?= $app_search[0]->search_img; ?>" accept="image/*"/>
                                </div>
                                <div class="pull-left goods_pic_con">
                                    <h5><span class="color_red">*</span>商品主图1</h5>
                                    <p>图片尺寸：1200×1200以内</p>
                                    <p>图片大小：不能大于2M</p>
                                    <p>图片格式：jpg、png、gif</p>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="phone_goods_pic_con2 pull-left">
                                    <?php if ($app_search[0]->search_img2): ?>
                                        <img src="<?= $app_search[0]->search_img2; ?>" height="130" width="130" id="goods_pic3" title="点击更换商品主图" />
                                    <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic3" title="点击上传商品主图" />
                                    <?php endif; ?>
                                    <input type="file" name="goods_pic" id="3" onChange="javascript:setImagePreview(this);" uploaded="<?= $app_search[0]->search_img2; ?>" base64="<?= $app_search[0]->search_img2; ?>" accept="image/*"/>
                                </div>
                                <div class="pull-left goods_pic_con">
                                    <h5>商品主图2</h5>
                                    <p>图片尺寸：1200×1200以内</p>
                                    <p>图片大小：不能大于2M</p>
                                    <p>图片格式：jpg、png、gif</p>
                                </div>
                            </div>
                        </div>
                        <div class="careful"><span>温馨提示：由于系统关键字的分配不确定性，如需某个关键字成交占多数的话可以将此关键词单独发活动；</span></div>
                        <?php if($trade_info->trade_type != '7'): ?>
                            <div class="phone_keyword_list_box">
                                <?php foreach ($app_search as $k=>$v): ?>
                                    <div class="phone_keyword_list">
                                        <div style="display: inline-block;width:100%">
                                            <div class="col-xs-4" style="padding-left:0">
                                                <label>手机<?= $plat_name ?>关键字来源<em><?php echo $k+1; ?></em>：<?php if ($k > 0): ?><a href="javascript:;" class="phone_keyword_list_del">删除</a></label><?php endif; ?></label>
                                                <div class="keyword_search"><input class="blue_input" type="text" name="app_keyword[]" value="<?= $v->kwd; ?>" maxlength="50" style="width:256px" autocomplete="off"/>&nbsp;&nbsp;</div>
                                            </div>
                                            <div class="col-xs-8">
                                                <label style="font-weight:500">首日访客人数：</label>
                                                <div class="keyword_search"><input class="blue_input" type="text" name="first_day[]" value="<?= isset($traffic_list[$v->kwd][0]) ? $traffic_list[$v->kwd][0] : ''; ?>" onblur="javascript:this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" /><small class="red">（请填写10的倍数）</small></div>
                                            </div>
                                        </div>
                                        <div style="display:flex;margin-top:10px;">
                                            <div class="phone_sort col-xs-2" style="padding-left:0">
                                                <p style="margin:5px 0">商品排序：</p>
                                                <select name="app_order_way" style="height:32px;" class="blue_input">
                                                    <option value="综合排序" <?php if ($v->order_way == '综合排序'): ?>selected<?php endif; ?>>综合排序</option>
                                                    <option value="销量优先" <?php if ($v->order_way == '销量优先'): ?>selected<?php endif; ?>>销量优先</option>
                                                    <option value="价格从低到高" <?php if ($v->order_way == '价格从低到高'): ?>selected<?php endif; ?>>价格从低到高</option>
                                                    <option value="价格从高到底" <?php if ($v->order_way == '价格从高到底'): ?>selected<?php endif; ?>>价格从高到底</option>
                                                    <option value="信用排序" <?php if ($v->order_way == '信用排序'): ?>selected<?php endif; ?>>信用排序</option>
                                                </select>
                                            </div>
                                            <div class="price_range col-xs-4">
                                                <p>价格区间：</p>
                                                <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $v->low_price; ?>" />元&nbsp;- <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $v->high_price; ?>" />元
                                            </div>
                                            <div class="address col-xs-4">
                                                <p style="margin:5px 0">发货地：</p>
                                                <span class="ie7_areaHack_area">
                                                    <div class="sel-loc-box">
                                                        <div class="fake-select sel-loc">
                                                            <ul class="selected">
                                                                <li>
                                                                    <s class="sel_dropdown">
                                                                        <s class="i">
                                                                        </s>
                                                                    </s>
                                                                    <input type="hidden" name="app_area[]" class="position" value="<?php echo $v->area; ?>" />
                                                                    <a href="javascript:;" data-value="<?php echo $v->area; ?>" data-nogo="true"><?php echo $v->area; ?></a>
                                                                </li>
                                                            </ul>
                                                            <div style="display: none;" class="toselect">
                                                                <ul class="loc1"><li class="checked"><a _val="全国" href="javascript:;">全国</a></li></ul>
                                                                <ul class="loc2 split">
                                                                    <li><a trace="location" _val="江苏,浙江,上海">江浙沪</a></li>
                                                                    <li><a trace="location" _val="广州,深圳,中山,珠海,佛山,东莞,惠州">珠三角</a></li>
                                                                    <li><a trace="location" _val="香港,澳门,台湾">港澳台</a></li>
                                                                    <li><a trace="location" _val="美国,英国,法国,瑞士,澳洲,新西兰,加拿大,奥地利,韩国,日本,德国,意大利,西班牙,俄罗斯,泰国,印度,荷兰,新加坡,其它国家">海外</a></li>
                                                                    <li><a trace="location">北京</a></li>
                                                                    <li><a trace="location">上海</a></li>
                                                                    <li><a trace="location">广州</a></li>
                                                                    <li><a trace="location">深圳</a></li>
                                                                    <li><a trace="location" _val="北京,天津">京津</a></li>
                                                                </ul>
                                                                <ul class="loc3">
                                                                    <li><a trace="location">杭州</a></li>
                                                                    <li><a trace="location">温州</a></li>
                                                                    <li><a trace="location">宁波</a></li>
                                                                    <li><a trace="location">南京</a></li>
                                                                    <li><a trace="location">苏州</a></li>
                                                                    <li><a trace="location">济南</a></li>
                                                                    <li><a trace="location">青岛</a></li>
                                                                    <li><a trace="location">大连</a></li>
                                                                    <li><a trace="location">无锡</a></li>
                                                                    <li><a trace="location">合肥</a></li>
                                                                    <li><a trace="location">天津</a></li>
                                                                    <li><a trace="location">长沙</a></li>
                                                                    <li><a trace="location">武汉</a></li>
                                                                    <li><a trace="location">石家庄</a></li>
                                                                    <li><a trace="location">郑州</a></li>
                                                                    <li><a trace="location">成都</a></li>
                                                                    <li><a trace="location">重庆</a></li>
                                                                    <li><a trace="location">西安</a></li>
                                                                    <li><a trace="location">昆明</a></li>
                                                                    <li><a trace="location">南宁</a></li>
                                                                    <li><a trace="location">福州</a></li>
                                                                    <li><a trace="location">厦门</a></li>
                                                                    <li><a trace="location">南昌</a></li>
                                                                    <li><a trace="location">东莞</a></li>
                                                                    <li><a trace="location">沈阳</a></li>
                                                                    <li><a trace="location">长春</a></li>
                                                                    <li><a trace="location">哈尔滨</a></li>
                                                                </ul>
                                                                <ul class="loc4 split">
                                                                    <li><a trace="location">河北</a></li>
                                                                    <li><a trace="location">河南</a></li>
                                                                    <li><a trace="location">湖北</a></li>
                                                                    <li><a trace="location">湖南</a></li>
                                                                    <li><a trace="location">福建</a></li>
                                                                    <li><a trace="location">江苏</a></li>
                                                                    <li><a trace="location">江西</a></li>
                                                                    <li><a trace="location">广东</a></li>
                                                                    <li><a trace="location">广西</a></li>
                                                                    <li><a trace="location">海南</a></li>
                                                                    <li><a trace="location">浙江</a></li>
                                                                    <li><a trace="location">安徽</a></li>
                                                                    <li><a trace="location">吉林</a></li>
                                                                    <li><a trace="location">辽宁</a></li>
                                                                    <li><a trace="location">黑龙江</a></li>
                                                                    <li><a trace="location">山东</a></li>
                                                                    <li><a trace="location">山西</a></li>
                                                                    <li><a trace="location">陕西</a></li>
                                                                    <li><a trace="location">新疆</a></li>
                                                                    <li><a trace="location">云南</a></li>
                                                                    <li><a trace="location">贵州</a></li>
                                                                    <li><a trace="location">四川</a></li>
                                                                    <li><a trace="location">甘肃</a></li>
                                                                    <li><a trace="location">宁夏</a></li>
                                                                    <li><a trace="location">西藏</a></li>
                                                                    <li><a trace="location">香港</a></li>
                                                                    <li><a trace="location">澳门</a></li>
                                                                    <li><a trace="location">台湾</a></li>
                                                                    <li><a trace="location">内蒙古</a></li>
                                                                    <li><a trace="location">青海</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="service">
                                            <p>折扣和服务：</p>
                                            <p>
                                                <label><input class="app_discount" type="checkbox" value="包邮" <?php if (in_array('包邮', $v->discount_arr)): ?>checked<?php endif; ?> />包邮</label>
                                                <label><input class="app_discount" type="checkbox" value="天猫" <?php if (in_array('天猫', $v->discount_arr)): ?>checked<?php endif; ?> />天猫</label>
                                                <label><input class="app_discount" type="checkbox" value="全球购" <?php if (in_array('全球购', $v->discount_arr)): ?>checked<?php endif; ?> />全球购</label>
                                                <label><input class="app_discount" type="checkbox" value="消费者保障" <?php if (in_array('消费者保障', $v->discount_arr)): ?>checked<?php endif; ?> />消费者保障</label>
                                                <label><input class="app_discount" type="checkbox" value="手机专享" <?php if (in_array('手机专享', $v->discount_arr)): ?>checked<?php endif; ?> />手机专享</label>
                                                <label><input class="app_discount" type="checkbox" value="淘金币抵钱" <?php if (in_array('淘金币抵钱', $v->discount_arr)): ?>checked<?php endif; ?> />淘金币抵钱</label>
                                                <label><input class="app_discount" type="checkbox" value="货到付款" <?php if (in_array('货到付款', $v->discount_arr)): ?>checked<?php endif; ?> />货到付款</label>
                                                <label><input class="app_discount" type="checkbox" value="7+天退换货" <?php if (in_array('7+天退换货', $v->discount_arr)): ?>checked<?php endif; ?> />7+天退换货</label>
                                                <label><input class="app_discount" type="checkbox" value="促销" <?php if (in_array('促销', $v->discount_arr)): ?>checked<?php endif; ?> />促销</label>
                                                <label><input class="app_discount" type="checkbox" value="花呗分期" <?php if (in_array('花呗分期', $v->discount_arr)): ?>checked<?php endif; ?> />花呗分期</label>
                                                <label><input class="app_discount" type="checkbox" value="天猫超市" <?php if (in_array('天猫超市', $v->discount_arr)): ?>checked<?php endif; ?> />天猫超市</label>
                                                <label><input class="app_discount" type="checkbox" value="天猫国际" <?php if (in_array('天猫国际', $v->discount_arr)): ?>checked<?php endif; ?> />天猫国际</label>
                                                <label><input class="app_discount" type="checkbox" value="通用排序" <?php if (in_array('通用排序', $v->discount_arr)): ?>checked<?php endif; ?> />通用排序</label>
                                                <input type="hidden" name="app_discount_text[]" />
                                            </p>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 classification">
                                                <p>商品分类：</p>
                                                <input class="blue_input" type="text" name="goods_cate[]" value="<?php echo $v->goods_cate; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="phone_keyword_add" <?php if (count($app_search)>=5): ?>style="display:none;"<?php endif; ?>>
                                <p class="color_red">增加关键词可提高活动安全保障</p>
                                <a href="javascript:;"><em class="add_icon">+</em>增加搜索关键词</a><i>（最多可添加4个方案）</i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- 手机搜索end -->
            </div>
            <div class="order_distribution_box white_box">
                <!-- 关键字执行天数 -->
                <div class="service js-exec-days" style="margin-top:16px;">
                    <div style="padding-bottom:12px">
                        <p style="margin-top:3px;font-weight:700">设置任务开始时间：</p>
                        <input name="start_time_date" style="background:url(/static/imgs/rili2.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({minDate:'%y-%M-#{%d}', dateFmt:'yyyy-MM-dd', onpicked: fun_selected_start})" value="<?= $start_time ?>" class="blue_input" />
                        <input name="start_time_time" style="background:url(/static/imgs/time.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({dateFmt:'HH:mm'})" value="<?= $start_mins ?>" class="blue_input" />
                    </div>
                    <p style="font-weight:700">设置执行天数：</p>
                    <p style="margin-left:16px;">
                        <label><input name="exec_days" class="exec_days" <?= ($exec_days == '1') ? 'checked' : ''; ?> type="checkbox" value="1" />1天</label>
                        <label><input name="exec_days" class="exec_days" <?= ($exec_days == '2') ? 'checked' : ''; ?> type="checkbox" value="2" />2天</label>
                        <label><input name="exec_days" class="exec_days" <?= ($exec_days == '6') ? 'checked' : ''; ?> type="checkbox" value="6" />6天<span class="red" style="float:inherit">（推荐）</span></label>
                        <label><input name="exec_days" class="exec_days" <?= ($exec_days == '8') ? 'checked' : ''; ?> type="checkbox" value="8" />8天</label>
                        <label><input name="exec_days" class="exec_days" <?= ($exec_days == '12') ? 'checked' : ''; ?> type="checkbox" value="12" />12天</label>
                    </p>
                </div>
                <div class="js-exeu-distribution">
                    <p style="margin-bottom:8px;font-weight:700">设置每日访客人数：（<span class="red">每日访客递增比例，建议设置在前一天访客数量上增加5%－20%</span>）<span style="font-weight:normal">请填写10的倍数，且数字不能小于前一天的访客人数</span></p>
                    <div style="overflow-x:auto; width:100%;">
                        <table class="table table-hover table-bordered">
                            <?php $show_days = ($exec_days < 6) ? 6 : $exec_days; ?>
                            <thead>
                            <tr>
                                <th style="width:128px;">&nbsp;</th>
                                <?php for ($i = 0; $i < $show_days; $i++){ ?>
                                <th data-datetime="<?= date('Y-m-d', strtotime($start_time) + $i*86400) ?>" class="<?= ($exec_days < $i+1) ? 'disabled' : ''; ?>"><?= date('m月d日', strtotime($start_time) + $i*86400) ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($traffic_list as $key => $item_list): ?>
                            <tr>
                                <td><nobr>关键字：<span class="j-key-words red"><?= $key ?></span></nobr></td>
                                <?php foreach ($item_list as $idx => $item): ?>
                                <td><input type="number" class="form-controller" placeholder="访客人数" value="<?= $item ?>" min="<?= $item_list[0] ?>" step="10"/></td>
                                <?php endforeach; ?>
                                <?php if ($exec_days < 6): ?>
                                <?php for ($i = 0; $i < (6 - $exec_days); $i++){ echo '<td class="disabled"><input type="number" class="form-controller" placeholder="访客人数" min="'. $item_list[0] .'" step="10" disabled /></td>';} ?>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>小计：</td>
                                <?php for ($i = 0; $i < $show_days; $i++){ ?>
                                <td class="<?= ($exec_days < $i+1) ? 'disabled' : ''; ?>"><nobr>当日访客：<span class="j-p-total red">0</span>人</nobr></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="13" style="text-align:right;font-weight:600;font-size:15px;">总访客数：<span class="j-total red">0</span>人 x 单价：<span class="red"><?= floatval($normal_price); ?></span>金币 ＝ <span class="j-amount red">0</span>金币</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="distribution_rate_box white_box">
                <p>请设置每日收藏、加购、领取优惠券占比</p>
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr><th>类别</th><th>占比</th><th>时间</th><th>访客数</th><th>价格</th><th>总花费</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($traffic_type_list as $key => $item): ?>
                    <tr data-type="<?= $key ?>" data-price="<?= $item['price'] ?>">
                        <td><?= $item['title'] ?></td>
                        <td><select class="j-rate"><option <?= ($rate_list[$key] == 0) ? 'selected':''; ?> value="0">0%</option><option <?= ($rate_list[$key] == 0.1) ? 'selected':''; ?> value="0.1">10%</option><option <?= ($rate_list[$key] == 0.2) ? 'selected':''; ?> value="0.2">20%</option><option <?= ($rate_list[$key] == 0.3) ? 'selected':''; ?> value="0.3">30%</option><option <?= ($rate_list[$key] == 0.4) ? 'selected':''; ?> value="0.4">40%</option><option <?= ($rate_list[$key] == 0.5) ? 'selected':''; ?> value="0.5">50%</option><option <?= ($rate_list[$key] == 0.6) ? 'selected':''; ?> value="0.6">60%</option><option <?= ($rate_list[$key] == 0.7) ? 'selected':''; ?> value="0.7">70%</option><option <?= ($rate_list[$key] == 0.8) ? 'selected':''; ?> value="0.8">80%</option><option <?= ($rate_list[$key] == 0.9) ? 'selected':''; ?> value="0.9">90%</option><option <?= ($rate_list[$key] == 1) ? 'selected':''; ?> value="1">100%</option></select></td>
                        <td><span class="j-rate-days"><?= $exec_days; ?></span> 天</td>
                        <td><span class="j-rate-nums">0</span> 人</td>
                        <td><span class="j-rate-price" data-price="<?= $item['price'] ?>"><?= $item['price'] ?></span> 金币</td>
                        <td><span class="j-rate-total">0</span> 金币</td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr><td colspan="6" style="text-align:right;font-weight:600;font-size:15px;">共计：<span class="red j-rate-total">0</span>金币</td></tr>
                    </tfoot>
                </table>
                <p class="text-right" style="padding:0 8px;font-size:18px;">总花费：<span class="red j-sum-total">0</span>金币</p>
                <p style="font-weight: normal;" class="j-add-cart">
                    <span>可选项：访客加入购物车商家件数</span><a href="javascript:;" class="js-popover" data-toggle="popover" data-placement="bottom" data-content="访客可以根据商家推荐的范围设置加入购物车商品件数"><img src="/static/imgs/icon/notice.png" style="margin:0 4px;width:18px;" /></a>
                    <input type="number" name="cart-num[]" min="1" value="1" class="blue_input" style="width:84px;text-align:center" /><span style="padding-right:8px">—</span><input type="number" name="cart-num[]" min="1" value="1" class="blue_input" style="width:84px;text-align:center"/>件<small>（非必填，不填写平台将默认为加入购物车商品数量为一件）</small>
                </p>
            </div>
        </div>
       <!-- 新增下单提示-->
        <div class="white_box_ts">
            <h3>下单提示 <span class="red">注：买手接活动时可看见该提示，提示内容自由填写，如：商品在第*页*行、聊天时不要问发货地和哪家快递等。属可选项，限<em>255</em>字内。</span></h3>
            <textarea  class="message_text" maxlength="255" rows="5" placeholder=""><?php echo $trade_item->order_prompt; ?></textarea>
        </div>
        <!-- 填写商品信息end -->
    </div>
    <div class="next_box">
        <a href="/trade/prev/<?= $trade_info->id; ?>" class="previous_step">上一步</a>
        <a href="javascript:;" class="next_step">下一步</a>
    </div>
</div>
<?php $this->load->view("/common/footer"); ?>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/toast/toastr.min.js"></script>
<script src="/static/js/task_step.js"></script>
<script src="/static/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
    $(function(){
        var _trade_id = '<?= $trade_info->id; ?>';
        var _plat_name = '<?= $plat_name ?>';
        var _plat_id = '<?= $trade_info->plat_id; ?>';
        $(".js-popover").popover();
        $(".goods_pic_con img").click(function(){
            $(this).siblings("input").click();
        });
        $(".phone_goods_pic_con img").click(function(){
            $(this).siblings("input").click();
        });
        $(".phone_goods_pic_con2 img").click(function(){
            $(this).siblings("input").click();
        });

        // 所在地
        $("body").on("click",'ul.selected',function(e){
            e.stopPropagation();
            var $pr = $(this).parents('.sel-loc-box').find('.toselect');
            if($pr.is(':visible')){
                $pr.hide();
            }else{
                $pr.show();
            }
        });
        $("body").on("click",'.toselect a',function(e){
            e.stopPropagation();
            e.preventDefault();
            var set = $(this).parents('.sel-loc-box').find('ul.selected a');
            var _val = $(this).text();
            set.html($(this).text())
            set.attr('data-value',_val);
            $(this).parents('.sel-loc-box').find('.position').val(_val);
            $('div.toselect').hide();
        });
        $(document).click(function(){
            $('div.toselect').hide();
        });

        // 默认展示手机淘宝
        $(window).load(function (e) {
            $(".phone_search_box>label").find('input[name="phone_check"]').prop("checked", "checked");
            $(".phone_taobao_con").slideDown();
        });

        if($(".phone_search_box>label>input").is(":checked")){
            $(".phone_taobao_con").show();
        }

        $(".phone_search_box>label").click(function(){
            if( $(this).children("input").is(":checked") ){
                $(".phone_taobao_con").slideDown();
            }else{
                $(".phone_taobao_con").slideUp();
            }
        });

        // phone点击添加关键词
        $(".phone_keyword_add a").click(function(){
            var keyword_num = $(".phone_keyword_list_box").children(".phone_keyword_list").size() + 1;
            var str_html = '<div class="phone_keyword_list">' +
                '<div style="display: inline-block;width:100%"><div class="col-xs-4" style="padding-left:0">' +
                '<label>手机' + _plat_name + '关键字来源<em>' + keyword_num + '</em>：<a href="javascript:;" class="phone_keyword_list_del">删除</a></label>' +
                '<div class="keyword_search"><input type="text" name="app_keyword[]" class="blue_input" maxlength="50" style="width:256px" autocomplete="off"/><em class="color_red">（必填）</em></div>' +
                '</div><div class="col-xs-8"><label style="font-weight:500">首日访客人数：</label><div class="keyword_search"><input class="blue_input" type="text" name="first_day[]" value="" onblur="javascript:this.value=this.value.replace(/[^\\d]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d]/g,\'\')"/><small class="red">（请填写10的倍数）</small></div></div></div>' +
                '<div style="display:flex;margin-top:10px;">' +
                '<div class="phone_sort col-xs-2" style="padding-left:0">' +
                '    <p style="margin:5px 0">商品排序：</p>' +
                '    <select name="app_order_way" style="height:32px;" class="blue_input">' +
                '        <option value="综合排序">综合排序</option>' +
                '        <option value="销量优先">销量优先</option>' +
                '        <option value="价格从低到高">价格从低到高</option>' +
                '        <option value="价格从高到底">价格从高到底</option>' +
                '        <option value="信用排序">信用排序</option>' +
                '    </select>' +
                '</div>' +
                '<div class="price_range col-xs-4">' +
                '    <p>价格区间：</p>' +
                '    <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="">元&nbsp;- <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="">元' +
                '</div>' +
                '<div class="address col-xs-4">' +
                '    <p style="margin:5px 0">发货地：</p>' +
                '    <span class="ie7_areaHack_area">' +
                '        <div class="sel-loc-box">' +
                '            <div class="fake-select sel-loc">' +
                '                <ul class="selected">' +
                '                    <li>' +
                '                        <s class="sel_dropdown">' +
                '                            <s class="i">' +
                '                            </s>' +
                '                        </s>' +
                '                        <input type="hidden" name="app_area[]" class="position" value="全国">' +
                '                        <a href="javascript:;" data-value="全国" data-nogo="true">全国</a>' +
                '                    </li>' +
                '                </ul>' +
                '                <div style="display: none;" class="toselect">' +
                '                    <ul class="loc1"><li class="checked"><a _val="全国" href="javascript:;">全国</a></li></ul>' +
                '                    <ul class="loc2 split">' +
                '                        <li><a trace="location" _val="江苏,浙江,上海">江浙沪</a></li>' +
                '                        <li><a trace="location" _val="广州,深圳,中山,珠海,佛山,东莞,惠州">珠三角</a></li>' +
                '                        <li><a trace="location" _val="香港,澳门,台湾">港澳台</a></li>' +
                '                        <li><a trace="location" _val="美国,英国,法国,瑞士,澳洲,新西兰,加拿大,奥地利,韩国,日本,德国,意大利,西班牙,俄罗斯,泰国,印度,荷兰,新加坡,其它国家">海外</a></li>' +
                '                        <li><a trace="location">北京</a></li>' +
                '                        <li><a trace="location">上海</a></li>' +
                '                        <li><a trace="location">广州</a></li>' +
                '                        <li><a trace="location">深圳</a></li>' +
                '                        <li><a trace="location" _val="北京,天津">京津</a></li>' +
                '                    </ul>' +
                '                    <ul class="loc3">' +
                '                        <li><a trace="location">杭州</a></li>' +
                '                        <li><a trace="location">温州</a></li>' +
                '                        <li><a trace="location">宁波</a></li>' +
                '                        <li><a trace="location">南京</a></li>' +
                '                        <li><a trace="location">苏州</a></li>' +
                '                        <li><a trace="location">济南</a></li>' +
                '                        <li><a trace="location">青岛</a></li>' +
                '                        <li><a trace="location">大连</a></li>' +
                '                        <li><a trace="location">无锡</a></li>' +
                '                        <li><a trace="location">合肥</a></li>' +
                '                        <li><a trace="location">天津</a></li>' +
                '                        <li><a trace="location">长沙</a></li>' +
                '                        <li><a trace="location">武汉</a></li>' +
                '                        <li><a trace="location">石家庄</a></li>' +
                '                        <li><a trace="location">郑州</a></li>' +
                '                        <li><a trace="location">成都</a></li>' +
                '                        <li><a trace="location">重庆</a></li>' +
                '                        <li><a trace="location">西安</a></li>' +
                '                        <li><a trace="location">昆明</a></li>' +
                '                        <li><a trace="location">南宁</a></li>' +
                '                        <li><a trace="location">福州</a></li>' +
                '                        <li><a trace="location">厦门</a></li>' +
                '                        <li><a trace="location">南昌</a></li>' +
                '                        <li><a trace="location">东莞</a></li>' +
                '                        <li><a trace="location">沈阳</a></li>' +
                '                        <li><a trace="location">长春</a></li>' +
                '                        <li><a trace="location">哈尔滨</a></li>' +
                '                    </ul>' +
                '                    <ul class="loc4 split">' +
                '                        <li><a trace="location">河北</a></li>' +
                '                        <li><a trace="location">河南</a></li>' +
                '                        <li><a trace="location">湖北</a></li>' +
                '                        <li><a trace="location">湖南</a></li>' +
                '                        <li><a trace="location">福建</a></li>' +
                '                        <li><a trace="location">江苏</a></li>' +
                '                        <li><a trace="location">江西</a></li>' +
                '                        <li><a trace="location">广东</a></li>' +
                '                        <li><a trace="location">广西</a></li>' +
                '                        <li><a trace="location">海南</a></li>' +
                '                        <li><a trace="location">浙江</a></li>' +
                '                        <li><a trace="location">安徽</a></li>' +
                '                        <li><a trace="location">吉林</a></li>' +
                '                        <li><a trace="location">辽宁</a></li>' +
                '                        <li><a trace="location">黑龙江</a></li>' +
                '                        <li><a trace="location">山东</a></li>' +
                '                        <li><a trace="location">山西</a></li>' +
                '                        <li><a trace="location">陕西</a></li>' +
                '                        <li><a trace="location">新疆</a></li>' +
                '                        <li><a trace="location">云南</a></li>' +
                '                        <li><a trace="location">贵州</a></li>' +
                '                        <li><a trace="location">四川</a></li>' +
                '                        <li><a trace="location">甘肃</a></li>' +
                '                        <li><a trace="location">宁夏</a></li>' +
                '                        <li><a trace="location">西藏</a></li>' +
                '                        <li><a trace="location">香港</a></li>' +
                '                        <li><a trace="location">澳门</a></li>' +
                '                        <li><a trace="location">台湾</a></li>' +
                '                        <li><a trace="location">内蒙古</a></li>' +
                '                        <li><a trace="location">青海</a></li>' +
                '                    </ul>' +
                '                </div>' +
                '            </div>' +
                '        </div>' +
                '    </span>' +
                '</div>' +

                '</div>' +
                '<div class="service">' +
                '<p>折扣和服务：</p>' +
                '<p>' +
                '    <label><input class="app_discount" type="checkbox" value="包邮">包邮</label>' +
                '    <label><input class="app_discount" type="checkbox" value="天猫">天猫</label>' +
                '    <label><input class="app_discount" type="checkbox" value="全球购">全球购</label>' +
                '    <label><input class="app_discount" type="checkbox" value="消费者保障">消费者保障</label>' +
                '    <label><input class="app_discount" type="checkbox" value="手机专享">手机专享</label>' +
                '    <label><input class="app_discount" type="checkbox" value="淘金币抵钱">淘金币抵钱</label>' +
                '    <label><input class="app_discount" type="checkbox" value="货到付款">货到付款</label>' +
                '    <label><input class="app_discount" type="checkbox" value="7+天退换货">7+天退换货</label>' +
                '    <label><input class="app_discount" type="checkbox" value="促销">促销</label>' +
                '    <label><input class="app_discount" type="checkbox" value="花呗分期">花呗分期</label>' +
                '    <label><input class="app_discount" type="checkbox" value="天猫超市">天猫超市</label>' +
                '    <label><input class="app_discount" type="checkbox" value="天猫国际">天猫国际</label>' +
                '    <label><input class="app_discount" type="checkbox" value="通用排序">通用排序</label>' +
                '    <input type="hidden" name="app_discount_text[]">' +
                '</p>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-xs-12 classification">' +
                '    <p>商品分类：</p>' +
                '    <input class="blue_input" type="text" name="goods_cate[]" value="">' +
                '</div>' +
                '</div>' +
                '</div>';
            $(".phone_keyword_list_box").append(str_html);
            if (keyword_num > 4) {
                $(this).parent(".phone_keyword_add").hide();
            }
            // 增加关键字分布
            var _table = $('.js-exeu-distribution').find('table'),
                _th_len = _table.find('thead').find('th').length,
                _th_disabled_len = _table.find('thead').find('th.disabled').length;
            var _str_html = '<tr><td><nobr>关键字：<span class="j-key-words red"></span></nobr></td>';
            for (var i = 1; i < _th_len; i++) {
                if (i >= _th_len - _th_disabled_len) {
                    _str_html += '<td class="disabled"><input type="number" class="form-controller" placeholder="访客人数" disabled step="10"></td>';
                } else {
                    _str_html += '<td><input type="number" class="form-controller" placeholder="访客人数" step="10"/></td>';
                }
            }
            _str_html += '</tr>';
            _table.find('tbody').append(_str_html);
        });
        // phone点击删除关键词
        $("body").on("click", ".phone_keyword_list_del", function () {
            var $this = $(this), _idex = $this.parent().find('em').text();
            $this.parents(".phone_keyword_list").remove();
            $(".phone_keyword_list_box").children(".phone_keyword_list").each(function (idx, obj) {
                $(obj).find('label>em').text(idx + 1);
            });
            $(".phone_keyword_add").show();
            // 删除关键字分布
            _idex = parseInt(_idex) - 1;
            $('.js-exeu-distribution').find('table>tbody').find('tr:eq('+ _idex +')').remove();
        });
        // 填写商品信息总提交点击
        $("body").on("click", ".next_step", function () {
            /* 关于商品相关内容验证start */
            var goods_url = $(".goods_url").children("input").val();
            var _check_url = check_goods_url(goods_url, _plat_id);
            if (_check_url.get('error') != 0) {
                toastr.warning(_check_url.get('message'));
                return false;
            }
            var goods_title = $(".goods_title").children("input").val();
            if (goods_title == '') {
                if ($(".goods_title").find(".red").length == 0) {
                    $(".goods_title").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请填写商品标题</span>");
                    $(".goods_title").addClass("redBorder");
                }
                toastr.warning("商品名称不能为空");
                return false;
            }
            if (!$('input[name="phone_check"]').is(":checked")) {
                toastr.warning('请选择使用"' + _plat_name + '搜索框"查找商品或者使用"手机' + _plat_name + '"查找商品');
                return false;
            }
            /* 关于商品相关内容验证end */

            /* 选择活动类型 */
            if ($(".phone_search_box>label>input").is(":checked")) {
                if ($(".phone_goods_pic_con input").attr("uploaded") == '') {
                    toastr.warning("请选择上传商品主图");
                    return false;
                }
                // 关键词验证
                var keyword_err = false, first_day_err = false;
                $(".phone_keyword_list").each(function () {
                    if ($(this).find(".keyword_search").find('input[name="app_keyword[]"]').val() == '') {
                        $(this).find(".keyword_search").find('input[name="app_keyword[]"]').css('border-color', 'red');
                        keyword_err = true;
                    }
                    if ($(this).find(".keyword_search").find('input[name="first_day[]"]').val() == ''){
                        $(this).find(".keyword_search").find('input[name="first_day[]"]').css('border-color', 'red');
                        first_day_err = true;
                    }
                });
                if (keyword_err) {
                    toastr.warning("请填写搜索关键词");
                    return false;
                }
                if (first_day_err) {
                    toastr.warning("请设置首日访客人数");
                    return false;
                }
            }
            // 第一步提交信息成功后
            var _app_discount_texts = $('input[name="app_discount_text[]"]');
            _app_discount_texts.each(function () {
                var _tmps = $(this).parent().find('.app_discount:checked');
                var _tmp_val = "";
                _tmps.each(function () {
                    _tmp_val = _tmp_val + $(this).val() + ",";
                });
                $(this).val(_tmp_val);
            });
            // 检查任务单分布设置
            var _dist_box = $('.order_distribution_box');
            var _start_time_date = _dist_box.find('.js-exec-days').find('input[name="start_time_date"]').val(),
                _start_time_time = _dist_box.find('.js-exec-days').find('input[name="start_time_time"]').val(),
                _start_time = _start_time_date + ' ' + _start_time_time;
            if (_start_time_date == '' || _start_time_time == '') {
                toastr.warning("请设置任务开始时间");
                return false;
            }
            var _days_obj = _dist_box.find('.js-exec-days').find('input[type="checkbox"]:checked');
            if (_days_obj.length != 1) {
                toastr.warning("请设置任务单执行天数");
                return false;
            }
            var _dist_err = false, _compare_day_err = false, _day_quantity_err = false, _dist_days_list = {};
            _dist_box.find('.js-exeu-distribution').find('table>tbody').find('tr').each(function (i, obj) {
                var _day_list = [], _last_day_quantity = 0;
                $(obj).find('input[type="number"]').each(function (idx, ipt) {
                    if (!$(ipt).is(':disabled')) {
                        _day_list.push($(ipt).val());
                        if (parseInt($(ipt).val()) < parseInt($(ipt).attr('min'))) {
                            $(ipt).css('border-color', 'red');
                            _dist_err = true ;
                        }
                        if (_last_day_quantity > parseInt($(ipt).val())) {
                            $(ipt).css('border-color', 'red');
                            _compare_day_err = true ;
                        } else {
                            _last_day_quantity = parseInt($(ipt).val());
                        }
                    }
                });
                _dist_days_list[i] = _day_list;
                if (_last_day_quantity <= 0){
                    _day_quantity_err = true;
                }
            });
            if (_dist_err) {
                toastr.warning('每日访客人数需填写10的倍数，且数字不能小于前一天的访客人数');
                return false;
            }
            if (_compare_day_err) {
                toastr.warning('每日访客递增比例，应比前一天访客数量上增加');
                return false;
            }
            if (_day_quantity_err) {
                toastr.warning('请设置关键词对应的每日访客数');
                return false;
            }
            // 分布比例
            var _rate_list = [];
            $('.distribution_rate_box').find('table>tbody').find('tr').each(function (idx, obj) {
                var _type = $(obj).data('type'), _rate = $(obj).find('.j-rate').val();
                var _rate_obj = {type: _type, rate: _rate};
                _rate_list.push(_rate_obj);
            });

            // 商品设置参数
            var guige_color = $('input[name="guige_color"]').val();
            var guige_size = $('input[name="guige_size"]').val();
            var show_price = $(".price_search").find('input').val();
            var phone_taobao = $('input[name="phone_check"]:checked').val();
            var app_kwd = rtn_array($('input[name="app_keyword[]"]'));
            var app_low_price = rtn_array($('input[name="app_price_start[]"]'));
            var app_high_price = rtn_array($('input[name="app_price_end[]"]'));
            var app_discount_text = rtn_array($('input[name="app_discount_text[]"]'));
            var app_area = rtn_array($('input[name="app_area[]"]'));
            var goods_cate = rtn_array($('input[name="goods_cate[]"]'));
            var app_order_way = rtn_array($('select[name="app_order_way"]'));
            var app_img1_base64 = $('.phone_goods_pic_con').find('input[name="goods_pic"]').attr('base64');
            var app_img2_base64 = $('.phone_goods_pic_con2').find('input[name="goods_pic"]').attr('base64');
            var cart_nums = rtn_array($('.j-add-cart').find('input[name="cart-num[]"]'));
            var order_prompt = $('.white_box_ts .message_text').val();

            // 任务单分布设置参数
            $.ajax({
                type: "POST",
                url: "/trade/traffic_step2_submit/" + _trade_id,
                data: {
                    "goods_url": goods_url,
                    "goods_name": goods_title,
                    "color": guige_color,
                    "size": guige_size,
                    "show_price": show_price,
                    "phone_taobao": phone_taobao,
                    "app_kwd": app_kwd,
                    "app_low_price": app_low_price,
                    "app_high_price": app_high_price,
                    "app_discount_text": app_discount_text,
                    "app_area": app_area,
                    "goods_cate": goods_cate,
                    "app_order_way": app_order_way,
                    "app_img1_base64": app_img1_base64,
                    "app_img2_base64": app_img2_base64,
                    "start_time": _start_time,
                    "days_list": _dist_days_list,
                    "rate_list": _rate_list,
                    "cart_nums": cart_nums,
                    "order_prompt": order_prompt
                },
                dataType: "json",
                success: function (res) {
                    if (res.code != '0') {
                        toastr.error(res.msg);
                    } else {
                        location.href = "/trade/step/" + _trade_id;
                    }
                }
            });
        });

        // 根据商品URL抓取商品信息
        $('.goods_url').on('change', 'input', function (e) {
            var $this = $(this), _goods_url = $this.val();
            // 校验录入
            var result = check_goods_url(_goods_url, _plat_id);
            if (result.get('error') != 0) {
                toastr.warning(result.get('message'));
                return false;
            }

            $.post('/ajax/get_goods_info', {url: _goods_url, plat: _plat_id}, function (data) {
                if (data.error == 0) {
                    $('.goods_title input').val(data.info.title);
                    $.ajax({
                        type: "POST",
                        url: "/ajax/get_img_upload",
                        dataType: "json",
                        success: function (res) {
                            var _token = res.code;
                            // 将图片上传返回URL
                            getBase64(data.info.img).then(function (base64) {
                                var type = base64.substring(0, base64.indexOf(';')).replace('data:', '');
                                var pic = base64.substring(base64.indexOf(',') + 1);
                                var url = "http://up-z1.qiniup.com/putb64/-1/";
                                var xhr = new XMLHttpRequest();
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState == 4) {
                                        var _res_json = eval('(' + xhr.responseText + ')');
                                        if (_res_json.key) {
                                           var _img_url = '<?php echo CDN_URL; ?>/' + _res_json.key;
                                            var _goods_pic_div = $('.phone_search_box').find('.phone_goods_pic_con');
                                            _goods_pic_div.find('input[name="goods_pic"]').attr('uploaded', 1).attr('base64', _img_url);
                                            _goods_pic_div.find('img').attr('src', _img_url);
                                        } else {
                                            toastr.error('图片上传失败，请重新上传');
                                        }
                                    }
                                };
                                xhr.open("POST", url, true);
                                xhr.setRequestHeader("Content-Type", type);
                                xhr.setRequestHeader("Authorization", "UpToken " + _token);
                                xhr.send(pic);
                            }, function (err) {
                                console.log(err);
                            });
                        }
                    });
                }
            }, 'json');
        });

        // 分布设置
        $('.order_distribution_box').on('click', '.exec_days', function (e) {
            var $this = $(this), _days = parseInt($this.val());
            if ($this.is(':checked')) {
                $this.parent().siblings().each(function () {
                    $(this).find('input').prop('checked', false);
                });

                // 处理表格展示
                var _table = $('.js-exeu-distribution').find('table'),
                    _th_len = _table.find('thead').find('th').length,
                    _start_time_date = _table.find('thead').find('th:last-child').attr('data-datetime');
                if (_th_len <= 7) {
                    if (_days <= 6) {
                        for (var i = 0; i < _th_len; i++) {
                            if (i < _days) {
                                // 可编辑
                                _table.find('thead').find('th').eq(i + 1).removeClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).removeClass('disabled').find('input').prop('disabled', false);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).removeClass('disabled');
                            } else {
                                // 不可编辑
                                _table.find('thead').find('th').eq(i + 1).addClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).addClass('disabled').find('input').prop('disabled', true);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).addClass('disabled');
                            }
                        }
                    } else {
                        for (var i = 0; i < _days; i++) {
                            if (i >= _th_len - 1) {
                                _start_time_date = get_next_day_date(_start_time_date);
                                _table.find('thead>tr').append('<th data-datetime="' + _start_time_date + '">' + get_local_format(_start_time_date) + '</th>');
                                _table.find('tbody>tr').each(function () {
                                    $(this).append('<td><input type="number" class="form-controller" placeholder="访客人数" min="0" step="10"/></td>');
                                });
                                _table.find('tfoot>tr:eq(0)').append('<td><nobr>当日访客：<span class="j-p-total red">0</span>人</nobr></td>');
                            } else {
                                _table.find('thead').find('th').eq(i + 1).removeClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).removeClass('disabled').find('input').prop('disabled', false);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).removeClass('disabled');
                            }
                        }
                    }
                } else {
                    if (_days <= 7) {
                        for (var i = 0; i < _th_len; i++) {
                            if (i < _days) {
                                // 可编辑
                                _table.find('thead').find('th').eq(i + 1).removeClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).removeClass('disabled').find('input').prop('disabled', false);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).removeClass('disabled');
                            } else if(i >= 7) {
                                _table.find('thead').find('th').eq(7).remove();
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(7).remove();
                                });
                                _table.find('tfoot').find('td').eq(7).remove();
                            } else {
                                // 不可编辑
                                _table.find('thead').find('th').eq(i + 1).addClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).addClass('disabled').find('input').prop('disabled', true);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).addClass('disabled');
                            }
                        }
                    } else if (_days > 7 && _days < _th_len) {
                        for (var i = 0; i < _th_len; i++) {
                            if (i < _days) {
                                // 可编辑
                                _table.find('thead').find('th').eq(i + 1).removeClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).removeClass('disabled').find('input').prop('disabled', false);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).removeClass('disabled');
                            } else {
                                _table.find('thead').find('th').eq(_days + 1).remove();
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(_days + 1).remove();
                                });
                                _table.find('tfoot').find('td').eq(_days + 1).remove();
                            }
                        }
                    } else {
                        for (var i = 0; i < _days; i++) {
                            if (i >= _th_len - 1) {
                                _start_time_date = get_next_day_date(_start_time_date);
                                _table.find('thead>tr').append('<th data-datetime="' + _start_time_date + '">' + get_local_format(_start_time_date) + '</th>');
                                _table.find('tbody>tr').each(function () {
                                    $(this).append('<td><input type="number" class="form-controller" placeholder="访客人数" min="0" step="10"/></td>');
                                });
                                _table.find('tfoot>tr:eq(0)').append('<td><nobr>当日访客：<span class="j-p-total red">0</span>人</nobr></td>');
                            } else {
                                _table.find('thead').find('th').eq(i + 1).removeClass('disabled');
                                _table.find('tbody').find('tr').each(function () {
                                    $(this).find('td').eq(i + 1).removeClass('disabled').find('input').prop('disabled', false);
                                });
                                _table.find('tfoot>tr:eq(0)').find('td').eq(i + 1).removeClass('disabled');
                            }
                        }

                    }
                }
                // 费用计算天数设置
                $('.distribution_rate_box').find('table>tbody').find('.j-rate-days').text(_days);
                service_fee();
            } else {
                $this.prop('checked', true);
            }
        });
        // 关键词填写
        $('.phone_keyword_list_box').on('change', '.keyword_search>input[name="app_keyword[]"]', function () {
            var $this = $(this), _key_words = $this.val();
            var _key_idx = $this.parent().prev().find('em').text();
            var _idx = parseInt(_key_idx) - 1 ;
            $this.css('border-color', '#bddffd');
            $('.js-exeu-distribution').find('table>tbody').find('tr:eq('+ _idx +')').find('.j-key-words').text(_key_words);
        }).on('change', '.keyword_search>input[name="first_day[]"]', function () {
            var $this = $(this), _first_day = $this.val();
            var _key_idx = $this.parent().parent().prev().find('label>em').text();
            var _idx = parseInt(_key_idx) - 1 ;
            $this.css('border-color', '#bddffd');
            if (_first_day % 10 != 0 || _first_day < 0){
                toastr.warning('访客人数请填写大于0、且是10的倍数的整数');
                return false;
            } else {
                $('.js-exeu-distribution').find('table>tbody').find('tr:eq(' + _idx + ')').find('td>input').each(function (idx, obj) {
                    $(obj).attr('min', _first_day).val(_first_day);
                });
                service_fee();
            }
        });
        $('.js-exeu-distribution').on('change', 'input[type="number"]', function () {
            var _days = $(this).val();
            if (_days % 10 != 0 || _days < 0) {
                toastr.warning('访客人数请填写大于0、且是10的倍数的整数');
                return false;
            } else {
                $(this).css('border-color', '#dadada');
                service_fee();
            }
        });
        $('.distribution_rate_box').on('change', '.j-rate', function () {
            service_fee();
        });
    });
    // 对象转数组
    function rtn_array(obj) {
        var tmpArr = new Array();
        obj.each(function () {
            tmpArr.push($(this).val());
        });
        return tmpArr;
    }

    function fun_selected_start() {
        var _frmae = $('.order_distribution_box'),
            _start_time_date = _frmae.find('input[name="start_time_date"]').val();
        if (_start_time_date == ''){
            return false ;
        }
        // 设置TABLE抬头
        $('.js-exeu-distribution').find('table>thead').find('th').each(function (i, obj) {
            if (i > 0) {
                $(obj).attr('data-datetime', _start_time_date).text(get_local_format(_start_time_date));
                _start_time_date = get_next_day_date(_start_time_date);
            }
        });
    }

    function get_next_day_date(date) {
        var _date_time_obj = new Date(date);
        _date_time_obj = _date_time_obj.setDate(_date_time_obj.getDate() + 1);
        _date_time_obj = new Date(_date_time_obj);
        var year = _date_time_obj.getFullYear(), month = _date_time_obj.getMonth() + 1, day = _date_time_obj.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        return year + '-' + month + '-' + day;
    }
    function get_local_format(date) {
        var _date_time_obj = new Date(date);
        var _month = _date_time_obj.getMonth() + 1, _day = _date_time_obj.getDate();
        return _month + '月' + _day + '日';
    }
    // 统计页面上费用
    var _j_normal_price = parseFloat('<?= $normal_price; ?>');
    service_fee();      // 运算页面赋值
    function service_fee() {
        var _frame = $('.js-exeu-distribution');
        var _days = parseInt($('.js-exec-days').find('input[name="exec_days"]:checked').val());
        var _day_list = new Array(_days);
        _day_list.fill(0);
        _frame.find('table>tbody').find('tr').each(function () {
            $(this).find('td input').each(function (idx, obj) {
                if (!$(obj).parent().hasClass('disabled')) {
                    var _val = parseInt($(obj).val());
                    if (!isNaN(_val)) {
                        _day_list[idx] += _val;
                    }
                }
            })
        });
        // 填充底部小计
        var _dist_table_tfoot = _frame.find('table>tfoot');
        _dist_table_tfoot.find('tr:eq(0)').find('.j-p-total').each(function (idx, obj) {
            if (idx < _days) {
                $(obj).text(_day_list[idx]);
            }
        });
        var _total_person = eval(_day_list.join("+"));
        var _j_amount = parseFloat(_total_person * _j_normal_price).toFixed(1);
        _dist_table_tfoot.find('tr:eq(1)').find('.j-total').text(_total_person);
        _dist_table_tfoot.find('tr:eq(1)').find('.j-amount').text(_j_amount);
        // 计算占比TABEL数据
        var _rate_table = $('.distribution_rate_box').find('table'), _j_rate_total = 0;
        _rate_table.find('tbody>tr').each(function (idx, obj) {
            var _price = parseFloat($(obj).data('price')), _rate = parseFloat($(obj).find('.j-rate').val());
            var _p_nums = Math.round(parseFloat(_total_person * _rate)), _p_total = parseFloat(_p_nums * _price);
            $(obj).find('.j-rate-nums').text(_p_nums);
            $(obj).find('.j-rate-total').text(_p_total.toFixed(1));
            _j_rate_total += _p_total ;
        });
        _rate_table.find('tfoot').find('.j-rate-total').text(_j_rate_total.toFixed(1));
        $('.distribution_rate_box').find('.j-sum-total').text((parseFloat(_j_rate_total) + parseFloat(_j_amount)).toFixed(1));
    }
    // 图片上传
    function setImagePreview(obj) {
        $.ajax({
            type: "POST",
            url: "/ajax/get_img_upload",
            dataType: "json",
            success: function (res) {
                var _token = res.code;
                var $this = $(obj);
                var _file = obj.files[0];
                var reader = new FileReader();
                var rs = reader.readAsDataURL(_file);
                reader.onload = function (e) {
                    var _base64 = e.target.result;
                    var type = _base64.substring(0, _base64.indexOf(';')).replace('data:', '');
                    var pic = _base64.substring(_base64.indexOf(',') + 1);
                    var url = "http://up-z1.qiniup.com/putb64/-1/";
                    var xhr = new XMLHttpRequest();
                    console.log(xhr.readyState)
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4) {
                            var _res_json = eval('(' + xhr.responseText + ')');
                            if (_res_json.key) {
                                var _img_url = '<?php echo CDN_URL; ?>/' + _res_json.key;
                                $(obj).parent().find('img').attr('src', _img_url);
                                $(obj).attr('uploaded', '1').attr('base64', _img_url);
                            } else {
                                toastr.error('图片上传失败，请重新上传');
                            }
                        }
                    };
                    xhr.open("POST", url, true);
                    xhr.setRequestHeader("Content-Type", type);
                    xhr.setRequestHeader("Authorization", "UpToken " + _token);
                    xhr.send(pic);
                };
            }
        });
    }

</script>
</body>
</html>