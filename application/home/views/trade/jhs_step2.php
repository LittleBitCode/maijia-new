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
                        <div class="col-xs-3">
                            <div class="price">
                                <p><label><span class="color_red">* </span>单品售价：</label></p>
                                <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" onafterpaste="value=value.replace(/\D/g,'')" value="<?php echo $trade_item->price; ?>" style="width: 175px;" autocomplete="off" disableautocomplete />元<span class="color_red">(必填)</span><br>
                            </div>
                        </div>
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
                    <div class="prompt">如商家发布的是手机端活动，请务必填写手机端展示的商品主图、页面搜索价格和商品筛选分类、排序等。</div>
                </div>
                <div class="search_type_box white_box">
                    <h1>如何找到您的商品：</h1>
                    <!-- 手机聚划算— 链接直拍 start -->
                    <div class="phone_search_box">
                        <label><input type="checkbox" name="phone_check" <?= ($app_search[0]->kwd == 'jhs_link') ? 'checked' : ''; ?> />使用<span class="red">"手机聚划算——链接直拍”</span>找到商品<span class="red">（用户直接使用手机复制链接下单）</span></label>
                        <div class="phone_taobao_con <?= ($app_search[0]->kwd == 'jhs_link') ? 'show' : 'hide'; ?>">
                            <div class="phone_taobao_pic row">
                                <div class="col-xs-5">
                                    <div class="phone_goods_pic_con pull-left">
                                        <?php if ($app_search[0]->search_img): ?>
                                        <img src="<?= $app_search[0]->search_img; ?>" height="130" width="130" id="goods_pic1" title="点击更换活动主图" />
                                        <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic1" title="点击上传活动主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="1" onChange="javascript:setImagePreview(this);" uploaded="<?php echo $app_search[0]->search_img; ?>" base64="" />
                                    </div>
                                    <div class="pull-left goods_pic_con">
                                        <h5>活动主图<span class="red" style="padding:4px;">*</span></h5>
                                        <p>图片尺寸：1200×1200以内</p>
                                        <p>图片大小：不能大于2M</p>
                                        <p>图片格式：jpg、png、gif</p>
                                    </div>
                                </div>
                                <div class="col-xs-7" style="display:<?= ($trade_info->plat_id == '4') ? 'none':''; ?>">
                                    <div class="phone_goods_pic_con2 pull-left">
                                        <?php if ($app_search[0]->search_img2): ?>
                                        <img src="<?= $app_search[0]->search_img2; ?>" height="130" width="130" id="goods_pic2" title="点击更换商品主图" />
                                        <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic2" title="点击上传商品主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="2" onChange="javascript:setImagePreview(this);" uploaded="<?php echo $app_search[0]->search_img2; ?>" base64="" />
                                    </div>
                                    <div class="pull-left goods_pic_con">
                                        <h5>商品主图<span class="red" style="padding:4px;">*</span></h5>
                                        <p>图片尺寸：1200×1200以内</p>
                                        <p>图片大小：不能大于2M</p>
                                        <p>图片格式：jpg、png、gif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 手机聚划算— 链接直拍 END -->

                    <!-- 手机淘宝 - 聚划算 start -->
                    <div class="phone_taobao_jhs_box">
                        <label><input type="checkbox" name="phone_check" <?= ($app_search[0]->kwd == 'jhs_search') ? 'checked' : ''; ?> style="margin-top: 4px;margin-right: 5px;" />使用<span class="red">"手机淘宝——聚划算”</span>找到商品<span class="red">（选择使用“手机淘宝——聚划算”查找商品，此任务默认是无线手机端活动）</span></label>
                        <div class="phone_taobao_con <?= ($app_search[0]->kwd == 'jhs_search') ? 'show' : 'hide'; ?>">
                            <div class="phone_taobao_pic row">
                                <div class="col-xs-5">
                                    <div class="phone_goods_pic_con pull-left">
                                        <?php if ($app_search[0]->search_img): ?>
                                            <img src="<?= $app_search[0]->search_img; ?>" height="130" width="130" id="goods_pic3" title="点击更换活动主图" />
                                        <?php else: ?>
                                            <img src="/static/imgs/trade/goods_pic.png" id="goods_pic3" title="点击上传活动主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="3" onChange="javascript:setImagePreview(this);" uploaded="<?php echo $app_search[0]->search_img; ?>" base64="" />
                                    </div>
                                    <div class="pull-left goods_pic_con">
                                        <h5>活动主图<span class="red" style="padding:4px;">*</span></h5>
                                        <p>图片尺寸：1200×1200以内</p>
                                        <p>图片大小：不能大于2M</p>
                                        <p>图片格式：jpg、png、gif</p>
                                    </div>
                                </div>
                                <div class="col-xs-7" style="display:<?= ($trade_info->plat_id == '4') ? 'none':''; ?>">
                                    <div class="phone_goods_pic_con2 pull-left">
                                        <?php if ($app_search[0]->search_img2): ?>
                                            <img src="<?= $app_search[0]->search_img2; ?>" height="130" width="130" id="goods_pic4" title="点击更换商品主图" />
                                        <?php else: ?>
                                            <img src="/static/imgs/trade/goods_pic.png" id="goods_pic4" title="点击上传商品主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="4" onChange="javascript:setImagePreview(this);" uploaded="<?php echo $app_search[0]->search_img2; ?>" base64="" />
                                    </div>
                                    <div class="pull-left goods_pic_con">
                                        <h5>商品主图<span class="red" style="padding:4px;">*</span></h5>
                                        <p>图片尺寸：1200×1200以内</p>
                                        <p>图片大小：不能大于2M</p>
                                        <p>图片格式：jpg、png、gif</p>
                                    </div>
                                </div>
                            </div>
                            <div class="phone_keyword_list_box">
                                <div class="phone_keyword_list">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <span>聚划算类型：</span>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '今日') ? 'checked' : ''; ?> value="今日" style="float: left;margin-top: 4px;margin-right: 5px;" />今日</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '非常大牌') ? 'checked' : ''; ?> value="非常大牌" style="float: left;margin-top: 4px;margin-right: 5px;" />非常大牌</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '聚名品') ? 'checked' : ''; ?> value="聚名品" style="float: left;margin-top: 4px;margin-right: 5px;" />聚名品</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '品牌') ? 'checked' : ''; ?> value="品牌" style="float: left;margin-top: 4px;margin-right: 5px;" />品牌</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '全球精选') ? 'checked' : ''; ?> value="全球精选" style="float: left;margin-top: 4px;margin-right: 5px;" />全球精选</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->order_way == '量贩精选') ? 'checked' : ''; ?> value="量贩精选" style="float: left;margin-top: 4px;margin-right: 5px;" />量贩精选</label>
                                        </div>
                                    </div>
                                    <div class="row" style="display: table-cell;">
                                        <p style="margin:10px 0;">缩小搜索范围：让买手通过<span class="red">“商品分类”</span>缩小范围，快速找到任务商品，提高任务完成率<span class="gry">（例如：今日-男装-牛仔裤-精选男装）</span></p>
                                        <span>商品分类：</span>
                                        <input class="blue_input" type="text" name="goods_cate" value="<?= $app_search[0]->goods_cate ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 手机淘宝 - 聚划算 END -->
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

