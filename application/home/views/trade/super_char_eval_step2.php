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
</head>
<body>
<?php $this->load->view("/common/top", ['site' => 'trade']); ?>
<div class="com_title">商家报名活动</div>
<!-- 发活动顶部活动步骤进度start -->
<div class="trade_top contain">
    <div class="Process">
        <ul class="clearfix">
            <li style="width:17%" class="cur"><em class="Processyes">1</em><span>选活动类型</span></li>
            <li style="width:17%"><em class="Processyes">2</em><span>添加预先浏览的商品</span></li>
            <li style="width:16%"><em>3</em><span>填写下单商品信息</span></li>
            <li style="width:16%"><em>4</em><span>选择活动数量</span></li>
            <li style="width:16%"><em>5</em><span>选增值服务</span></li>
            <li style="width:17%"><em>6</em><span>支付</span></li>
            <li style="width:17%" class="Processlast"><em>7</em><span>发布成功</span></li>
        </ul>
    </div>
</div>
<div style="clear: both;"></div>
<div class="trade_box">
    <div class="step2_box">
        <h3 style="margin-top:0;font-size:22px;">添加预先浏览的商品</h3>
        <!-- 添加搜索任务start -->
        <div class="goods_info_box white_box">
            <div class="phone_search_box">
                <div class="phone_taobao_con">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="goods_url" style="padding-left: 0">
                                <h4>1、预先浏览的商品</h4>
                                <p><label><span class="color_red">* </span>商品链接：</label></p>
                                <input type="text" name="goods_url[]" value="<?php echo $app_scans[0]->goods_url; ?>" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="goods_title" style="padding-left: 0;margin-top:40px;">
                                <p><label><span class="color_red">* </span>商品标题：</label></p>
                                <input type="text" name="goods_title[]" value="<?php echo $app_scans[0]->goods_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                            </div>
                        </div>
                    </div>
                    <div class="phone_taobao_pic row">
                        <div class="col-xs-6">
                            <div class="phone_goods_pic_con pull-left">
                                <?php if ($app_scans[0]->search_img): ?>
                                    <img src="<?= $app_scans[0]->search_img; ?>" height="130px" width="130px" id="goods_pic0" title="点击更换商品主图" />
                                <?php else: ?>
                                    <img src="/static/imgs/trade/goods_pic.png" id="goods_pic0" title="点击上传商品主图" />
                                <?php endif; ?>
                                <input type="file" name="goods_pic" id="0" onChange="javascript:setImagePreview2(this);" uploaded="<?php echo $app_scans[0]->search_img; ?>" base64="" />
                            </div>
                            <div class="pull-left goods_pic_con">
                                <h5><span class="color_red">*</span>商品主图1</h5>
                                <p>图片尺寸：1200×1200以内</p>
                                <p>图片大小：不能大于2M</p>
                                <p>图片格式：jpg、png、gif</p>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="phone_goods_pic_con2 pull-left">
                                <?php if ($app_scans[0]->search_img2): ?>
                                    <img src="<?= $app_scans[0]->search_img2; ?>" height="130" width="130" id="goods_pic1" title="点击更换商品主图" />
                                <?php else: ?>
                                    <img src="/static/imgs/trade/goods_pic.png" id="goods_pic1" title="点击上传商品主图" />
                                <?php endif; ?>
                                <input type="file" name="goods_pic" id="1" onChange="javascript:setImagePreview3(this);" uploaded="<?php echo $app_scans[0]->search_img2; ?>" base64="" />
                            </div>
                            <div class="pull-left goods_pic_con">
                                <h5>商品主图2</h5>
                                <p>图片尺寸：1200×1200以内</p>
                                <p>图片大小：不能大于2M</p>
                                <p>图片格式：jpg、png、gif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 shop_name_div" style="padding-left: 0">
                        <div class="goods_url" style="padding-left: 0" >
                            <p><label><span class="color_red">* </span>店铺名称：</label></p>
                            <input type="text" name="shop_name[]" value="<?php echo $app_scans[0]->shop_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                        </div>
                    </div>
                    <div class="phone_keyword_list_box" style="margin-top: 15px">
                        <label><span class="color_red">*</span>手机<?= $plat_name ?>关键字：</label>
                        <div class="keyword_search">
                            <input class="blue_input" type="text" name="app_keyword[]" value="<?php echo $app_scans[0]->kwd; ?>" maxlength="50"/>&nbsp;&nbsp;
                        </div>
                        <!-- 折叠区域 -->
                        <div class="open_extra_service" style="margin-top: 15px">
                            <a href="javascript:;" class="open_extra_a" data-is_open="0" style="color: #ed702c"><em class="add_icon" >▾</em>如搜索不到，可增加以下筛选条件</a>
                            <div class="extra_service">
                                <div class="price_search" style="padding-left: 0" >
                                    <p>搜索页面展示价格：</p>
                                    <input type="text" class="price" name="price[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scans[0]->price; ?>" style="text-align: center" autocomplete="off" disableautocomplete />
                                    <em>如该商品有满减、促销、多规格等情况，请填写此金额</em>
                                </div>
                                <div class="careful"><span>如果商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</span></div>
                                <div class="price_range">
                                    <p>价格区间：</p>
                                    <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scans[0]->low_price; ?>" />元&nbsp;-
                                    <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scans[0]->high_price; ?>" />元
                                </div>
                                <div class="service">
                                    <p>折扣和服务：</p>
                                    <p>
                                    <label><input class="app_discount" type="checkbox" value="包邮" <?php if (in_array('包邮', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />包邮</label>
                                    <label><input class="app_discount" type="checkbox" value="天猫" <?php if (in_array('天猫', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />天猫</label>
                                    <label><input class="app_discount" type="checkbox" value="全球购" <?php if (in_array('全球购', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />全球购</label>
                                    <label><input class="app_discount" type="checkbox" value="消费者保障" <?php if (in_array('消费者保障', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />消费者保障</label>
                                    <label><input class="app_discount" type="checkbox" value="手机专享" <?php if (in_array('手机专享', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />手机专享</label>
                                    <label><input class="app_discount" type="checkbox" value="淘金币抵钱" <?php if (in_array('淘金币抵钱', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />淘金币抵钱</label>
                                    <label><input class="app_discount" type="checkbox" value="货到付款" <?php if (in_array('货到付款', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />货到付款</label>
                                    <label><input class="app_discount" type="checkbox" value="7+天退换货" <?php if (in_array('7+天退换货', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />7+天退换货</label>
                                    <label><input class="app_discount" type="checkbox" value="促销" <?php if (in_array('促销', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />促销</label>
                                    <label><input class="app_discount" type="checkbox" value="花呗分期" <?php if (in_array('花呗分期', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />花呗分期</label>
                                    <label><input class="app_discount" type="checkbox" value="天猫超市" <?php if (in_array('天猫超市', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />天猫超市</label>
                                    <label><input class="app_discount" type="checkbox" value="天猫国际" <?php if (in_array('天猫国际', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />天猫国际</label>
                                    <label><input class="app_discount" type="checkbox" value="通用排序" <?php if (in_array('通用排序', $app_scans[0]->discount_arr)): ?>checked<?php endif; ?> />通用排序</label>
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
                                                        <s class="sel_dropdown"><s class="i"></s></s>
                                                        <input type="hidden" name="app_area[]" class="position" value="<?php echo $app_scans[0]->area; ?>" />
                                                        <a href="javascript:;" data-value="<?php echo $app_scans[0]->area; ?>" data-nogo="true"><?php echo $app_scans[0]->area; ?></a>
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
                                    <div class="col-xs-3 classification">
                                        <p>商品分类：</p>
                                        <input class="blue_input" type="text" name="goods_cate[]" value="<?php echo $app_scans[0]->goods_cate; ?>" />
                                    </div>
                                    <div class="col-xs-3 classification">
                                        <p>商品排序：</p>
                                        <select name="app_order_way[]" style="height:32px;" class="blue_input">
                                            <option value="综合排序" <?php if ($app_scans[0]->order_way == '综合排序'): ?>selected<?php endif; ?>>综合排序</option>
                                            <option value="销量优先" <?php if ($app_scans[0]->order_way == '销量优先'): ?>selected<?php endif; ?>>销量优先</option>
                                            <option value="价格从低到高" <?php if ($app_scans[0]->order_way == '价格从低到高'): ?>selected<?php endif; ?>>价格从低到高</option>
                                            <option value="价格从高到底" <?php if ($app_scans[0]->order_way == '价格从高到底'): ?>selected<?php endif; ?>>价格从高到底</option>
                                            <option value="信用排序" <?php if ($app_scans[0]->order_way == '信用排序'): ?>selected<?php endif; ?>>信用排序</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (count($app_scans) > 1) : ?>
                <?php foreach ($app_scans as $key => $app_scan) : ?>
                    <?php if ($key > 0) : ?>
                        <div class="phone_search_box">
                            <div class="phone_taobao_con">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="goods_url" style="padding-left: 0">
                                            <h4><?= $key+1 ?>、预先浏览的商品<a href="javascript:;" class="phone_keyword_list_del" style="margin-left: 30px">删除</a></label></h4>
                                            <p><label><span class="color_red">* </span>商品链接：</label></p>
                                            <input type="text" name="goods_url[]" value="<?php echo $app_scan->goods_url; ?>" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="goods_title" style="padding-left: 0;margin-top:40px;">
                                            <p><label><span class="color_red">* </span>商品标题：</label></p>
                                            <input type="text" name="goods_title[]" value="<?php echo $app_scan->goods_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="phone_taobao_pic row">
                                    <div class="col-xs-6">
                                        <div class="phone_goods_pic_con pull-left">
                                            <?php if ($app_scan->search_img): ?>
                                                <img src="<?= $app_scan->search_img; ?>" height="130" width="130" id="goods_pic<?= 2*$key; ?>" title="点击更换商品主图" />
                                            <?php else: ?>
                                                <img src="/static/imgs/trade/goods_pic.png" id="goods_pic<?= 2*$key; ?>" title="点击上传商品主图" />
                                            <?php endif; ?>
                                            <input type="file" name="goods_pic" id="<?= 2*$key; ?>" onChange="javascript:setImagePreview2(this);" uploaded="<?php echo $app_scan->search_img; ?>" base64="" />
                                        </div>
                                        <div class="pull-left goods_pic_con">
                                            <h5><span class="color_red">*</span>商品主图1</h5>
                                            <p>图片尺寸：1200×1200以内</p>
                                            <p>图片大小：不能大于2M</p>
                                            <p>图片格式：jpg、png、gif</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="phone_goods_pic_con2 pull-left">
                                            <?php if ($app_scan->search_img2): ?>
                                                <img src="<?= $app_scan->search_img2; ?>" height="130" width="130" id="goods_pic<?= 2*$key + 1; ?>" title="点击更换商品主图" />
                                            <?php else: ?>
                                                <img src="/static/imgs/trade/goods_pic.png" id="goods_pic<?= 2*$key + 1; ?>" title="点击上传商品主图" />
                                            <?php endif; ?>
                                            <input type="file" name="goods_pic" id="<?= 2*$key + 1; ?>" onChange="javascript:setImagePreview3(this);" uploaded="<?php echo $app_scan->search_img2; ?>" base64="" />
                                        </div>
                                        <div class="pull-left goods_pic_con">
                                            <h5>商品主图2</h5>
                                            <p>图片尺寸：1200×1200以内</p>
                                            <p>图片大小：不能大于2M</p>
                                            <p>图片格式：jpg、png、gif</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 shop_name_div" style="padding-left: 0">
                                    <div class="goods_url" style="padding-left: 0" >
                                        <p><label><span class="color_red">* </span>店铺名称：</label></p>
                                        <input type="text" name="shop_name[]" value="<?php echo $app_scan->shop_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>
                                    </div>
                                </div>
                                <div class="phone_keyword_list_box" style="margin-top: 15px">
                                    <label><span class="color_red">*</span>手机<?= $plat_name ?>关键字：</label>
                                    <div class="keyword_search">
                                        <input class="blue_input" type="text" name="app_keyword[]" value="<?php echo $app_scan->kwd; ?>" maxlength="50"/>&nbsp;&nbsp;
                                    </div>
                                    <!-- 折叠区域 -->
                                    <div class="open_extra_service" style="margin-top: 15px">
                                        <a href="javascript:;" class="open_extra_a" data-is_open="0" style="color: #ed702c"><em class="add_icon">▾</em>如搜索不到，可增加以下筛选条件</a>
                                        <div class="extra_service">
                                            <div class="price_search" style="padding-left: 0" >
                                                <p>搜索页面展示价格：</p>
                                                <input type="text" class="price" name="price[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scan->price; ?>" style="text-align: center" autocomplete="off" disableautocomplete />
                                                <em>如该商品有满减、促销、多规格等情况，请填写此金额</em>
                                            </div>
                                            <div class="careful"><span>如果商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</span></div>
                                            <div class="price_range">
                                                <p>价格区间：</p>
                                                <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scan->low_price; ?>" />元&nbsp;- <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo $app_scan->high_price; ?>" />元
                                            </div>
                                            <div class="service">
                                                <p>折扣和服务：</p>
                                                <p>
                                                    <label><input class="app_discount" type="checkbox" value="包邮" <?php if (in_array('包邮', $app_scan->discount_arr)): ?>checked<?php endif; ?> />包邮</label>
                                                    <label><input class="app_discount" type="checkbox" value="天猫" <?php if (in_array('天猫', $app_scan->discount_arr)): ?>checked<?php endif; ?> />天猫</label>
                                                    <label><input class="app_discount" type="checkbox" value="全球购" <?php if (in_array('全球购', $app_scan->discount_arr)): ?>checked<?php endif; ?> />全球购</label>
                                                    <label><input class="app_discount" type="checkbox" value="消费者保障" <?php if (in_array('消费者保障', $app_scan->discount_arr)): ?>checked<?php endif; ?> />消费者保障</label>
                                                    <label><input class="app_discount" type="checkbox" value="手机专享" <?php if (in_array('手机专享', $app_scan->discount_arr)): ?>checked<?php endif; ?> />手机专享</label>
                                                    <label><input class="app_discount" type="checkbox" value="淘金币抵钱" <?php if (in_array('淘金币抵钱', $app_scan->discount_arr)): ?>checked<?php endif; ?> />淘金币抵钱</label>
                                                    <label><input class="app_discount" type="checkbox" value="货到付款" <?php if (in_array('货到付款', $app_scan->discount_arr)): ?>checked<?php endif; ?> />货到付款</label>
                                                    <label><input class="app_discount" type="checkbox" value="7+天退换货" <?php if (in_array('7+天退换货', $app_scan->discount_arr)): ?>checked<?php endif; ?> />7+天退换货</label>
                                                    <label><input class="app_discount" type="checkbox" value="促销" <?php if (in_array('促销', $app_scan->discount_arr)): ?>checked<?php endif; ?> />促销</label>
                                                    <label><input class="app_discount" type="checkbox" value="花呗分期" <?php if (in_array('花呗分期', $app_scan->discount_arr)): ?>checked<?php endif; ?> />花呗分期</label>
                                                    <label><input class="app_discount" type="checkbox" value="天猫超市" <?php if (in_array('天猫超市', $app_scan->discount_arr)): ?>checked<?php endif; ?> />天猫超市</label>
                                                    <label><input class="app_discount" type="checkbox" value="天猫国际" <?php if (in_array('天猫国际', $app_scan->discount_arr)): ?>checked<?php endif; ?> />天猫国际</label>
                                                    <label><input class="app_discount" type="checkbox" value="通用排序" <?php if (in_array('通用排序', $app_scan->discount_arr)): ?>checked<?php endif; ?> />通用排序</label>
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
                                                <s class="sel_dropdown"><s class="i"></s></s>
                                                <input type="hidden" name="app_area[]" class="position" value="<?php echo $app_scan->area; ?>" />
                                                <a href="javascript:;" data-value="<?php echo $app_scan->area; ?>" data-nogo="true"><?php echo $app_scan->area; ?></a>
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
                                                <div class="col-xs-3 classification">
                                                    <p>商品分类：</p>
                                                    <input class="blue_input" type="text" name="goods_cate[]" value="<?php echo $app_scan->goods_cate; ?>" />
                                                </div>
                                                <div class="col-xs-3 classification">
                                                    <p>商品排序：</p>
                                                    <select name="app_order_way[]" style="height:32px;" class="blue_input">
                                                        <option value="综合排序" <?php if ($app_scan->order_way == '综合排序'): ?>selected<?php endif; ?>>综合排序</option>
                                                        <option value="销量优先" <?php if ($app_scan->order_way == '销量优先'): ?>selected<?php endif; ?>>销量优先</option>
                                                        <option value="价格从低到高" <?php if ($app_scan->order_way == '价格从低到高'): ?>selected<?php endif; ?>>价格从低到高</option>
                                                        <option value="价格从高到底" <?php if ($app_scan->order_way == '价格从高到底'): ?>selected<?php endif; ?>>价格从高到底</option>
                                                        <option value="信用排序" <?php if ($app_scan->order_way == '信用排序'): ?>selected<?php endif; ?>>信用排序</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="phone_keyword_add" <?php if (count($app_scans)>=5): ?>style="display:none;"<?php endif; ?>>
            <a href="javascript:;"><em class="add_icon">+</em>增加搜索任务</a><i>（最多可添加4个，<?= SUPER_SCAN_PRICE ?>金币/个）</i>
        </div>
    </div>
    <div class="next_box">
        <a href="/trade/prev/<?php echo $trade_info->id; ?>" class="previous_step">上一步</a>
        <a href="javascript:;" class="next_step">下一步</a>
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
        var _trade_id = '<?= $trade_info->id; ?>';
        // 默认展示手机淘宝
        $(window).load(function (e) {
            $(".phone_taobao_con").slideDown();
            $('.extra_service').slideUp();
        });
        //  点击添加搜索任务
        $(".phone_keyword_add a").click(function(){
            var trade_num = $(".goods_info_box").children(".phone_search_box").size() + 1
            var str_html =
                    '<div class="phone_search_box">' +
                        '<div class="phone_taobao_con" style="display: block">' +
                            '<div class="row">' +
                                '<div class="col-xs-6">' +
                                    '<div class="goods_url" style="padding-left: 0">' +
                                        '<h4>'+trade_num+'、预先浏览的商品<a href="javascript:;" class="phone_keyword_list_del" style="margin-left: 30px">删除</a></label></h4>' +
                                        '<p><label><span class="color_red">* </span>商品链接：</label></p>' +
                                        '<input type="text" name="goods_url[]" autocomplete="off" disableautocomplete/>' +
                                        '<span class="color_red">(必填)</span><br>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-xs-6">' +
                                    '<div class="goods_title" style="padding-left: 0;margin-top:40px;">' +
                                    ' <p><label><span class="color_red">* </span>商品标题：</label></p>' +
                                    '<input type="text" name="goods_title[]"  onkeyup="this.value=this.value.replace(/^ +| +$/g,\'\')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>' +
                                '</div>' +
                            '</div>' +
                            '<div class="phone_taobao_pic row">' +
                                '<div class="col-xs-6" style="padding-left:30px">' +
                                    '<div class="phone_goods_pic_con pull-left">' +
                                        '<img src="/static/imgs/trade/goods_pic.png" id="goods_pic'+(2*trade_num-2)+'" title="点击上传商品主图" />' +
                                       '<input type="file" name="goods_pic" id="'+ (2*trade_num-2)  +'" onChange="javascript:setImagePreview2(this);" uploaded base64 />' +
                                    '</div>' +
                                    '<div class="pull-left goods_pic_con">' +
                                        '<h5><span class="color_red">*</span>商品主图1</h5>' +
                                        '<p>图片尺寸：1200×1200以内</p>' +
                                        '<p>图片大小：不能大于2M</p>' +
                                       '<p>图片格式：jpg、png、gif</p>' +
                                   '</div>' +
                                '</div>' +
                                '<div class="col-xs-6">' +
                                    '<div class="phone_goods_pic_con2 pull-left">' +
                                        '<img src="/static/imgs/trade/goods_pic.png" id="goods_pic'+(2*trade_num-1)+'" title="点击上传商品主图" />' +
                                        '<input type="file" name="goods_pic"   id="'+ (2*trade_num-1)  +'" onChange="javascript:setImagePreview2(this);" uploaded base64 />' +
                                    '</div>' +
                                    '<div class="pull-left goods_pic_con">' +
                                        '<h5>商品主图2</h5>' +
                                        '<p>图片尺寸：1200×1200以内</p>' +
                                        '<p>图片大小：不能大于2M</p>' +
                                        '<p>图片格式：jpg、png、gif</p>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-xs-6 shop_name_div">' +
                                '<div class="goods_url" style="padding-left: 0" >' +
                                    '<p><label><span class="color_red">* </span>店铺名称：</label></p>' +
                                    '<input type="text" name="shop_name[]" onkeyup="this.value=this.value.replace(/^ +| +$/g,\'\')" autocomplete="off" disableautocomplete/><span class="color_red">(必填)</span><br>' +
                                '</div>' +
                            '</div>'+
                            '<div class="phone_keyword_list_box" style="margin-top: 15px">' +
                                '<label><span class="color_red">*</span>手机' + _plat_name +'关键字：</label>' +
                                '<div class="keyword_search">' +
                                    '<input class="blue_input" type="text" name="app_keyword[]" maxlength="50"/>' +
                                '</div>' +
                            '</div>' +

                            '<div class="open_extra_service" style="margin-top: 15px;padding-left: 15px">' +
                                '<a href="javascript:;" class="open_extra_a" data-is_open="0" style="color: #ed702c"><em class="add_icon">▾</em>如搜索不到，可增加以下筛选条件</a>' +
                                '<div class="extra_service" style="display: none">' +
                                    '<div class="price_search" style="padding-left: 0" >' +
                                        '<p>搜索页面展示价格：</p>' +
                                        '<input type="text" class="price" name="price[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')"  style="text-align: center" autocomplete="off" disableautocomplete />' +
                                        '<em>如该商品有满减、促销、多规格等情况，请填写此金额</em>' +
                                    '</div>' +
                                    '<div class="careful"><span>如果商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</span></div>'+
                                        '<div class="price_range">' +
                                            '<p>价格区间：</p>' +
                                            '<input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')"  />元&nbsp;-&nbsp;' +
                                            '<input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" />元' +
                                        '</div>' +
                                    '<div class="service">' +
                                        '<p>折扣和服务：</p><p>' +
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
                    '<label><input class="app_discount" type="checkbox" value="通用排序" />通用排序</label>' +
                    '<input type="hidden" name="app_discount_text[]" /></p></div>' +
                    '<div class="row"><div class="col-xs-2"><div class="address">'+
                    '<p>发货地：</p><span class="ie7_areaHack_area"><div class="sel-loc-box"><div class="fake-select sel-loc"><ul class="selected"><li>'+
                    '<s class="sel_dropdown"><s class="i"></s></s>'+
                    '<input type="hidden" name="app_area[]" class="position" value="全国" /><a href="javascript:;" data-value="全国" data-nogo="true">全国</a></li></ul>'+
                    '<div style="display: none;" class="toselect">'+
                    '<ul class="loc1"><li class="checked"><a val="全国" href="javascript:;">全国</a></li></ul>'+
                    '<ul class="loc2 split">'+
                    '<li><a trace="location" val="江苏,浙江,上海">江浙沪</a></li><li><a trace="location" val="广州,深圳,中山,珠海,佛山,东莞,惠州">珠三角</a></li>'+
                    '<li><a trace="location" val="香港,澳门,台湾">港澳台</a></li>'+
                    '<li><a trace="location" val="美国,英国,法国,瑞士,澳洲,新西兰,加拿大,奥地利,韩国,日本,德国,意大利,西班牙,俄罗斯,泰国,印度,荷兰,新加坡,其它国家">海外</a></li>'+
                    '<li><a trace="location">北京</a></li><li><a trace="location">上海</a></li><li><a trace="location">广州</a></li><li><a trace="location">深圳</a></li>'+
                    '<li><a trace="location" _val="北京,天津">京津</a></li></ul><ul class="loc3"><li><a trace="location">杭州</a></li>'+
                    '<li><a trace="location">温州</a></li><li><a trace="location">宁波</a></li><li><a trace="location">南京</a></li>'+
                    '<li><a trace="location">苏州</a></li><li><a trace="location">济南</a></li><li><a trace="location">青岛</a></li>'+
                    '<li><a trace="location">大连</a></li><li><a trace="location">无锡</a></li><li><a trace="location">合肥</a></li>'+
                    '<li><a trace="location">天津</a></li><li><a trace="location">长沙</a></li><li><a trace="location">武汉</a></li>'+
                    '<li><a trace="location">石家庄</a></li><li><a trace="location">郑州</a></li><li><a trace="location">成都</a></li>' +
                    '<li><a trace="location">重庆</a></li><li><a trace="location">西安</a></li><li><a trace="location">昆明</a></li>'+
                    '<li><a trace="location">南宁</a></li><li><a trace="location">福州</a></li><li><a trace="location">厦门</a></li>'+
                    '<li><a trace="location">南昌</a></li><li><a trace="location">东莞</a></li><li><a trace="location">沈阳</a></li>'+
                    '<li><a trace="location">长春</a></li><li><a trace="location">哈尔滨</a></li></ul><ul class="loc4 split">'+
                    '<li><a trace="location">河北</a></li><li><a trace="location">河南</a></li><li><a trace="location">湖北</a></li>'+
                    '<li><a trace="location">湖南</a></li><li><a trace="location">福建</a></li><li><a trace="location">江苏</a></li>'+
                    '<li><a trace="location">江西</a></li><li><a trace="location">广东</a></li><li><a trace="location">广西</a></li>'+
                    '<li><a trace="location">海南</a></li><li><a trace="location">浙江</a></li><li><a trace="location">吉林</a></li>'+
                    '<li><a trace="location">辽宁</a></li><li><a trace="location">黑龙江</a></li><li><a trace="location">山东</a></li>'+
                    '<li><a trace="location">山西</a></li><li><a trace="location">陕西</a></li><li><a trace="location">新疆</a></li>'+
                    '<li><a trace="location">云南</a></li><li><a trace="location">贵州</a></li><li><a trace="location">四川</a></li>'+
                    '<li><a trace="location">甘肃</a></li><li><a trace="location">宁夏</a></li><li><a trace="location">西藏</a></li>'+
                    '<li><a trace="location">香港</a></li><li><a trace="location">澳门</a></li><li><a trace="location">台湾</a></li>'+
                    '<li><a trace="location">内蒙古</a></li><li><a trace="location">青海</a></li></ul></div></div></div></span></div>'+
                    '</div><div class="col-xs-3 classification"><p>商品分类：</p><input class="blue_input" type="text" name="goods_cate[]" />' +
                    '</div><div></div class="col-xs-3 classification"><p>商品排序：</p>' +
                    '<select name="app_order_way[]" style="height:32px;" class="blue_input">' +
                    '<option value="综合排序">综合排序</option>' +
                    '<option value="销量优先">销量优先</option>' +
                    '<option value="价格从低到高">价格从低到高</option>' +
                    '<option value="价格从高到底">价格从高到底</option>' +
                    '<option value="信用排序">信用排序</option>' +
                    '</select></div></div></div></div></div>';
            $(".goods_info_box").append(str_html);
            if (trade_num >= 5) {
                $(this).parent(".phone_keyword_add").hide();
            }
        });

        // 删除搜索任务
        $("body").on("click", ".phone_keyword_list_del", function () {
            $(this).parents(".phone_search_box").remove();
            var keyword_num = $(".goods_info_box").children(".phone_search_box").size();
            for (i = 0; i < keyword_num; i++) {
                $(".phone_search_box").eq(i).children("label").children("em").text(i + 1);
            }
            $(".phone_keyword_add").show();
        });

        $(".goods_pic_con img").click(function(){
            $(this).siblings("input").click();
        });

        $("body").on("click", ".phone_goods_pic_con img",function(){
            $(this).siblings("input").click();
        });

        $("body").on("click", ".phone_goods_pic_con2 img",function(){
            $(this).siblings("input").click();
        });
        // 展开额外的筛选条件
        $("body").on('click', '.open_extra_a', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var $this = $(this)
            var _extra_service_div = $this.parent().find('.extra_service')
            var _is_open = $(this).attr('data-is_open');
            if (_is_open === "0") {
                $(this).attr('data-is_open', "1")
                _extra_service_div.slideDown()
            } else {
                $(this).attr('data-is_open', "0")
                _extra_service_div.slideUp();
            }
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
            set.html($(this).text());
            set.attr('data-value',_val);
            $(this).parents('.sel-loc-box').find('.position').val(_val);
            $('div.toselect').hide();
        });
        $(document).click(function(){
            $('div.toselect').hide();
        });

        $("body").on("change keyup", ".price input,.number input", function(event) {
            var price  = parseFloat( $(".price").children("input").val() )*10000;
            var number = parseFloat( $(".number").children("input").val() );
            if( !(price > 0) || !(number >0 ) ){
                $(".price_total").text(1);
            }else{
                $(".price_total").text( (price*number/10000).toFixed(2) );
            }
        });

        // 到下一步
        $('.trade_box').on('click','.next_step',function(){
            var trade_num = $(".goods_info_box").children(".phone_search_box").size()
            // 折扣服务
            var _app_discount_texts = $('input[name="app_discount_text[]"]');
            _app_discount_texts.each(function () {
                var _tmps = $(this).parent().find('.app_discount:checked');
                var _tmp_val = "";
                _tmps.each(function () {
                    _tmp_val = _tmp_val + $(this).val() + ",";
                });
                $(this).val(_tmp_val);
            });

            var goods_url = rtn_array($('input[name="goods_url[]"]'));
            var shop_name = rtn_array($('input[name="shop_name[]"]'));
            var goods_title = rtn_array($('input[name="goods_title[]"]'));
            var price = rtn_array($('input[name="price[]"]'));
            var kwd = rtn_array($('input[name="app_keyword[]"]'));
            var low_price = rtn_array($('input[name="app_price_start[]"]'));
            var high_price = rtn_array($('input[name="app_price_end[]"]'));
            var discount_text = rtn_array($('input[name="app_discount_text[]"]'));
            var area = rtn_array($('input[name="app_area[]"]'));
            var goods_cate = rtn_array($('input[name="goods_cate[]"]'));
            var order_way = rtn_array($('select[name="app_order_way[]"]'));
            var img1_base64 = ''
            var img2_base64 = ''
            var img3_base64 = ''
            var img4_base64 = ''
            var img5_base64 = ''
            var img6_base64 = ''
            var img7_base64 = ''
            var img8_base64 = ''
            switch (trade_num) {
                case 1:
                    img1_base64 = $('#0').attr('base64');
                    img2_base64 = $('#1').attr('base64');
                    break;
                case 2:
                    img1_base64 = $('#0').attr('base64');
                    img2_base64 = $('#1').attr('base64');
                    img3_base64 = $('#2').attr('base64');
                    img4_base64 = $('#3').attr('base64');
                    break;
                case 3:
                    img1_base64 = $('#0').attr('base64');
                    img2_base64 = $('#1').attr('base64');
                    img3_base64 = $('#2').attr('base64');
                    img4_base64 = $('#3').attr('base64');
                    img5_base64 = $('#4').attr('base64');
                    img6_base64 = $('#5').attr('base64');
                    break;
                case 4:
                    img1_base64 = $('#0').attr('base64');
                    img2_base64 = $('#1').attr('base64');
                    img3_base64 = $('#2').attr('base64');
                    img4_base64 = $('#3').attr('base64');
                    img5_base64 = $('#4').attr('base64');
                    img6_base64 = $('#5').attr('base64');
                    img7_base64 = $('#6').attr('base64');
                    img8_base64 = $('#7').attr('base64');
                    break;
                default:
                    break;
            }

            /* 关于商品相关内容验证start */
            for (var index = 0; index < trade_num; index ++) {
                var trade_index = index + 1
                if (goods_url[index] == '' || (goods_url[index].indexOf(".com") <= 0 && goods_url[index].indexOf("tmall.hk") <= 0)) {
                    toastr.warning("第" + trade_index + "个浏览商品：填写的商品链接不正确")
                    return false
                }
                if (goods_title[index] == '') {
                    toastr.warning("第" + trade_index + "个浏览商品：商品名称不能为空");
                    return false;
                }
                if (shop_name[index] == '') {
                    toastr.warning("第" + trade_index + "个浏览商品：店铺名称不能为空");
                    return false;
                }
                if (kwd[index] == '') {
                    toastr.warning("第" + trade_index + "个浏览商品：关键字不能为空");
                    return false;
                }
            }

            $.ajax({
                type: "POST",
                url: "/trade/super_char_eval_step2_submit/" + _trade_id,
                data: {
                    "num": trade_num,
                    "goods_url[]": goods_url,
                    "shop_name[]": shop_name,
                    "goods_name[]": goods_title,
                    "price[]": price,
                    "kwd[]": kwd,
                    "low_price[]": low_price,
                    "high_price[]": high_price,
                    "discount_text[]": discount_text,
                    "area[]": area,
                    "goods_cate[]": goods_cate,
                    "order_way[]": order_way,
                    "img1_base64": img1_base64,
                    "img2_base64": img2_base64,
                    "img3_base64": img3_base64,
                    "img4_base64": img4_base64,
                    "img5_base64": img5_base64,
                    "img6_base64": img6_base64,
                    "img7_base64": img7_base64,
                    "img8_base64": img8_base64
                },
                datatype: "json",
                success: function(res) {
                    location.href = "/trade/step/" + _trade_id;
                }
            });
        });

        // 根据商品URL抓取商品信息
        $("body").on("change", '.goods_url input', function (e) {
            var $this = $(this), _goods_url = $this.val();
            if (_goods_url == '' || (_goods_url.indexOf(".com") <= 0 && _goods_url.indexOf("tmall.hk") <= 0)) {
                // toastr.warning('填写的商品链接不正确，请确认')
            }
            // 根据商品url获取平台
            var plat_id = get_plat_by_goods_url(_goods_url)
            if (plat_id == '-1') {
                toastr.warning('暂不支持其他平台')
            }
            $.post('/ajax/get_goods_info', {url: _goods_url, plat: plat_id}, function (data) {
                if (data.error == 0) {
                    $this.parent().parent().parent().find('input[name="goods_title[]"]').val(data.info.title)
                    // 将图片转成base64
                    getBase64(data.info.img).then(function (base64) {
                        var _goods_pic_div = $this.parent().parent().parent().parent().parent().find('.phone_goods_pic_con')
                        _goods_pic_div.find('input[name="goods_pic"]').attr('uploaded', 1).attr('base64', base64);
                        _goods_pic_div.find('img').attr('src', data.info.img);
                    }, function (err) {
                        console.log(err);
                    });
                }
            }, 'json');
            $.post('/ajax/get_shop_name', {url: _goods_url}, function (res) {
                if (res.error == 0) {
                    console.log(res.shop_name)
                    var _shop_name_div = $this.parent().parent().parent().parent().find('.shop_name_div');
                    _shop_name_div.find('input[name="shop_name[]"]').val(res.shop_name)
                } else {
                    // toastr.warning(res.message)
                }
            }, 'json');
        });
    });

    function get_plat_by_goods_url(goods_url) {
        if (goods_url == '' || (goods_url.indexOf(".com") <= 0 && goods_url.indexOf("tmall.hk") <= 0)) {
            return 0;
        }
        if (goods_url.indexOf("taobao.com") > 0 || goods_url.indexOf("fliggy.com") > 0 || goods_url.indexOf("alitrip.com") > 0) {
            return 1;
        }
        if (goods_url.indexOf("tmall.com") > 0 || goods_url.indexOf("yao.95095.com") > 0 || goods_url.indexOf("tmall.hk") > 0 || goods_url.indexOf("ju.taobao.com") > 0) {
            return 2;
        }
        return -1;
    }
    //下面用于商品信息图片上传预览功能
    function setImagePreview(obj) {
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