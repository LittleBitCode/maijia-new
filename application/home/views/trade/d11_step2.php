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
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/toast/toastr.min.js"></script>
<title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
    <style>
        .activeDoule11{margin-bottom:20px;}
        .activeDoule11 .active_url{display: inline-block;  width: 410px;  height: 30px;  border: 1px solid #bddffd;  background-color: #fbfdff;  border-radius: 2px;  padding-left: 10px;  line-height: 30px;}
        .meetingplace{display: inline-block;  width: 410px;  height: 30px;  border: 1px solid #bddffd;  background-color: #fbfdff;  border-radius: 2px; padding-left: 10px;  line-height: 30px;}
        .pc_keyword_add a {  color: #1491fc;  font-weight: bold;  }
        .pc_keyword_list {  border-bottom: 1px dashed #E0E0E0;  padding: 15px 0;  }
    </style>
</head>
<body>
    <?php $this->load->view("/common/top", ['site' => 'trade']); ?>
    <div class="com_title">商家报名活动</div>
    <!-- 发活动顶部活动步骤进度start -->
    <div class="trade_top contain">
        <div class="Process">
            <ul class="clearfix">
                <li style="width:20%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
                <li style="width:20%"><em class="Processyes">2</em><span>填写商品信息</span></li>
                <li style="width:20%"><em>3</em><span>选择活动数量</span></li>          
                <li style="width:20%"><em>4</em><span>选增值服务</span></li>
                <li style="width:20%"><em>5</em><span>支付</span></li>
                <li style="width:20%" class="Processlast"><em>6</em><span>发布成功</span></li>
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
                                <input type="text" name="goods_url" value="<?php echo $trade_item->goods_url; ?>" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="goods_title">
                                <p><label><span class="color_red">* </span>商品标题：</label></p>
                                <input type="text" name="goods_title" value="<?php echo $trade_item->goods_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
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
                    <div class="row price_num">
                        <?php if (!in_array($trade_info->trade_type, ['111', '112', '211', '212'])): ?>
                        <div class="col-xs-3">
                            <div class="price">
                                <p><label><span class="color_red">* </span>单品售价：</label></p>
                                <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" value="<?php echo $trade_item->price; ?>" style="width: 175px;" autocomplete="off" disableautocomplete />元<span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-xs-9">
                            <div class="number">
                                <p><label><span class="color_red">* </span>每单拍：</label></p>
                                <input type="number" value="1" min="1" onblur="javascript:this.value=this.value.replace(/[^\d]/g,'');if(this.value<1){this.value=1};" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?php echo $trade_item->buy_num; ?>" style="text-align: center;" autocomplete="off" disableautocomplete />件
                                <i>(考虑安全问题，建议每单不要超过2件)</i><span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                    </div>
                    <div class="row price_search">
                        <div class="col-xs-12">
                            <p><label>搜索页面展示价格：</label></p>
                            <input type="text" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $trade_item->show_price; ?>" style="text-align: center;" />
                            <em>如该商品有满减、促销、多规格等情况，请填写此金额</em>
                            <b>下单总金额<span class="price_total color_red"><?php echo $trade_item->price*$trade_item->buy_num; ?></span>元</b>
                        </div>
                    </div>

                    <?php if (in_array($trade_info->trade_type, ['111', '112', '211', '212'])): ?>
                    <div class="row price_num">
                        <div class="col-xs-3">
                            <div class="price">
                                <p><label><span class="color_red">* </span>商品定金：</label></p>
                                <input name="front_money" type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" value="<?php echo $trade_double11->front_money; ?>" style="width: 175px;" autocomplete="off" disableautocomplete />元<span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                        <div class="col-xs-9">
                            <?php if (in_array($trade_info->trade_type, ['112', '212'])): ?>
                            <div class="final_payment">
                                <p><label><span class="color_red">* </span>商品尾款：</label></p>
                                <input name="final_payment" type="text"  onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" value="<?php echo $trade_double11->final_payment; ?>" style="text-align: center;" autocomplete="off" disableautocomplete />元<span class="color_red">(必填)</span><br>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="prompt">如商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</div>
                </div>
                <div class="search_type_box white_box">
                    <h1>如何找到您的商品：</h1>

                    <?php if (in_array($trade_info->trade_type, ['111', '112', '211', '212'])): ?>
                    <!-- 手机搜索start -->
                    <div class="phone_search_box">
                        <label>
                            <input type="checkbox" name="phone_check" <?php if ($trade_double11->source == '1'): ?>checked<?php endif; ?> />手机<?= $plat_name ?>活动<span>（用户"手机<?= $plat_name ?>APP"搜索下单）</span>
                        </label>
                        <div class="phone_taobao_con" <?php if ($trade_double11->source == '1'): ?>style="display:block;"<?php endif; ?>>
                            <div class="phone_taobao_pic row">
                                <div class="col-xs-5">
                                    <div class="phone_goods_pic_con pull-left">
                                        <?php if ($app_search[0]->search_img): ?>
                                        <img src="<?= $app_search[0]->search_img; ?>" height="130" width="130" id="goods_pic2" title="点击更换商品主图" />
                                        <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic2" title="点击上传商品主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="2" onChange="javascript:setImagePreview2(this);" uploaded="<?php echo $app_search[0]->search_img; ?>" base64="" />
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
                                        <input type="file" name="goods_pic" id="3" onChange="javascript:setImagePreview3(this);" uploaded="<?php echo $app_search[0]->search_img2; ?>" base64="" />
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
                                    <label><span class="color_red">*</span>手机<?= $plat_name ?>关键字来源<em><?php echo $k+1; ?></em>：
                                    <?php if ($k > 0): ?>
                                    <a href="javascript:;" class="phone_keyword_list_del">删除</a></label>
                                    <?php endif; ?>
                                    </label>
                                    <div class="keyword_search">
                                        <input class="blue_input" type="text" name="app_keyword[]" value="<?php echo $v->kwd; ?>" maxlength="50"/>&nbsp;&nbsp;
                                    </div>
                                    <div class="price_range">
                                        <p>价格区间：</p>
                                        <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $v->low_price; ?>" />元&nbsp;- <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $v->high_price; ?>" />元
                                    </div>
                                    <div class="service">
                                        <p>折扣和服务：</p>
                                        <p>
                                        <?php if($trade_info->plat_id == '4'): ?>
                                            <label><input class="app_discount" type="checkbox" value="京东物流" <?php if (in_array('京东物流', $v->discount_arr)): ?>checked<?php endif; ?> />京东物流</label>
                                            <label><input class="app_discount" type="checkbox" value="自营211" <?php if (in_array('自营211', $v->discount_arr)): ?>checked<?php endif; ?> />自营211</label>
                                            <label><input class="app_discount" type="checkbox" value="货到付款" <?php if (in_array('货到付款', $v->discount_arr)): ?>checked<?php endif; ?> />货到付款</label>
                                            <label><input class="app_discount" type="checkbox" value="仅看有货" <?php if (in_array('仅看有货', $v->discount_arr)): ?>checked<?php endif; ?> />仅看有货</label>
                                            <label><input class="app_discount" type="checkbox" value="全球购" <?php if (in_array('全球购', $v->discount_arr)): ?>checked<?php endif; ?> />全球购</label>
                                            <label><input class="app_discount" type="checkbox" value="配送全球" <?php if (in_array('配送全球', $v->discount_arr)): ?>checked<?php endif; ?> />配送全球</label>
                                            <label><input class="app_discount" type="checkbox" value="PLUS专享价" <?php if (in_array('PLUS专享价', $v->discount_arr)): ?>checked<?php endif; ?> />PLUS专享价</label>
                                            <label><input class="app_discount" type="checkbox" value="新品" <?php if (in_array('新品', $v->discount_arr)): ?>checked<?php endif; ?> />新品</label>
                                            <label><input class="app_discount" type="checkbox" value="促销" <?php if (in_array('促销', $v->discount_arr)): ?>checked<?php endif; ?> />促销</label>
                                        <?php else: ?>
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
                                        <?php endif; ?>
                                            <input type="hidden" name="app_discount_text[]" />
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="address">
                                                <p>发货地：</p>
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
                                        <div class="col-xs-9 classification">
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
                            <div class="narrow_search">
                                <div class="narrow_t">缩小搜索范围：</div>
                                <div class="phone_sort">
                                    <p>商品排序：</p>
                                    <select name="app_order_way" style="height:32px;" class="blue_input">
                                        <option value="综合排序" <?php if ($app_search[0]->order_way == '综合排序'): ?>selected<?php endif; ?>>综合排序</option>
                                        <option value="销量优先" <?php if ($app_search[0]->order_way == '销量优先'): ?>selected<?php endif; ?>>销量优先</option>
                                        <option value="价格从低到高" <?php if ($app_search[0]->order_way == '价格从低到高'): ?>selected<?php endif; ?>>价格从低到高</option>
                                        <option value="价格从高到底" <?php if ($app_search[0]->order_way == '价格从高到底'): ?>selected<?php endif; ?>>价格从高到底</option>
                                        <option value="信用排序" <?php if ($app_search[0]->order_way == '信用排序'): ?>selected<?php endif; ?>>信用排序</option>
                                    </select>
                                </div>
                                <!-- 预计后台要添加
                                <div>
                                    <p>商品现有付款人数：</p>
                                    <input type="text" class="blue_input" /> 人
                                    <span class="text-grey"> 此处为手机淘宝销量优先搜索列表页显示的付款人数，新店无收货人请填1</span>
                                </div> -->
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- 手机搜索end -->
                    <?php endif; ?>
                    
                <div class="pc_search_box" style="display: none">
                    <label>
                        <input type="checkbox" name="hc_check" <?php if ($trade_double11->source == '2'): ?>checked<?php endif; ?>>双12活动<span>（用户"双12会场关键词"搜索下单）</span>
                    </label>
                    <div class="pc_taobao_con" <?php if ($trade_double11->source == '2'): ?>style="display: block;"<?php endif; ?>>
                        <div class="row activeDoule11">
                            <div class="col-xs-12"><span class="color_red">*</span>请填写双12会场商品链接：<input type="text" class="active_url" name="activity_url" value="<?php echo $trade_double11->activity_url; ?>" autocomplete="off" disableautocomplete=""><span class="color_red">(必填)</span></div>
                            <div class="col-xs-12" style="margin-top:10px;">请填写会场分类：<input type="text" class="meetingplace" name="activity_cate" value="<?php echo $trade_double11->activity_cate; ?>" autocomplete="off" disableautocomplete=""></div>
                        </div>
                        <div class="phone_taobao_pic row" style="border-bottom:1px dashed #e0e0e0;padding-bottom:20px;">
                            <div class="col-xs-5">
                                <div class="phone_goods_pic_con pull-left">
                                    <?php if ($trade_double11->activity_img): ?>
                                    <img src="<?= $trade_double11->activity_img; ?>" height="130" width="130" id="goods_pic4" title="点击更换商品主图" />
                                    <?php else: ?>
                                    <img src="/static/imgs/trade/goods_pic.png" id="goods_pic4" title="点击上传会场主图" />
                                    <?php endif; ?>
                                    <input type="file" name="goods_pic" id="4" onchange="javascript:setImagePreview4(this);" uploaded="" base64="">
                                </div>
                                <div class="pull-left goods_pic_con">
                                    <h5><span class="color_red">*</span>双12会场主图</h5>
                                    <p>图片尺寸：1200×1200以内</p>
                                    <p>图片大小：不能大于2M</p>
                                    <p>图片格式：jpg、png、gif</p>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <div class="phone_goods_pic_con2 pull-left">
                                    <?php if ($trade_double11->goods_img): ?>
                                    <img src="<?php echo $trade_double11->goods_img; ?>" id="goods_pic5" title="点击上传商品主图">
                                    <?php else: ?>
                                    <img src="/static/imgs/trade/goods_pic.png" id="goods_pic5" title="点击上传商品主图">
                                    <?php endif; ?>
                                    <input type="file" name="goods_pic" id="5" onchange="javascript:setImagePreview5(this);" uploaded="" base64="">
                                </div>
                                <div class="pull-left goods_pic_con">
                                    <h5><span class="color_red">*</span>商品主图</h5>
                                    <p>图片尺寸：1200×1200以内</p>
                                    <p>图片大小：不能大于2M</p>
                                    <p>图片格式：jpg、png、gif</p>
                                </div>
                            </div>
                        </div>
                        <div class="careful"><span>使用关键词查找商品（买手通过会场进入店铺后，通过您提供的关键词在您的店铺内搜索商品）</span></div>
                        <div class="pc_keyword_list_box" >
                            <?php foreach ($hc_search as $k=>$v): ?>
                            <div class="pc_keyword_list">
                                <label><span class="color_red">*</span>关键词<em><?php echo $k+1; ?></em>：
                                </label>
                                <div class="keyword_search">
                                    <input class="blue_input" type="text" name="hc_keyword[]" value="<?php echo $v->kwd; ?>" maxlength="50">&nbsp;&nbsp;
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="pc_keyword_add" style="padding-top:15px;">
                            <p class="color_red">增加关键词可提高活动安全保障</p>
                            <a href="javascript:;"><em class="add_icon">+</em>增加搜索关键词</a><i>（最多可添加4个方案）</i>
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="one_but_box"><input type="button" class="one_button" value="确认提交信息" /></div>
                
            </div>
            <!-- 填写商品信息end -->
            
            
            <!-- 填写商品信息完成start -->
            <div class="one_box_ok">
                
            </div>
            <!-- 填写商品信息完成end -->
            
            
            <!-- 设置商品收取运费方式start -->
            
            <div class="two_box">
                <h3>
                    <span class="color_red">* </span>设置商品收取运费的方式
                    <small class="color_red">为保障安全，请在店铺页面、旺旺上表明支持平台发货所使用的快递公司</small>
                </h3>
                <div class="baoyou_box">
                    <label><input type="radio" name="baoyou" checked value="0" <?php if ($trade_item->is_post == '0'): ?>checked<?php endif; ?> />商品不包邮<span>无需买手联系客服。商家每单额外支出<em><?php echo POST_FEE; ?></em>元作为运费押金，活动完成后运费押金将全部退还给商家</span></label>
                    <label><input type="radio" name="baoyou" value="1" <?php if ($trade_item->is_post == '1'): ?>checked<?php endif; ?> />商品本身包邮<span>买手按照商品实际金额下单</span></label>
                </div>
                <div class="two_but_box"><input type="button" class="two_button" value="确认提交信息" /></div>
            </div>
            
            <div class="two_box_ok">
                <h3>设置商品收取运费的方式</h3>
                <p>商品本身包邮&nbsp;&nbsp;买手按照商品实际金额下单<a href="javascript:;" class="two_box_update">修改</a></p>
            </div>
            
            <!-- 设置商品收取运费方式end -->
            
            
        </div>
        
        <div class="next_box">
            <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
            <a href="javascript:;" class="next_step_no">下一步</a>
        </div>  
    </div>
    <!-- 报名活动一键发布  确认弹窗 -->
    <div class="popup_wrap one_button_publishing" style="display: none;">
        <div class="publishing_wrap">
            <p class="publishing_remind">为了避免活动审核不通过，影响您的活动效率，请先核实好以下信息是否和您的<?= $plat_name ?>信息保持一致，并且确保您提供的关键字，可以在前5页搜索到您的商品，活动一旦审核通过，不得更改<?= $plat_name ?>商品信息，否则撤销对应活动。</p>
            <p class="publishing_shop_name">店铺名：<span>绿色放心购</span></p>
            <table>
                <thead>
                    <td width="20%">商品主图</td>
                    <td width="30%">商品名称</td>
                    <td width="20%">单品售价</td>
                    <td width="30%">商品链接</td>
                </thead>
                <tbody>
                    <tr>
                        <td width="20%"><img width="60" height="60" src="/static/imgs/trade/goods_pic_input.jpg" alt="商品主图"></td>
                        <td width="30%">商品名称</td>
                        <td width="20%"><span>100元</span></td>
                        <td width="30%"></td>
                    </tr>
                </tbody>
                
            </table>
            <p class="verify_goods"><span class="count_down">5</span>s后才能进行活动，请先核实商品信息</p>
            <div class="publishing_btn_wrap">
                <a class="publishing_btn dis_publishing_btn" href="javascript:;">确认无误，提交</a>
                <a class="prev_edit" href="javascript:;">返回修改</a>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>

<script src="/static/js/exif.js"></script>
<script src="/static/js/lrz.js"></script>
<script src="/static/js/task_step.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript">
$(function(){
    var _plat_name = '<?= $plat_name ?>';
    var _plat_id = '<?= $trade_info->plat_id; ?>';
    $(".goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });
    
    $(".tmall_goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });
    
    $(".phone_goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });
    
    $(".phone_goods_pic_con2 img").click(function(){
        $(this).siblings("input").click();
    });
    
    //所在地
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
    // $(window).load(function (e) {
    //     $(".phone_search_box>label").find('input[name="phone_check"]').prop("checked", "checked");
    //     $(".pc_search_box>label>input").attr("checked",false);
    //     $(".pc_tmall_search_box>label>input").attr("checked",false);
    //     $(".phone_taobao_con").slideDown();
    //     $(".pc_taobao_con").slideUp();
    //     $(".pc_tmall_con").slideUp();
    // });
    
    //选择搜索类型
    $(".pc_search_box>label").click(function(){
        if( $(this).children("input").is(":checked") ){
            $(".phone_search_box>label>input").attr("checked",false);
            $(".pc_taobao_con").slideDown();
            $(".phone_taobao_con").slideUp();
        }else{
            $(".pc_taobao_con").slideUp();
        }
    });
    
    $(".pc_tmall_search_box>label").click(function(){
        if( $(this).children("input").is(":checked") ){
            $(".phone_search_box>label>input").attr("checked",false);
            $(".pc_tmall_con").slideDown();
            $(".phone_taobao_con").slideUp();
        }else{
            $(".pc_tmall_con").slideUp();
        }
    });

    if($(".pc_search_box>label>input").is(":checked")){
        $(".pc_taobao_con").show();
    }

    if($(".pc_tmall_search_box>label>input").is(":checked")){
        $(".pc_tmall_con").show();
    }

    if($(".phone_search_box>label>input").is(":checked")){
        $(".phone_taobao_con").show();
    }
    
    $(".phone_search_box>label").click(function(){
        if( $(this).children("input").is(":checked") ){
            $(".pc_search_box>label>input").attr("checked",false);
            $(".pc_tmall_search_box>label>input").attr("checked",false);
            $(".phone_taobao_con").slideDown();
            $(".pc_taobao_con").slideUp();
            $(".pc_tmall_con").slideUp();
        }else{
            $(".phone_taobao_con").slideUp();
        }
    });
    
    $(".price input,.number input,.final_payment input").on('change keyup', function(event) {
        var price  = parseFloat( $(".price").children("input").val() )*10000;
        
        if ($(".final_payment").length > 0) {
            var price2  = parseFloat( $(".final_payment").children("input").val() )*10000;
            price = price + price2;
        }
    
        var number = parseFloat( $(".number").children("input").val() );
        if( !(price > 0) || !(number >0 ) ){
            $(".price_total").text(1);
        }else{
            $(".price_total").text( (price*number/10000).toFixed(2) );
        }
    });
    
    //PC点击添加关键词
    $(".keyword_add a").click(function(){
        var keyword_num = $(".keyword_list_box").children(".keyword_list").size()+1;
        $(".keyword_list_box").append('<div class="keyword_list">'+
                        '<label>关键字来源<em>'+keyword_num+'</em>：<a href="javascript:;" class="keyword_list_del">删除</a></label>'+
                        '<p class="keyword_search">让买手在<em>taobao.com</em>搜索关键字进入"搜索列表"<input type="text" name="tb_keyword[]" maxlength="50"/><em>必填</em></p>'+
                        '<p>在当前列表中，告诉买手通过筛选分类找到商品</p><div class="classification"><input type="text" name="tb_classify1[]" class="blue_input w125" /><input type="text" name="tb_classify2[]" class="blue_input w125" /><input type="text" name="tb_classify3[]" class="blue_input w125" /><input type="text" name="tb_classify4[]" class="blue_input w125" /><i>最多四个分类&nbsp;非必填</i></div>'+
                    '</div>');
        if(keyword_num>4){
            $(this).parent(".keyword_add").hide();
        }
    });
    //PC点击删除关键词
    $("body").on("click",".keyword_list_del",function(){
        $(this).parents(".keyword_list").remove();
        var keyword_num = $(".keyword_list_box").children(".keyword_list").size();
        for(i=0;i<keyword_num;i++){
            $(".keyword_list").eq(i).children("label").children("em").text(i+1);
        }
        $(".keyword_add").show();
    });
    
    //tmall点击添加关键词
    $(".tmall_keyword_add a").click(function(){
        var tmall_keyword_num = $(".tmall_keyword_list_box").children(".tmall_keyword_list").size()+1;
        $(".tmall_keyword_list_box").append('<div class="tmall_keyword_list">'+
                        '<label>关键字来源<em>'+tmall_keyword_num+'</em>：<a href="javascript:;" class="tmall_keyword_list_del">删除</a></label>'+
                        '<p class="keyword_search">让买手在<em>tmall.com</em>搜索关键字进入"搜索列表"<input type="text" name="tm_keyword[]" /><em>必填</em></p>'+
                        '<p>在当前列表中，告诉买手通过筛选分类找到商品</p><div class="classification"><input type="text" name="tm_classify1[]" class="blue_input w125" /><input type="text" name="tm_classify2[]" maxlength="50" class="blue_input w125" /><input type="text" name="tm_classify3[]" class="blue_input w125" /><input type="text" name="tm_classify4[]" class="blue_input w125" /><i>最多四个分类&nbsp;非必填</i></div>'+
                    '</div>');
        if(tmall_keyword_num>4){
            $(this).parent(".tmall_keyword_add").hide();
        }
    });
    //tmall点击删除关键词
    $("body").on("click",".tmall_keyword_list_del",function(){
        $(this).parents(".tmall_keyword_list").remove();
        var keyword_num = $(".tmall_keyword_list_box").children(".tmall_keyword_list").size();
        for(i=0;i<keyword_num;i++){
            $(".tmall_keyword_list").eq(i).children("label").children("em").text(i+1);
        }
        $(".tmall_keyword_add").show();
    });

    //phone点击添加关键词
    $(".phone_keyword_add a").click(function(){
        var keyword_num = $(".phone_keyword_list_box").children(".phone_keyword_list").size()+1;
        var str_html = '<div class="phone_keyword_list">' +
            '<label>手机' + _plat_name + '关键字来源<em>' + keyword_num + '</em>：<a href="javascript:;" class="phone_keyword_list_del">删除</a></label>' +
            '<p class="keyword_search">让买手打开<em>手机' + _plat_name + 'APP</em>搜索关键字进入"搜索列表"<input type="text" name="app_keyword[]" maxlength="50"/><em>必填</em></p>' +
            '<div class="price_range" style="padding:8px 0;">价格区间：<input type="text" name="price_start" onblur="javascript:this.value=this.value.replace(\/[^\\d.]\/g,\'\')" onkeyup="this.value=this.value.replace(\/[^\\d.]\/g,\'\')" />元&nbsp;-<input type="text" name="price_end" onblur="javascript:this.value=this.value.replace(\/[^\\d.]\/g,\'\')" onkeyup="this.value=this.value.replace(\/[^\\d.]\/g,\'\')" />元</div>' +
            '<div class="service"><span>折扣和服务：</span><p>';
        if (_plat_id == '4') {
            str_html += '<label><input class="app_discount" type="checkbox" value="京东物流" />京东物流</label>' +
                '<label><input class="app_discount" type="checkbox" value="自营211" />自营211</label>' +
                '<label><input class="app_discount" type="checkbox" value="货到付款" />货到付款</label>' +
                '<label><input class="app_discount" type="checkbox" value="仅看有货" />仅看有货</label>' +
                '<label><input class="app_discount" type="checkbox" value="全球购" />全球购</label>' +
                '<label><input class="app_discount" type="checkbox" value="配送全球" />配送全球</label>' +
                '<label><input class="app_discount" type="checkbox" value="PLUS专享价" />PLUS专享价</label>' +
                '<label><input class="app_discount" type="checkbox" value="新品" />新品</label>' +
                '<label><input class="app_discount" type="checkbox" value="促销" />促销</label>';
        } else {
            str_html +=
                '<label><input class="app_discount" type="checkbox" value="包邮" />包邮</label>' +
                '<label><input class="app_discount" type="checkbox" value="天猫" />天猫</label>' +
                '<label><input class="app_discount" type="checkbox" value="全球购" />全球购</label>' +
                '<label><input class="app_discount" type="checkbox" value="消费者保障" />消费者保障</label>' +
                '<label><input class="app_discount" type="checkbox" value="手机专享" />手机专享</label>' +
                '<label><input class="app_discount" type="checkbox" value="淘金币抵钱" />淘金币抵钱</label>' +
                '<label><input class="app_discount" type="checkbox" value="货到付款" />货到付款</label>' +
                '<label><input class="app_discount" type="checkbox" value="7+天退换货" />7+天退换货</label>' +
                '<label><input class="app_discount" type="checkbox" value="促销" />促销</label>' +
                '<label><input class="app_discount" type="checkbox" value="花呗分期" />花呗分期</label>' +
                '<label><input class="app_discount" type="checkbox" value="天猫超市" />天猫超市</label>' +
                '<label><input class="app_discount" type="checkbox" value="天猫国际" />天猫国际</label>' +
                '<label><input class="app_discount" type="checkbox" value="通用排序" />通用排序</label>';
        }
        str_html += '<input type="hidden" name="app_discount_text[]" />'+
                '</p>'+
            '</div>'+
            '<div class="row"><div class="col-xs-2"><div class="address">'+
                '<p>发货地：</p>'+
                '<span class="ie7_areaHack_area">'+
                   '<div class="sel-loc-box">'+
                        '<div class="fake-select sel-loc">'+
                            '<ul class="selected">'+
                                '<li>'+
                                    '<s class="sel_dropdown"><s class="i"></s></s>'+
                                    '<input type="hidden" name="app_area[]" class="position" value="全国" />'+
                                    '<a href="javascript:;" data-value="全国" data-nogo="true">全国</a>'+
                                '</li>'+
                            '</ul>'+
                            '<div style="display: none;" class="toselect">'+
                                '<ul class="loc1"><li class="checked"><a _val="全国" href="javascript:;">全国</a></li></ul>'+
                                '<ul class="loc2 split">'+
                                    '<li><a trace="location" _val="江苏,浙江,上海">江浙沪</a></li>'+
                                    '<li><a trace="location" _val="广州,深圳,中山,珠海,佛山,东莞,惠州">珠三角</a></li>'+
                                    '<li><a trace="location" _val="香港,澳门,台湾">港澳台</a></li>'+
                                    '<li><a trace="location" _val="美国,英国,法国,瑞士,澳洲,新西兰,加拿大,奥地利,韩国,日本,德国,意大利,西班牙,俄罗斯,泰国,印度,荷兰,新加坡,其它国家">海外</a></li>'+
                                    '<li><a trace="location">北京</a></li>'+
                                    '<li><a trace="location">上海</a></li>'+
                                    '<li><a trace="location">广州</a></li>'+
                                    '<li><a trace="location">深圳</a></li>'+
                                    '<li><a trace="location" _val="北京,天津">京津</a></li>'+
                                '</ul>'+
                                '<ul class="loc3">'+
                                    '<li><a trace="location">杭州</a></li>'+
                                    '<li><a trace="location">温州</a></li>'+
                                    '<li><a trace="location">宁波</a></li>'+
                                    '<li><a trace="location">南京</a></li>'+
                                    '<li><a trace="location">苏州</a></li>'+
                                    '<li><a trace="location">济南</a></li>'+
                                    '<li><a trace="location">青岛</a></li>'+
                                    '<li><a trace="location">大连</a></li>'+
                                    '<li><a trace="location">无锡</a></li>'+
                                    '<li><a trace="location">合肥</a></li>'+
                                    '<li><a trace="location">天津</a></li>'+
                                    '<li><a trace="location">长沙</a></li>'+
                                    '<li><a trace="location">武汉</a></li>'+
                                    '<li><a trace="location">石家庄</a></li>'+
                                    '<li><a trace="location">郑州</a></li>'+
                                    '<li><a trace="location">成都</a></li>'+
                                    '<li><a trace="location">重庆</a></li>'+
                                    '<li><a trace="location">西安</a></li>'+
                                    '<li><a trace="location">昆明</a></li>'+
                                    '<li><a trace="location">南宁</a></li>'+
                                    '<li><a trace="location">福州</a></li>'+
                                    '<li><a trace="location">厦门</a></li>'+
                                    '<li><a trace="location">南昌</a></li>'+
                                    '<li><a trace="location">东莞</a></li>'+
                                    '<li><a trace="location">沈阳</a></li>'+
                                    '<li><a trace="location">长春</a></li>'+
                                    '<li><a trace="location">哈尔滨</a></li>'+
                                '</ul>'+
                                '<ul class="loc4 split">'+
                                    '<li><a trace="location">河北</a></li>'+
                                    '<li><a trace="location">河南</a></li>'+
                                    '<li><a trace="location">湖北</a></li>'+
                                    '<li><a trace="location">湖南</a></li>'+
                                    '<li><a trace="location">福建</a></li>'+
                                    '<li><a trace="location">江苏</a></li>'+
                                    '<li><a trace="location">江西</a></li>'+
                                    '<li><a trace="location">广东</a></li>'+
                                    '<li><a trace="location">广西</a></li>'+
                                    '<li><a trace="location">海南</a></li>'+
                                    '<li><a trace="location">浙江</a></li>'+
                                    '<li><a trace="location">安徽</a></li>'+
                                    '<li><a trace="location">吉林</a></li>'+
                                    '<li><a trace="location">辽宁</a></li>'+
                                    '<li><a trace="location">黑龙江</a></li>'+
                                    '<li><a trace="location">山东</a></li>'+
                                    '<li><a trace="location">山西</a></li>'+
                                    '<li><a trace="location">陕西</a></li>'+
                                    '<li><a trace="location">新疆</a></li>'+
                                    '<li><a trace="location">云南</a></li>'+
                                    '<li><a trace="location">贵州</a></li>'+
                                    '<li><a trace="location">四川</a></li>'+
                                    '<li><a trace="location">甘肃</a></li>'+
                                    '<li><a trace="location">宁夏</a></li>'+
                                    '<li><a trace="location">西藏</a></li>'+
                                    '<li><a trace="location">香港</a></li>'+
                                    '<li><a trace="location">澳门</a></li>'+
                                    '<li><a trace="location">台湾</a></li>'+
                                    '<li><a trace="location">内蒙古</a></li>'+
                                    '<li><a trace="location">青海</a></li>'+
                                '</ul>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</span>'+
            '</div>'+
            '</div>'+
            '<div class="classification col-xs-9"><p>商品分类：</p><input class="blue_input" type="text" name="goods_cate[]" /></div>'+
        '</div></div>';
        $(".phone_keyword_list_box").append(str_html);
        if (keyword_num > 4) {
            $(this).parent(".phone_keyword_add").hide();
        }
    });
    //phone点击删除关键词
    $("body").on("click", ".phone_keyword_list_del", function () {
        $(this).parents(".phone_keyword_list").remove();
        var keyword_num = $(".phone_keyword_list_box").children(".phone_keyword_list").size();
        for (i = 0; i < keyword_num; i++) {
            $(".phone_keyword_list").eq(i).children("label").children("em").text(i + 1);
        }
        $(".phone_keyword_add").show();
    });


    //双11活动
    $(".pc_keyword_add a").click(function(){
        var pc_keyword_num = $(".pc_keyword_list_box").children(".pc_keyword_list").size()+1;
        $(".pc_keyword_list_box").append(
                '<div class="pc_keyword_list">'+
                '<label><span class="color_red">*</span>关键字<em>'+pc_keyword_num+'</em>：<a href="javascript:;" class="pc_keyword_list_del">删除</a> </label>'+
                '<div class="keyword_search">'+
                '<input class="blue_input" type="text" name="hc_keyword[]" value="" maxlength="50">&nbsp;&nbsp; </div>'+
                '</div>');
        if(pc_keyword_num>4){
            $(this).parent(".pc_keyword_add").hide();
        }
    })
    //双十一点击删除关键词
    $("body").on("click", ".pc_keyword_list_del", function () {
        $(this).parents(".pc_keyword_list").remove();
        var keyword_num = $(".pc_keyword_list_box").children(".pc_keyword_list").size();
        for (i = 0; i < keyword_num; i++) {
            $(".pc_keyword_list").eq(i).children("label").children("em").text(i + 1);
        }
        $(".pc_keyword_add").show();
    });

    // 填写商品信息总提交点击
    $("body").on("click", ".one_button", function () {
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
                $(".goods_title").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请填写商品标题</span>")
                $(".goods_title").addClass("redBorder");
            }
            toastr.warning("商品名称不能为空");
            return false;
        }

        var price = $(".price").children("input").val();
        var number = $(".number").children("input").val();
        if (price <= 0 || number <= 0) {
            if (price == "") {
                if ($(".price").find(".red").length == 0) {
                    $(".price").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请输入正确的商品金额</span>")
                    $(".price").addClass("redBorder");
                }
            }
            toastr.warning("请输入正确的商品金额和每单所需拍的件数");
            return false;
        }
        if (!$('input[name="hc_check"]').is(":checked") && !$('input[name="phone_check"]').is(":checked")) {
            toastr.warning('请选择使用会场搜索框"查找商品或者使用"手机' + _plat_name + '"查找商品');
            return false;
        }

        /* 关于商品相关内容验证end */
        var search_html_but = '<div class="goods_keyword"><h5>如何找到您的商品：</h5>';
        /* 选择活动类型 */
        //1.选中PC搜索
        if ($(".pc_search_box>label>input").is(":checked")) {
            if ($(".goods_pic_con input").attr("uploaded") == '') {
                toastr.warning("请选择上传商品主图");
                return false;
            }
            //关键词验证
            $(".keyword_list>p").children("span.error").remove();
            $(".keyword_list").each(function () {
                if ($(this).children(".keyword_search").children("input").val() == '') {
                    if ($(this).children(".keyword_search").find('.red').length == 0) {
                        $(this).children(".keyword_search").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请填写搜索关键词</span>");
                        $(this).children(".keyword_search").addClass("redBorder");
                    }
                    keyword_err = true;
                }
            });
            if (keyword_err) {
                toastr.warning("请填写搜索关键词");
                return false;
            }

            var search_html = '<p>使用"' + _plat_name + '搜索框"查找商品</p>';
            var price_start = $('input[name="pc_price_start"]').val();
            var price_end = $('input[name="pc_price_end"]').val();
            var address = $(".pc_taobao_con").find(".position").val();
            var i = 1;
            $(".keyword_list").each(function (e) {
                var $this = $(this);
                var keyword = $this.children(".keyword_search").children("input").val();
                var classification = [], str_classification = '';
                $this.children(".classification").children("input").each(function () {
                    if ($(this).val()) {
                        classification.push($(this).val());
                    }
                });
                if (classification.length > 0) {
                    str_classification = '所在分类：' + classification.join('&nbsp;|&nbsp;');
                }
                var price_number = "";
                if (price_start || price_end) {
                    price_number = '<p>价格：' + price_start + '&nbsp;-&nbsp;' + price_end + '元</p>'
                }
                search_html += '<div><b>来源关键字-' + i + '：' + keyword + '</b><p>' + str_classification + '</p>' + price_number + '<label>所在地：' + address + '</label></div>';
                i++;
            });
            search_html_but += search_html;
        }
        //2.选中tmall搜索
        if ($(".pc_tmall_search_box>label>input").is(":checked")) {
            if ($(".tmall_goods_pic_con input").attr("uploaded") == '') {
                toastr.warning("请选择上传商品主图");
                return false;
            }
            //关键词验证
            $(".tmall_keyword_list>p").children("span.error").remove();
            $(".tmall_keyword_list").each(function () {
                 if ($(this).children(".keyword_search").children("input").val() == '') {
                   if ($(this).children(".keyword_search").find('.red').length == 0) {
                     $(this).children(".keyword_search").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请填写搜索关键词</span>");
                       $(this).children(".keyword_search").addClass("redBorder");
                     }
                   keyword_err = true;
                   }
                  });
               if (keyword_err) {
                   toastr.warning("请填写搜索关键词");
                    return false;
                            }

            var tmall_search_html = '<p>使用"天猫搜索框"查找商品</p>';
            var tmall_price_start = $('input[name="pc_tmall_price_start"]').val();
            var tmall_price_end = $('input[name="pc_tmall_price_end"]').val();
            var tmall_address = $(".pc_tmall_con").find(".position").val();
            var i = 1;
            $(".tmall_keyword_list").each(function (e) {
                var $this = $(this);
                var tmall_keyword = $this.children(".keyword_search").children("input").val();
                var tmall_classification = [], str_classification = '';
                $this.children(".classification").children("input").each(function () {
                    if ($(this).val()) {
                        tmall_classification.push($(this).val());
                    }
                });
                if (tmall_classification.length > 0) {
                    str_classification = '所在分类：' + tmall_classification.join('&nbsp;|&nbsp;');
                }

                var price_number = "";
                if (tmall_price_start || tmall_price_end) {
                    price_number = '<p>价格：' + tmall_price_start + '&nbsp;-&nbsp;' + tmall_price_end + '元</p>'
                }

                tmall_search_html += '<div><b>来源关键字-' + i + '：' + tmall_keyword + '</b><p>' + str_classification + '</p>' + price_number + '<label>所在地：' + tmall_address + '</label></div>';
                i++;
            });
            search_html_but += tmall_search_html;
        }
        //3.选中phone搜索
        if ($(".phone_search_box>label>input").is(":checked")) {
            if ($(".phone_goods_pic_con input").attr("uploaded") == '') {
                toastr.warning("请选择上传商品主图");
                return false;
            }
            //关键词验证
            var keyword_err = false;
            $(".phone_keyword_list").each(function () {
                if ($(this).children(".keyword_search").children("input").val() == '') {
                    if ($(this).children(".keyword_search").find('.red').length == 0) {
                       $(this).children(".keyword_search").append("<span class='red'><img src='/static/imgs/unchecked.jpg'>请填写搜索关键词</span>");
                        $(this).children(".keyword_search").addClass("redBorder");
                    }
                    keyword_err = true;
                }
            });
            if (keyword_err) {
                toastr.warning("请填写搜索关键词");
                return false;
            }

            var search_html = '<p>使用"手机' + _plat_name + '搜索框"查找商品</p>';
            var i = 1;
            $(".phone_keyword_list").each(function (e) {
                var $this = $(this);
                var keyword = $this.find(".keyword_search").find("input").val();
                var price_start = $this.find(".price_range").find('input[name="app_price_start[]"]').val();
                var price_end = $this.find(".price_range").find('input[name="app_price_end[]"]').val();
                var price_number = "";
                if (price_start || price_end) {
                    price_number = '<p>价格：' + price_start + '&nbsp;-&nbsp;' + price_end + '元</p>'
                }

                var address = $this.find(".address").find(".position").val();
                var classification = $this.find(".classification").find("input").val();
                if (classification) {
                    classification = '所在分类：' + classification;
                }

                search_html += '<div><b>来源关键字-' + i + '：' + keyword + '</b><p>' + classification + '</p>' + price_number + '<label>所在地：' + address + '</label></div>';
                i++;
            });
            search_html_but += search_html;
        }


        //第一步提交信息成功后
        var _app_discount_texts = $('input[name="app_discount_text[]"]');
        _app_discount_texts.each(function () {
            var _tmps = $(this).parent().find('.app_discount:checked');
            var _tmp_val = "";
            _tmps.each(function () {
                _tmp_val = _tmp_val + $(this).val() + ",";
            });
            $(this).val(_tmp_val);
        });

        var goods_url = $(".goods_url input").val();
        var goods_title = $(".goods_title input").val();
        var guige_color = $('input[name="guige_color"]').val();
        var guige_size = $('input[name="guige_size"]').val();
        var price = $(".price input").val();
        var number = $(".number input").val();
        var show_price = $(".price_search input").val();
        var front_money = $('input[name="front_money"]').val();
        var final_payment = $('input[name="final_payment"]').val();

        var phone_check = $('input[name="phone_check"]:checked').val();
        var app_kwd = rtn_array($('input[name="app_keyword[]"]'));
        var app_low_price = rtn_array($('input[name="app_price_start[]"]'));
        var app_high_price = rtn_array($('input[name="app_price_end[]"]'));
        var app_discount_text = rtn_array($('input[name="app_discount_text[]"]'));
        var app_area = rtn_array($('input[name="app_area[]"]'));
        var goods_cate = rtn_array($('input[name="goods_cate[]"]'));
        var app_order_way = $('select[name="app_order_way"]').val();
        var app_img1_base64 = $('#2').attr('base64');
        var app_img2_base64 = $('#3').attr('base64');

        var hc_check = $('input[name="hc_check"]:checked').val();
        var activity_url  = $('input[name="activity_url"]').val();
        var activity_cate = $('input[name="activity_cate"]').val();
        var activity_img_base64  = $('#4').attr('base64');
        var goods_img_hc_base64  = $('#5').attr('base64');
        var hc_kwd       = rtn_array($('input[name="hc_keyword[]"]'));

        var one_box_ok_str = '<label>规格：</label>';

        $(".one_box_ok").html('<h3>核对商品信息</h3>' +
            '<div class="goods_info_ok">' +
            '<div><b>商品：</b>' + goods_title + '<a href="javascript:;" class="one_box_update">修改</a></div>' +
            '<div>' + one_box_ok_str + '<label><b>单品售价：<span>' + price + '</span>元</b></label><label><b>此商品每单拍：<span>' + number + '</span>个</b></label></div>' +
            '</div>' + search_html_but + '</div></div>');

        $.ajax({
            type: "POST",
            url: "/trade/d11_step2_1_submit/<?php echo $trade_info->id; ?>",
            data: {
                "goods_url": goods_url,
                "goods_name": goods_title,
                "color": guige_color,
                "size": guige_size,
                "price": price,
                "buy_num": number,
                "show_price": show_price,
                "front_money": front_money,
                "final_payment": final_payment,

                "phone_check": phone_check,
                "app_kwd[]": app_kwd,
                "app_low_price[]": app_low_price,
                "app_high_price[]": app_high_price,
                "app_discount_text[]": app_discount_text,
                "app_area[]": app_area,
                "goods_cate[]": goods_cate,
                "app_order_way": app_order_way,
                "app_img1_base64": app_img1_base64,
                "app_img2_base64": app_img2_base64,

                "hc_check":hc_check,
                "activity_url":activity_url,
                "activity_cate":activity_cate,
                "activity_img_base64":activity_img_base64,
                "goods_img_hc_base64":goods_img_hc_base64,
                "hc_kwd[]":hc_kwd
            },
            dataType: "json",
            success: function (d) {
                if (d.code != '0') {
                    toastr.error(d.msg);
                } else {
                    $(".one_box").hide();
                    $(".one_box_ok").show();
                    $(".two_box").show();
                }
            }
        });
    });

    // 修改第一步提交后的内容
    $("body").on("click",".one_box_update",function(){
        $(".one_box_ok").hide();
        $(".one_box").show();
        $(".two_box").hide();
        $(".two_box_ok").hide();
        $(".next_box").children("a").eq(1).addClass("next_step_no").removeClass("next_step");
    });
    
    
    //第二步设置商品运费方式提交
    $(".two_but_box").click(function(){
        var baoyou_status = $('input[name="baoyou"]:checked').val();
        if( baoyou_status == 0 ){
            $(".two_box_ok").html('<h3><span class="red">* </span>设置商品收取运费的方式</h3><p>商品不包邮&nbsp;&nbsp;无需买手联系客服。商家每单额外支出10元作为运费押金，活动完成后运费押金将全部退还给商家<a href="javascript:;" class="two_box_update">修改</a></p>');
        }else{
            $(".two_box_ok").html('<h3><span class="red">* </span>设置商品收取运费的方式</h3><p>商品本身包邮&nbsp;&nbsp;买手按照商品实际金额下单<a href="javascript:;" class="two_box_update">修改</a></p>');
        }
        
        $(".two_box").hide();
        $(".two_box_ok").show();
        $(".next_box").children("a").eq(1).addClass("next_step").removeClass("next_step_no");

        var is_post = $('input[name="baoyou"]:checked').val();

        $.ajax({
            type: "POST",
            url: "/trade/d11_step2_2_submit/<?php echo $trade_info->id; ?>",
            data: {
                "is_post":is_post
            },
            datatype: "json",
            success: function(d) {

            }
        });
    });
    
    //第二步设置商品运费方式修改
    $("body").on("click",".two_box_update",function(){
        $(".two_box").show();
        $(".two_box_ok").hide();
        
        $(".next_box").children("a").eq(1).addClass("next_step_no").removeClass("next_step");
    });

    // 到下一步
    $('.trade_box').on('click','.next_step',function(){

        $.ajax({
            type: "POST",
            url: "/trade/d11_step2_3_submit/<?php echo $trade_info->id; ?>",
            data: {
                "a":1
            },
            datatype: "json",
            success: function(d) {
                location.href = "/trade/step/<?php echo $trade_info->id; ?>"
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
                // 将图片转成base64
                getBase64(data.info.img).then(function (base64) {
                    var _goods_pic_div = $('.phone_search_box').find('.phone_goods_pic_con');
                    if (_goods_pic_div.length == 0) {
                        _goods_pic_div = $('.pc_taobao_con').find('.phone_goods_pic_con');
                    }
                    _goods_pic_div.find('input[name="goods_pic"]').attr('uploaded', 1).attr('base64', base64);
                    _goods_pic_div.find('img').attr('src', data.info.img);
                }, function (err) {
                    console.log(err);
                });
            }
        }, 'json');
    });
});

//下面用于商品信息图片上传预览功能
function setImagePreview(obj) {
    var docObj=document.getElementById(obj.id);
    var imgObjPreview=document.getElementById("goods_pic"+obj.id);
    lrz(obj.files[0],  function(res){
        $(obj).attr('base64', res.base64);
        $(obj).attr('uploaded', 1);
    });
    
    //验证平台展示图
    $(".goods_pic_con").children(".error").remove();
    //验证图片格式
    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
        toastr.warning("图片格式必须是gif，jpg，png中的一种");
        return false;
    }else{
        //验证图片大小
        if( docObj.files[0]['size'] > 1024*1024*2 ){
            toastr.warning("图片不能大于2M");
            return false;
        }
        //验证图片宽度、高度
        var _URL = window.URL || window.webkitURL;
        if ((file = obj.files[0])) {
            img = new Image();
            img.onload = function () {
                if( this.width > 1200 || this.height > 1200 ){
                    toastr.warning("图片尺寸要求1200x1200以内");
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    
    //显示预览图片
    if(docObj.files &&docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        //imgObjPreview.src = docObj.files[0].getAsDataURL();
        
        //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}

function setImagePreview2(obj) {
    var docObj=document.getElementById(obj.id);
    var imgObjPreview=document.getElementById("goods_pic"+obj.id);
    lrz(obj.files[0],  function(res){
        $(obj).attr('base64', res.base64);
        $(obj).attr('uploaded', 1);
    });
    
    //验证平台展示图
    $(".phone_goods_pic_con").children(".error").remove();
    //验证图片格式
    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
        toastr.warning("图片格式必须是gif，jpg，png中的一种");
        return false;
    }else{
        //验证图片大小
        if( docObj.files[0]['size'] > 1024*1024*2 ){
            toastr.warning("图片不能大于2M");
            return false;
        }
        //验证图片宽度、高度
        var _URL = window.URL || window.webkitURL;
        if ((file = obj.files[0])) {
            img = new Image();
            img.onload = function () {
                if( this.width > 1200 || this.height > 1200 ){
                    toastr.warning("图片尺寸要求1200x1200以内")
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    
    //显示预览图片
    if(docObj.files &&docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}

function setImagePreview3(obj) {
    var docObj=document.getElementById(obj.id);
    var imgObjPreview=document.getElementById("goods_pic"+obj.id);
    lrz(obj.files[0],  function(res){
        $(obj).attr('base64', res.base64);
        $(obj).attr('uploaded', 1);
    });
    
    //验证平台展示图
    $(".phone_goods_pic_con2").children(".error").remove();
    //验证图片格式
    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
        toastr.warning("图片格式必须是gif，jpg，png中的一种");
        return false;
    }else{
        //验证图片大小
        if( docObj.files[0]['size'] > 1024*1024*2 ){
            toastr.warning("图片不能大于2M");
            return false;
        }
        //验证图片宽度、高度
        var _URL = window.URL || window.webkitURL;
        if ((file = obj.files[0])) {
            img = new Image();
            img.onload = function () {
                if( this.width > 1200 || this.height > 1200 ){
                    toastr.warning("图片尺寸要求1200x1200以内");
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    
    //显示预览图片
    if(docObj.files &&docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}

function setImagePreview4(obj) {
    var docObj=document.getElementById(obj.id);
    var imgObjPreview=document.getElementById("goods_pic"+obj.id);

    lrz(obj.files[0],  function(res){
        $(obj).attr('base64', res.base64);
        $(obj).attr('uploaded', 1);
    });
    
    //验证图片格式
    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
        toastr.warning("图片格式必须是gif，jpg，png中的一种");
        return false;
    }else{
        //验证图片大小
        if( docObj.files[0]['size'] > 1024*1024*2 ){
            toastr.warning("图片不能大于2M");
            return false;
        }
        //验证图片宽度、高度
        var _URL = window.URL || window.webkitURL;
        if ((file = obj.files[0])) {
            img = new Image();
            img.onload = function () {
                if( this.width > 1200 || this.height > 1200 ){
                    toastr.warning("图片尺寸要求1200x1200以内");
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    
    //显示预览图片
    if(docObj.files &&docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}

function setImagePreview5(obj) {
    var docObj=document.getElementById(obj.id);
    var imgObjPreview=document.getElementById("goods_pic"+obj.id);

    lrz(obj.files[0],  function(res){
        $(obj).attr('base64', res.base64);
        $(obj).attr('uploaded', 1);
    });
    
    //验证图片格式
    if(!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(docObj.files[0]['name'])){
        toastr.warning("图片格式必须是gif，jpg，png中的一种");
        return false;
    }else{
        //验证图片大小
        if( docObj.files[0]['size'] > 1024*1024*2 ){
            toastr.warning("图片不能大于2M");
            return false;
        }
        //验证图片宽度、高度
        var _URL = window.URL || window.webkitURL;
        if ((file = obj.files[0])) {
            img = new Image();
            img.onload = function () {
                if( this.width > 1200 || this.height > 1200 ){
                    toastr.warning("图片尺寸要求1200x1200以内");
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    }
    
    //显示预览图片
    if(docObj.files &&docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}

// 对象转数组
function rtn_array(obj) {
    var tmpArr = new Array();

    obj.each(function (){
        tmpArr.push($(this).val());
    });

    return tmpArr;
}
// 5秒倒计时
var timer = setInterval(function() {
    var count_down = $('.count_down').text();
    if(count_down>0){
        count_down -- ;
        $('.count_down').text(count_down);
    }else{
        $('.publishing_btn').addClass('true_publishing_btn').removeClass('dis_publishing_btn');
        clearInterval(timer);
        $('.count_down').text(0);
    }
}, 1000);

$('.publishing_btn').click(function(event) {
    if($(this).hasClass('dis_publishing_btn'))return;
    $(this).parents('.popup_wrap').hide();
    $('.one_button').click();
});
$('.prev_edit').click(function(event) {
    $(this).parents('.popup_wrap').hide();
});
</script>
</body>
</html>