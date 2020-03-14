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
<title>商家报名活动-<?= PROJECT_NAME; ?></title>
<style>
    .guige input{margin-right:15px;width:134px;}
    .majorimg p{color:#909090;margin-top:5px;}
    .orderStyle{margin-bottom:20px;border-bottom:1px solid #ebebeb;}
    .orderStyle label{margin-right:32px;padding:20px 0;font-weight: normal;}
    .orderStyle label input,.falseChat label input{margin-right:10px;}
    .falseChat label{margin-right:32px;padding-top:20px;font-weight: normal;}
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
                        <div class="col-xs-5">
                            <div class="goods_url">
                                <label><span class="color_red">* </span>商品链接：</label> <input type="text" name="goods_url" value="<?= $trade_item->goods_url; ?>" autocomplete="off" style="width:290px;" />
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <div class="goods_title">
                                <label><span class="color_red">* </span>商品标题：</label><input type="text" style="width:290px;" name="goods_title" value="<?= $trade_item->goods_name; ?>" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="phone_taobao_pic row">
                        <div class="col-xs-5">
                            <div><label style="padding-left:30px;"> <span class="color_red">*</span>上传商品主图：</label></div>
                            <div class="phone_goods_pic_con pull-left" style="padding:24px 16px 4px 108px">
                                <img src="<?= ($app_search[0]->search_img) ? $app_search[0]->search_img : '/static/imgs/trade/goods_pic.png'; ?>" id="goods_pic2" title="点击更换商品主图" style="width:108px;height:108px" />
                                <input type="file" name="goods_pic" id="2" onchange="javascript:setImagePreview2(this);" uploaded="<?= $app_search[0]->search_img; ?>" base64="<?= $app_search[0]->search_img; ?>" />
                            </div>
                            <div class="pull-left goods_pic_con majorimg" style="margin-top:15px;">
                                <h5>商品主图</h5>
                                <p>图片尺寸：1200×1200以内</p>
                                <p>图片大小：不能大于2M</p>
                                <p>图片格式：jpg、png、gif</p>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="guige" style="margin:10px 0;">
                                        <label style="width:100px;">&nbsp;&nbsp;商品规格：</label>
                                        <input type="text" placeholder="如：颜色" name="guige_color" value="<?= $trade_item->color; ?>" autocomplete="off" />
                                        <input type="text" placeholder="如：尺码" name="guige_size" value="<?= $trade_item->size; ?>" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="row price_num">
                                <div class="col-xs-12">
                                    <div class="price" style="margin:10px 0;">
                                        <label style="width:100px;"><span class="color_red">* </span>商品价格：</label>
                                        <select style="width:140px;border:1px solid #bddffd;background-color:#fbfdff;border-radius:2px;" class="price_type">
                                            <option <?= ($app_search[0]->classify1 == '单购价格') ? 'selected':''; ?> value="单购价格">单购价格</option>
                                            <option <?= ($app_search[0]->classify1 == '拼团价格') ? 'selected':''; ?> value="拼团价格">拼团价格</option>
                                        </select>
                                        <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" value="<?= $trade_item->price; ?>" style="width: 200px;" autocomplete="off" disableautocomplete="">元
                                    </div>
                                </div>
                                <div class="col-xs-7" style="margin:10px 0;">
                                    <div class="number">
                                        <label style="width:100px;"><span class="color_red" style="padding-left:15px;">* </span>每单拍：</label>
                                        <input type="number" min="1" style="width:80px;" onblur="javascript:this.value=this.value.replace(/[^\d]/g,'');if(this.value<1){this.value=1};" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?= $trade_item->buy_num; ?>" style="text-align: center;" autocomplete="off" />件
                                        <i>(建议每单不要超过2件)</i>
                                    </div>
                                </div>
                                <div class="col-xs-5" style="margin:10px 0;">
                                    <div class="price_search" style="margin:0 0 0 15px;">
                                        <label>搜索页面展示价格：</label>
                                        <input type="text" style="width:80px;margin-right:0;" onblur="javascript:this.value=this.value.replace(/[^\d\¥￥－\-.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d\¥￥－\-.]/g,'')" value="<?= $trade_item->show_price; ?>" />&nbsp;元
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search_type_box white_box">
                    <h1>如何找到您的商品：</h1>
                    <!-- 手机搜索start -->
                    <div class="phone_search_box">
                        <div class="phone_taobao_con" style="display: block;">
                            <span>搜索关键词 <em style="color:#909090;">（通过搜索商品关键词找到目标商品）</em></span>
                            <div class="phone_keyword_list_box">
                                <?php foreach ($app_search as $key => $item): ?>
                                <div class="phone_keyword_list">
                                    <div class="row">
                                        <div class="keyword_search col-xs-4">
                                            <span class="color_red">*</span>关键词来源<em style="padding-left:4px">1</em>：
                                            <input class="blue_input" type="text" name="app_keyword[]" value="<?= $item->kwd ?>" maxlength="50" />
                                        </div>
                                        <div class="price_range col-xs-5">
                                            价格区间：
                                            <input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?= $item->low_price ?>" />元&nbsp;－ <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?= $item->high_price ?>" />元
                                        </div>
                                        <?php if ($key > 0): ?><a href="javascript:;" class="phone_keyword_list_del" style="margin-left:64px;line-height:32px;">删除</a><?php endif; ?>
                                    </div>
                                    <div class="row" style="margin-top:25px;">
                                        <div class="col-xs-4">
                                            <div class="address">
                                                <span style="float:left;padding:5px 5px 0 40px;display:inline-block">发货地：</span>
                                                <span class="ie7_areaHack_area">
                                                    <div class="sel-loc-box">
                                                        <div class="fake-select sel-loc">
                                                            <ul class="selected">
                                                                <li>
                                                                    <s class="sel_dropdown"><s class="i"></s></s>
                                                                    <input type="hidden" name="app_area[]" class="position" value="<?= $item->area ?>" />
                                                                    <a href="javascript:;" data-value="<?= $item->area ?>" data-nogo="true"><?= $item->area ?></a>
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
                                        <div class="col-xs-5 classification">
                                            <span>商品分类：</span>
                                            <input class="blue_input" type="text" name="goods_cate[]" value="<?= $item->goods_cate ?>" />
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="phone_keyword_add">
                                <p class="color_red">增加关键词可提高活动安全保障</p>
                                <a href="javascript:;"><em class="add_icon">+</em>增加搜索关键词</a><i>（最多可添加4个方案）</i>
                            </div>
                        </div>
                    </div>
                    <!-- 手机搜索end -->
                </div>
                <div class="search_type_box white_box" style="padding:30px;">
                    <label>下单类型</label>
                    <div class="orderStyle">
                        <label><input class="order_discount" type="checkbox" <?= ($app_search[0]->classify2 == '有团开团，无团再开') ? 'checked':''; ?> value="有团开团，无团再开">有团开团，无团再开</label>
                        <label><input class="order_discount" type="checkbox" <?= ($app_search[0]->classify2 == '开团') ? 'checked':''; ?> value="开团">开团</label>
                        <label><input class="order_discount" type="checkbox" <?= ($app_search[0]->classify2 == '参团') ? 'checked':''; ?> value="参团">参团</label>
                        <label><input class="order_discount" type="checkbox" <?= ($app_search[0]->classify2 == '单买') ? 'checked':''; ?> value="单买">单买</label>
                    </div>
                    <label>是否假聊</label>
                    <div class="falseChat">
                        <label><input type="checkbox" <?= ($app_search[0]->classify3 == '1') ? 'checked':''; ?> value="1">是</label>
                        <label><input type="checkbox" <?= ($app_search[0]->classify3 == '2') ? 'checked':''; ?> value="2">否</label>
                    </div>
                </div>
            </div>
            <!-- 填写商品信息end -->
        </div>
        <div class="next_box">
            <a href="/trade/prev/<?= $trade_info->id; ?>" class="previous_step">上一步</a>
            <a href="javascript:;" class="next_step">下一步</a>
        </div>
    </div>
    <!-- 报名活动一键发布  确认弹窗 -->

<?php $this->load->view("/common/footer"); ?>
<script src="/static/js/jquery-1.12.4.min.js"></script>
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/static/toast/toastr.min.js"></script>
<script src="/static/js/task_step.js"></script>
<script>
$(function(){
    var _plat_id = '<?= $trade_info->plat_id; ?>';
    var _trade_id = '<?= $trade_info->id; ?>';
    // 触发上传图片
    $(".phone_goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });
    // 下单类型
    $('.orderStyle').on('click', 'label', function () {
        var $this = $(this);
        $this.siblings("label").children("input").attr("checked", false);
        if (!$this.children('input').is(':checked')) {
            $this.children("input").prop("checked", true);
        }
    });
    // 是否假聊
    $('.falseChat').on('click', 'label', function () {
        var $this = $(this);
        $this.siblings("label").children("input").attr("checked", false);
        if (!$this.children('input').is(':checked')) {
            $this.children("input").prop("checked", true);
        }
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

    // phone点击添加关键词
    $(".phone_keyword_add a").click(function(){
        var keyword_num = $(".phone_keyword_list_box").children(".phone_keyword_list").size() + 1;
        var str_html = '<div class="phone_keyword_list">' +
            '<div class="row">' +
            '<div class="keyword_search col-xs-4"><span class="color_red">*</span>关键词来源<em style="padding-left:4px">'+ keyword_num +'</em>：<input class="blue_input" type="text" name="app_keyword[]" value="" maxlength="50">&nbsp;&nbsp;</div>' +
            '<div class="price_range col-xs-5">价格区间：<input class="blue_input" type="text" name="app_price_start[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="">元&nbsp;－ <input type="text" name="app_price_end[]" onblur="javascript:this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="">元</div>' +
            '<a href="javascript:;" class="phone_keyword_list_del" style="margin-left:64px;line-height:32px;">删除</a>' +
            '</div>' +
            '<div class="row" style="margin-top:25px;"><div class="col-xs-4"><div class="address">'+
                '<span style="float:left;padding:5px 5px 0 40px;display:inline-block">发货地：</span>'+
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
            '<div class="classification col-xs-5"><span>商品分类：</span><input class="blue_input" type="text" name="goods_cate[]" /></div>'+
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
            $(".phone_keyword_list").eq(i).find(".keyword_search").children("em").text(i + 1);
        }
        $(".phone_keyword_add").show();
    });
    // 填写商品信息总提交点击
    $("body").on("click", ".next_step", function () {
        /* 关于商品相关内容验证start */
        var goods_url_box = $(".goods_url").children("input");
        var _check_url = check_goods_url(goods_url_box.val(), _plat_id);
        if (_check_url.get('error') != 0) {
            goods_url_box.addClass("redBorder");
            toastr.warning(_check_url.get('message'));
            return false;
        } else {
            goods_url_box.removeClass("redBorder");
        }
        var goods_title_box = $(".goods_title").children("input");
        if (goods_title_box.val() == '') {
            goods_title_box.addClass("redBorder");
            toastr.warning("商品名称不能为空");
            return false;
        } else {
            goods_title_box.removeClass('redBorder');
        }

        var price_box = $(".price").children("input");
        if (isNaN(parseFloat(price_box.val())) ||  parseFloat(price_box.val()) <= 0) {
            price_box.addClass("redBorder");
            toastr.warning("请输入正确的商品单价");
            return false;
        } else {
            price_box.removeClass("redBorder");
        }
        var number_box = $(".number").children("input");
        if (isNaN(parseInt(number_box.val())) || parseInt(number_box.val()) <= 0) {
            number_box.addClass("redBorder");
            toastr.warning("请输入正确的商品金额和每单所需拍的件数");
            return false;
        } else {
            number_box.removeClass("redBorder");
        }
        /* 关于商品相关内容验证end */
        // 3.选中phone搜索
        if ($(".phone_goods_pic_con input").attr("uploaded") == '') {
            toastr.warning("请选择上传商品主图");
            return false;
        }
        // 关键词验证
        var keyword_err = false;
        $(".phone_keyword_list").each(function () {
            var _ipt_obj = $(this).find(".keyword_search").find('input[name="app_keyword[]"]');
            if (_ipt_obj.val() == '') {
                _ipt_obj.addClass("redBorder");
                keyword_err = true;
            } else {
                _ipt_obj.removeClass("redBorder");
            }
        });
        if (keyword_err) {
            toastr.warning("请填写搜索关键词");
            return false;
        }

        var goods_url = $(".goods_url input").val();
        var goods_title = $(".goods_title input").val();
        var guige_color = $('input[name="guige_color"]').val();
        var guige_size = $('input[name="guige_size"]').val();
        var price_type = $(".price .price_type").val();
        var price = $(".price input").val();
        var number = $(".number input").val();
        var show_price = $(".price_search input").val();
        // 搜索关键词
        var app_kwd = rtn_array($('input[name="app_keyword[]"]'));
        var app_low_price = rtn_array($('input[name="app_price_start[]"]'));
        var app_high_price = rtn_array($('input[name="app_price_end[]"]'));
        var app_area = rtn_array($('input[name="app_area[]"]'));
        var goods_cate = rtn_array($('input[name="goods_cate[]"]'));
        var app_img1_base64 = $('#2').attr('base64');
        var order_style = $('.orderStyle').find('input[type="checkbox"]:checked').val();
        var is_chat = $('.falseChat').find('input[type="checkbox"]:checked').val();
        // 数据提交
        $.ajax({
            type: "POST",
            url: "/trade/pdd_char_eval_step2_submit/" + _trade_id,
            dataType: "json",
            data: {
                "goods_url": goods_url,
                "goods_name": goods_title,
                "color": guige_color,
                "size": guige_size,
                "price_type": price_type,
                "price": price,
                "buy_num": number,
                "show_price": show_price,
                "app_kwd[]": app_kwd,
                "app_low_price[]": app_low_price,
                "app_high_price[]": app_high_price,
                "app_area[]": app_area,
                "goods_cate[]": goods_cate,
                "app_img1_base64": app_img1_base64,
                "order_style": order_style,
                "is_chat": is_chat,
            },
            success: function (res) {
                if (res.code != '0') {
                    toastr.error(res.msg);
                    return false
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
                // 图片上传到服务器
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
                                        var _goods_pic_div = $('.phone_taobao_pic').find('.phone_goods_pic_con');
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
});

// 下面用于商品信息图片上传预览功能
function setImagePreview2(obj) {
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

// 对象转数组
function rtn_array(obj) {
    var tmpArr = new Array();
    obj.each(function () {
        tmpArr.push($(this).val());
    });
    return tmpArr;
}
</script>
</body>
</html>