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
                                <input type="number" min="1" onblur="javascript:this.value=this.value.replace(/[^\d]/g,'');if(this.value<1){this.value=1};" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?php echo $trade_item->buy_num; ?>" style="text-align: center;" autocomplete="off" disableautocomplete />件
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
                    <!-- 手机搜索start -->
                    <div class="phone_search_box">
                        <label>
                            <input type="checkbox" name="phone_check" <?php if ($trade_info->is_phone): ?>checked<?php endif; ?> />使用<span class="red">"手机淘抢购”</span>找到商品<span class="red">（选择使用“手机淘抢购”查找商品，此任务默认是无线手机端活动）</span>
                        </label>
                        <div class="phone_taobao_con">
                            <div class="phone_taobao_pic row">
                                <div class="col-xs-5">
                                    <div class="phone_goods_pic_con pull-left">
                                        <?php if ($app_search[0]->search_img): ?>
                                        <img src="<?= $app_search[0]->search_img; ?>" height="130" width="130" id="goods_pic2" title="点击更换活动主图" />
                                        <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic2" title="点击上传活动主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="2" onChange="javascript:setImagePreview2(this);" uploaded="<?php echo $app_search[0]->search_img; ?>" base64="" />
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
                                        <img src="<?= $app_search[0]->search_img2; ?>" height="130" width="130" id="goods_pic3" title="点击更换商品主图" />
                                        <?php else: ?>
                                        <img src="/static/imgs/trade/goods_pic.png" id="goods_pic3" title="点击上传商品主图" />
                                        <?php endif; ?>
                                        <input type="file" name="goods_pic" id="3" onChange="javascript:setImagePreview3(this);" uploaded="<?php echo $app_search[0]->search_img2; ?>" base64="" />
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
                                            <span>淘抢购类型：</span>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->kwd == '淘抢购') ? 'checked' : ''; ?> value="淘抢购" />淘抢购</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->kwd == '即将售罄') ? 'checked' : ''; ?> value="即将售罄" />即将售罄</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->kwd == '围观抢') ? 'checked' : ''; ?> value="围观抢" />围观抢</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->kwd == '爆款返场') ? 'checked' : ''; ?> value="爆款返场" />爆款返场</label>
                                            <label style="margin-right:16px;"><input class="app_discount" type="checkbox" <?= ($app_search[0]->kwd == '品牌抢购') ? 'checked' : ''; ?> value="品牌抢购" />品牌抢购</label>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:20px;">
                                        <div class="col-xs-2 text-right" style="margin-top:5px;"><span style="margin-right:4px;color:#fc2104;">*</span>设置淘抢购开抢时间：</div>
                                        <div class="col-xs-9">
                                            <input name="tqg_st_date" style="background:url(/static/imgs/rili2.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({minDate:'%y-%M-#{%d-1}',dateFmt:'yyyy-MM-dd'})" value="<?= $app_search[0]->classify1 ? date('Y-m-d', $app_search[0]->classify1) : ''; ?>" class="blue_input" placeholder="请设置开抢时间" />
                                            <input name="tqg_st_time" style="background:url(/static/imgs/time.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({dateFmt:'HH:mm'})" value="<?= $app_search[0]->classify1 ? date('H:i', $app_search[0]->classify1) : ''; ?>" class="blue_input" placeholder="请设置开抢时分" />
                                            <span>（请提供准确的淘抢购开抢时间）</span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:20px;">
                                        <div class="col-lg-2 text-right" style="margin-top:5px;"><span style="margin-right:4px;color:#fc2104;">*</span>设置任务开始时间：</div>
                                        <div class="col-xs-9">
                                            <input name="task_st_date" style="background:url(/static/imgs/rili2.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({minDate:'%y-%M-#{%d}',dateFmt:'yyyy-MM-dd'})" value="<?= $app_search[0]->classify2 ? date('Y-m-d', $app_search[0]->classify2) : ''; ?>" class="blue_input" placeholder="请设置任务开始时间" />
                                            <input name="task_st_time" style="background:url(/static/imgs/time.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({dateFmt:'HH:mm'})" value="<?= $app_search[0]->classify2 ? date('H:i', $app_search[0]->classify2) : ''; ?>" class="blue_input" placeholder="请设置任务开始时分" />
                                            <span>（任务在此时开始）</span>
                                            <p class="red" style="padding: 8px 0;">温馨提示：客服审核时间为9:00-22:00，请在本时间段内发布任务，否则无法按照您设置的开始时间发布。</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 text-right" style="margin-top:5px;"><span style="margin-right:4px;color:#fc2104;">*</span>设置任务结束时间：</div>
                                        <div class="col-xs-9">
                                            <input name="task_et_date" style="background:url(/static/imgs/rili2.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({minDate:'%y-%M-#{%d}',dateFmt:'yyyy-MM-dd'})" value="<?= $app_search[0]->classify3 ? date('Y-m-d', $app_search[0]->classify3) : ''; ?>" class="blue_input" placeholder="请设置任务结束时间" />
                                            <input name="task_et_time" style="background:url(/static/imgs/time.png) no-repeat 140px;background-size:14%" type="text" onclick="WdatePicker({dateFmt:'HH:mm'})" value="<?= $app_search[0]->classify3 ? date('H:i', $app_search[0]->classify3) : ''; ?>" class="blue_input" placeholder="请设置任务结束时分" />
                                            <span>（任务在此时结束）</span>
                                            <p class="red" style="padding: 8px 0;">温馨提示：任务将会按照您设置的时间自动暂停，若暂停后仍需继续发布，请联系请联系网站客服审核通过。</p>
                                            <p>请分别填写您商品参加手机淘抢购频道的抢购开始、和结束时间。</p>
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
                    <!-- 手机搜索end -->
                    
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
    
    $(".price input,.number input").on('change keyup', function(event) {
        var price  = parseFloat( $(".price").children("input").val() )*10000;
        var number = parseFloat( $(".number").children("input").val() );
        if( !(price > 0) || !(number >0 ) ){
            $(".price_total").text(1);
        }else{
            $(".price_total").text( (price*number/10000).toFixed(2) );
        }
    });

    // 淘抢购类型
    $('.phone_keyword_list').on('click', 'label', function (e) {
        var $this = $(this);
        $this.siblings("label").children("input").attr("checked", false);
//        $this.children("input").prop("checked", true);
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
        if (!$('input[name="pc_check"]').is(":checked") && !$('input[name="phone_check"]').is(":checked")) {
            toastr.warning('请选择使用"' + _plat_name + '搜索框"查找商品或者使用"手机' + _plat_name + '"查找商品');
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

        // 手机淘抢购数据
        var _phone_taobao_con = $('.phone_taobao_con');
        var app_img_1 = _phone_taobao_con.find('#2'), uploaded = app_img_1.attr('uploaded'), app_img1_base64 = app_img_1.attr('base64');
        if (uploaded == '' && app_img1_base64 == ''){
            toastr.warning('请上传淘抢购商品的活动主图');
            return 0;
        }
        var app_img_2 = _phone_taobao_con.find('#3'), uploaded = app_img_2.attr('uploaded'), app_img2_base64 = app_img_2.attr('base64');
        if (uploaded == '' && app_img2_base64 == ''){
            toastr.warning('请上传淘抢购商品的主图');
            return 0;
        }
        var app_discount_item = _phone_taobao_con.find('.app_discount:checked'), app_discount = '';
        if (app_discount_item.length <= 0){
            toastr.warning('请指定淘抢购的类型');
            return 0 ;
        } else {
            app_discount = app_discount_item[0].value;
        }
        var tqg_st_date = _phone_taobao_con.find('input[name="tqg_st_date"]').val();
        var tqg_st_time = _phone_taobao_con.find('input[name="tqg_st_time"]').val();
        if (tqg_st_date == '' || tqg_st_time == ''){
            toastr.warning('请指定淘抢购开抢的完整时间');
            return 0 ;
        }
        var _tqg_time = tqg_st_date + ' ' + tqg_st_time;
        var task_st_date = _phone_taobao_con.find('input[name="task_st_date"]').val();
        var task_st_time = _phone_taobao_con.find('input[name="task_st_time"]').val();
        if (task_st_date == '' || task_st_time == ''){
            toastr.warning('请指定任务开始的完整时间');
            return 0 ;
        }
        var _task_start_time = task_st_date + ' ' + task_st_time;
        var task_et_date = _phone_taobao_con.find('input[name="task_et_date"]').val();
        var task_et_time = _phone_taobao_con.find('input[name="task_et_time"]').val();
        if (task_et_date == '' || task_et_time == ''){
            toastr.warning('请指定任务结束的完整时间');
            return 0 ;
        }
        var _tast_end_time = task_et_date + ' ' +task_et_time;
        var startDate =parseInt(_task_start_time.replace(/[^\d.]/g, ""));
        var endDate =parseInt(_tast_end_time.replace(/[^\d.]/g, ""));
        if(startDate > endDate){
               toastr.warning('开始时间不能大于结束时间');
               return 0;
          }
        var goods_cate = _phone_taobao_con.find('input[name="goods_cate"]').val();

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
            url: "/trade/tqg_step2_1_submit/" + _trade_id,
            data: {
                "goods_url": goods_url, "goods_name": goods_title, "color": guige_color, "size": guige_size, "price": price, "buy_num": number, "show_price": show_price,
                "app_img1_base64": app_img1_base64, "app_img2_base64": app_img2_base64,
                "tqg_time": _tqg_time, "start_time": _task_start_time, "end_time": _tast_end_time,
                "phone_taobao": 1, "type": app_discount, "goods_cate": goods_cate
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
                    var _goods_pic_div = $('.phone_search_box').find('.phone_goods_pic_con2');
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
</script>
</body>
</html>