<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<script src="/static/js/exif.js"></script>
<script src="/static/js/lrz.js"></script>
<script src="/static/js/task_step.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript">
$(function(){
    var _plat_name = '<?= $plat_name ?>';
    var _plat_id = '<?= $trade_info->plat_id; ?>';
    var _trade_id = '<?= $trade_info->id; ?>';
    $(".goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });

    $(".phone_goods_pic_con img").click(function(){
        $(this).siblings("input").click();
    });
    
    $(".phone_goods_pic_con2 img").click(function(){
        $(this).siblings("input").click();
    });

    // 手机聚划算——链接直拍  手机淘宝——聚划算
    $('.search_type_box').on('click', 'input[name="phone_check"]', function (e) {
        var $this = $(this), _parent = $this.parent().parent();
        if (_parent.hasClass('phone_search_box')) {
            var _another_box = $(".phone_taobao_jhs_box");
            if ($this.is(':checked')) {
                $this.prop("checked", true);
                _parent.find(".phone_taobao_con").removeClass('hide').slideDown();
                _another_box.find('input[name="phone_check"]').prop("checked", false);
                _another_box.find(".phone_taobao_con").removeClass('show').slideUp();
            } else {
                $this.prop("checked", false);
                _parent.find(".phone_taobao_con").removeClass('show').slideUp();
                _another_box.find('input[name="phone_check"]').prop("checked", true);
                _another_box.find(".phone_taobao_con").removeClass('hide').slideDown();
            }
        } else if (_parent.hasClass('phone_taobao_jhs_box')) {
            var _another_box = $(".phone_search_box");
            if ($this.is(':checked')) {
                $this.prop("checked", true);
                _parent.find(".phone_taobao_con").removeClass('hide').slideDown();
                _another_box.find('input[name="phone_check"]').prop("checked", false);
                _another_box.find(".phone_taobao_con").removeClass('show').slideUp()
            } else {
                $this.prop("checked", false);
                _parent.find(".phone_taobao_con").removeClass('show').slideUp();
                _another_box.find('input[name="phone_check"]').prop("checked", true);
                _another_box.find(".phone_taobao_con").removeClass('hide').slideDown();
            }
        }
    });

    $(".price input,.number input").on('change keyup', function(event) {
        var price  = parseFloat( $(".price").children("input").val() )*10000;
        var number = parseFloat( $(".number").children("input").val() );
        if( !(price > 0) || !(number >0 ) ){
            $(".price_total").text(1);
        }else{
            $(".price_total").text( (price*number/10000).toFixed(2) );
        }
    });

    // 聚划算类型
    $('.phone_keyword_list').on('click', 'label', function (e) {
        var $this = $(this);
        $this.siblings("label").children("input").attr("checked", false);
        $this.children("input").prop("checked", true);
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
            toastr.warning("请输入报名活动商品的标题");
            return false;
        }

        var price = $(".price").children("input").val();
        var number = $(".number").children("input").val();
        if (price <= 0 || number <= 0) {
            toastr.warning("请输入正确的商品金额和每单所需拍的件数");
            return false;
        }

        var _phone_check = $('.search_type_box').find('input[name="phone_check"]:checked');
        if (_phone_check.length <= 0){
            toastr.warning('请选择使用"手机聚划算——链接直拍“搜索框"查找商品、或者使用"手机淘宝——聚划算"查找商品');
            return false;
        }

        // 第一步提交信息成功后
        var goods_url = $(".goods_url input").val();
        var goods_title = $(".goods_title input").val();
        var guige_color = $('input[name="guige_color"]').val();
        var guige_size = $('input[name="guige_size"]').val();
        var price = $(".price input").val();
        var number = $(".number input").val();
        var show_price = $(".price_search input").val();

        var _active_type = '', app_discount = '', app_img_1 = null, app_img_2 = null;
        var _parent_list = _phone_check.parent().parent(), _parent = _parent_list[0];
        if (_parent.classList.contains('phone_taobao_jhs_box')){        // 手机淘宝——聚划算
            _active_type = 'jhs_search';
            app_img_1 = $(_parent).find('#3');
            var uploaded = app_img_1.attr('uploaded'), app_img1_base64 = app_img_1.attr('base64');
            if (uploaded == '' && app_img1_base64 == ''){
                toastr.warning('请上传手机淘宝-聚划算商品的活动主图');
                return 0;
            }

            app_img_2 = $(_parent).find('#4');
            var uploaded = app_img_2.attr('uploaded'), app_img2_base64 = app_img_2.attr('base64');
            if (uploaded == '' && app_img2_base64 == ''){
                toastr.warning('请上传手机淘宝-聚划算商品主图');
                return 0;
            }
            var app_discount_item = $(_parent).find('.app_discount:checked');
            if (app_discount_item.length <= 0){
                toastr.warning('请指定手机淘宝-聚划算类型');
                return 0 ;
            } else {
                app_discount = app_discount_item[0].value;
            }
            var goods_cate = $(_parent).find('input[name="goods_cate"]').val();
        } else if(_parent.classList.contains('phone_search_box')) {     // 手机聚划算——链接直拍
            _active_type = 'jhs_link';
            app_img_1 = $(_parent).find('#1');
            var uploaded = app_img_1.attr('uploaded'), app_img1_base64 = app_img_1.attr('base64');
            if (uploaded == '' && app_img1_base64 == ''){
                toastr.warning('请上传手机聚划算-链接直拍商品活动主图');
                return 0;
            }

            app_img_2 = $(_parent).find('#2');
            var uploaded = app_img_2.attr('uploaded'), app_img2_base64 = app_img_2.attr('base64');
            if (uploaded == '' && app_img2_base64 == ''){
                toastr.warning('请上传手机聚划算-链接直拍商品主图');
                return 0;
            }
        }

        var one_box_ok_str = '<label>规格：';
        if (guige_color) {
            one_box_ok_str += guige_color + '-';
        }
        if (guige_size) {
            one_box_ok_str += guige_size;
        } else {
            one_box_ok_str.substring(0, one_box_ok_str.length - 1);
        }
        if (!guige_color && !guige_size) {
            one_box_ok_str = "";
        } else {
            one_box_ok_str += '</label>';
        }

        $(".one_box_ok").html('<h3>核对商品信息</h3>' +
            '<div class="goods_info_ok">' +
            '<div><b>商品：</b>' + goods_title + '<a href="javascript:;" class="one_box_update">修改</a></div>' +
            '<div>' + one_box_ok_str + '<label><b>单品售价：<span>' + price + '</span>元</b></label><label><b>此商品每单拍：<span>' + number + '</span>个</b></label></div></div>');

        // 数据提交
        $.ajax({
            type: "POST",
            url: "/trade/jhs_step2_1_submit/" + _trade_id,
            data: {
                "goods_url": goods_url, "goods_name": goods_title, "color": guige_color, "size": guige_size, "price": price, "buy_num": number, "show_price": show_price,
                "app_img1_base64": app_img1_base64, "app_img2_base64": app_img2_base64,
                "phone_taobao": 1, "active_type": _active_type, "type": app_discount, "goods_cate": goods_cate
            },
            dataType: "json",
            success: function (d) {
                if (d.code != '0') {
                    toastr.error(d.msg);
                    return 0 ;
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
            url: "/trade/char_eval_step2_2_submit/" + _trade_id,
            data: {"is_post": is_post},
            datatype: "json",
            success: function (d) { }
        });
    });
    
    //第二步设置商品运费方式修改
    $("body").on("click",".two_box_update",function(){
        $(".two_box").show();
        $(".two_box_ok").hide();
        
        $(".next_box").children("a").eq(1).addClass("next_step_no").removeClass("next_step");
    });

    // 到下一步
    $('.trade_box').on('click', '.next_step', function () {
        $.ajax({
            type: "POST",
            url: "/trade/char_eval_step2_3_submit/" + _trade_id,
            data: {"a": 1},
            datatype: "json",
            success: function (d) {
                location.href = "/trade/step/" + _trade_id;
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
                    // 手机聚划算——链接直拍
                    var _goods_pic_div = $('.phone_search_box').find('.phone_goods_pic_con2');
                    _goods_pic_div.find('input[name="goods_pic"]').attr('uploaded', 1).attr('base64', base64);
                    _goods_pic_div.find('img').attr('src', data.info.img);

                    // 手机淘宝——聚划算
                    var _goods_pic_div = $('.phone_taobao_jhs_box').find('.phone_goods_pic_con2');
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
    
    // 显示预览图片
    if (docObj.files && docObj.files[0]) {
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '130px';
        imgObjPreview.style.height = '130px';
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }
}
</script>
</body>
</html>